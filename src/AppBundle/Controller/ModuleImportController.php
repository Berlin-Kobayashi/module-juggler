<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Document\Module;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Model\ModuleImporter;

class ModuleImportController extends Controller
{

	/**
	 * @Route("/mj/api/modules/import")
	 * @Method("POST")
	 */
	public function importModulesAction(Request $request)
	{
		$input = json_decode((string)$request->getContent(), true);

		$module = new Module();
		$module->fromArray($input);

		$dm = $this->get('doctrine_mongodb')->getManager();
		$dm->persist($module);
		$dm->flush();

		return new JsonResponse($module->toArray());
	}

}
