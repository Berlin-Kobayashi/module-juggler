<?php

namespace AppBundle\Controller;

use AppBundle\Model\CircularModuleDependencyChecker;
use AppBundle\Model\ModuleJuggler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Document\Module;
use Symfony\Component\HttpFoundation\Response;

class ModuleCanDependOnController extends Controller
{

	/**
	 * @Route("/mj/api/modules/{module}/can-depend-on/{dependency}")
	 * @Method("GET")
	 */
	public function canDependOnAction(Request $request, $module, $dependency)
	{
		$dm = $this->get('doctrine_mongodb')->getManager();

		$circularModuleDependencyChecker = new CircularModuleDependencyChecker($dm);

		$canDependOn = !$circularModuleDependencyChecker->isCircularDependency($module, $dependency);

		if ($canDependOn) {

			return new Response();

		} else {

			$response = new Response();
			$response->setStatusCode(Response::HTTP_BAD_REQUEST);
			$response->setContent($module . ' can not depend on ' . $dependency . ' because it would lead to a circular dependency.');

			return $response;

		}
	}

}
