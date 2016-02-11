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
		$dm = $this->get('doctrine_mongodb')->getManager();

		$modulesPath = $this->get('kernel')->getRootDir() . '/../modules';

		$moduleImporter = new ModuleImporter($dm);

		$result = $moduleImporter->importFromDirectory($modulesPath);

		return new JsonResponse($result);
	}

}
