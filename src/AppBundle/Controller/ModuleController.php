<?php

namespace AppBundle\Controller;


use AppBundle\Model\ModuleImporter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Document\Module;
use Symfony\Component\HttpFoundation\Response;

class ModuleController extends Controller
{

	/**
	 * @Route("/mj/api/modules")
	 * @Method("POST")
	 */
	public function createModuleAction(Request $request)
	{
		$input = json_decode((string)$request->getContent(), true);

		$dm = $this->get('doctrine_mongodb')->getManager();

		$moduleImporter = new ModuleImporter($dm);

		$result = $moduleImporter->importFromArray($input);

		if ($result === false) {
			$response = new Response();
			$response->setStatusCode(Response::HTTP_BAD_REQUEST);
			$response->setContent("Could not create module because given ID already is in use.");
			return $response;
		}

		return new JsonResponse($result);
	}

	/**
	 * @Route("/mj/api/modules")
	 * @Method("GET")
	 */
	public function getAllModulesAction(Request $request)
	{
		$repository = $this->get('doctrine_mongodb')
			->getManager()
			->getRepository('AppBundle:Module');

		$criteria = self::extractResourceCriteriaFromRequest($request);

		/** @var Module[] $modules */
		$modules = $repository->findBy($criteria);

		$result = [];

		foreach ($modules as $module) {
			$result[] = $module->toArray();
		}

		return new JsonResponse($result);
	}

	/**
	 * @Route("/mj/api/modules/{id}")
	 * @Method("PUT")
	 */
	public function updateModuleAction(Request $request, $id)
	{
		$dm = $this->get('doctrine_mongodb')->getManager();

		/** @var Module $module */
		$module = $dm->getRepository('AppBundle:Module')->find($id);

		if (!$module) {
			$response = new Response();
			$response->setStatusCode(Response::HTTP_NOT_FOUND);
			return $response;
		}

		$input = json_decode((string)$request->getContent(), true);
		$module->fromArray($input);

		$dm->flush();

		return new JsonResponse($module->toArray());
	}

	/**
	 * @Route("/mj/api/modules/{id}")
	 * @Method("PATCH")
	 */
	public function partialUpdateModuleAction(Request $request, $id)
	{
		$dm = $this->get('doctrine_mongodb')->getManager();

		/** @var Module $module */
		$module = $dm->getRepository('AppBundle:Module')->find($id);

		if (!$module) {
			$response = new Response();
			$response->setStatusCode(Response::HTTP_NOT_FOUND);
			return $response;
		}

		$input = json_decode((string)$request->getContent(), true);
		$module->fillByArray($input);

		$dm->flush();

		return new JsonResponse($module->toArray());
	}

	/**
	 * @Route("/mj/api/modules/{id}")
	 * @Method("DELETE")
	 *
	 * @return Response Status 200 if a module was deleted, Status 404 if no module was found or Status 400 if the module depends another module
	 */
	public function deleteModuleAction(Request $request, $id)
	{
		$dm = $this->get('doctrine_mongodb')->getManager();
		$module = $dm->getRepository('AppBundle:Module')->find($id);

		if (!$module) {
			$response = new Response();
			$response->setStatusCode(Response::HTTP_NOT_FOUND);
			return $response;
		}


		if (self::doesModuleDependOtherModules($module)) {
			$response = new Response();
			$response->setStatusCode(Response::HTTP_BAD_REQUEST);
			$response->setContent("Requested module depends other modules and can therefore not be deleted.");
			return $response;
		}

		$dm->remove($module);
		$dm->flush();

		return new Response();
	}


	/**
	 * Extracts the criteria for modules from a request.
	 * @param Request $request
	 * @return array associative array containing the criteria
	 */
	private static function extractResourceCriteriaFromRequest(Request $request)
	{
		$id = $request->query->get('id');
		$name = $request->query->get('name');
		$code = $request->query->get('code');
		$default = $request->query->get('default');

		$criteria = array();

		if ($id != null) {
			$criteria['_id'] = $id;
		}

		if ($name != null) {
			$criteria['name'] = $name;
		}

		if ($code != null) {
			$criteria['code'] = $code;
		}

		if ($default != null) {

			if (strtolower($default) == 'true') {
				$criteria['default'] = true;
			}

			if (strtolower($default) == 'false') {
				$criteria['default'] = null;
			}

		}

		return $criteria;
	}

	/**
	 * @param Module $module
	 * @return bool
	 */
	private function doesModuleDependOtherModules(Module $module)
	{
		$repository = $this->get('doctrine_mongodb')
			->getManager()
			->getRepository('AppBundle:Module');

		/** @var Module[] $modules */
		$modules = $repository->findAll();

		foreach ($modules as $currentModule) {

			if (in_array($module->getId(), $currentModule->getDependsOn())) {
				return true;
			}

		}

		return false;
	}

}
