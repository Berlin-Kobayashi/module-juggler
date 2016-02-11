<?php

namespace AppBundle\Controller;

use AppBundle\Model\ModuleJuggler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Document\Module;
use Symfony\Component\HttpFoundation\Response;

class ModuleJuggleController extends Controller
{

	/**
	 * @Route("/mj/api/modules/juggle")
	 * @Method("GET")
	 */
	public function juggleModulesAction(Request $request)
	{
		$dm = $this->get('doctrine_mongodb')->getManager();

		$moduleIds = explode(',', $request->query->get('ids'));

		$moduleJuggler = new ModuleJuggler($dm);

		try {

			$juggledIds = $moduleJuggler->juggle($moduleIds);
			return new JsonResponse($juggledIds);

		} catch (\Exception $e) {

			$response = new Response();
			$response->setStatusCode(Response::HTTP_BAD_REQUEST);
			$response->setContent($e->getMessage());

			return $response;

		}
	}

}
