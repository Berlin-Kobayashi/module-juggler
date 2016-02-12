<?php

namespace AppBundle\Controller;

use AppBundle\Model\Bootstrap;
use AppBundle\Model\Bootstrapper;
use AppBundle\Model\CircularModuleDependencyChecker;
use AppBundle\Model\ModuleJuggler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Document\Module;
use Symfony\Component\HttpFoundation\Response;

class BootstrapController extends Controller
{

	/**
	 * @Route("/mj/api/bootstraps")
	 * @Method("POST")
	 */
	public function bootstrapAction(Request $request)
	{
		$dm = $this->get('doctrine_mongodb')->getManager();

		$input = json_decode((string)$request->getContent(), true);

		$name = $input['name'];
		$moduleIds = $input['modules'];

		$bootstrapPath = $this->get('kernel')->getRootDir() . '/../bootstraps';

		try {

			$moduleJuggler = new ModuleJuggler($dm);

			$juggledIds = $moduleJuggler->juggle($moduleIds);

			$bootstrapper = new Bootstrapper($dm);
			$content = $bootstrapper->bootstrap($juggledIds);

			$bootstrap = new Bootstrap($name, $content, $bootstrapPath);

			$bootstrap->save();

			return new JsonResponse($bootstrap->toArray());

		} catch (\Exception $e) {

			$response = new Response();
			$response->setStatusCode(Response::HTTP_BAD_REQUEST);
			$response->setContent($e->getMessage());

			return $response;

		}
	}

}
