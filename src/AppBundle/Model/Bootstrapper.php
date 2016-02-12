<?php
namespace AppBundle\Model;

use AppBundle\Document\Module;

class Bootstrapper
{

	protected $dm;

	const SHEBANG = '#!/usr/bin/env bash';

	const HEADLINE = '# This bootstrap was created by Module Juggler: https://github.com/DanShu93/module-juggler';

	const COMMENT_VERB = 'install';

	/**
	 * ModuleImporter constructor.
	 * @param $dm
	 */
	public function __construct($dm)
	{
		$this->dm = $dm;
	}

	/**
	 * @return mixed
	 */
	public function getDm()
	{
		return $this->dm;
	}

	/**
	 * @param mixed $dm
	 * @return ModuleImporter
	 */
	public function setDm($dm)
	{
		$this->dm = $dm;
		return $this;
	}

	/**
	 * @param string[] $moduleIds
	 * @return string
	 */
	public function bootstrap($moduleIds)
	{
		$repository = $this->getDm()->getRepository('AppBundle:Module');

		$bootstrap = $this->createHead();
		$bootstrap .= PHP_EOL;

		foreach ($moduleIds as $moduleId) {

			/** @var Module $module */
			$module = $repository->findOneById($moduleId);

			// add comment
			$bootstrap .= '# ' . self::COMMENT_VERB . ' ' . $module->getName();
			$bootstrap .= PHP_EOL;

			// add code
			$bootstrap .= $module->getCode();
			$bootstrap .= PHP_EOL;
			$bootstrap .= PHP_EOL;

		}

		return $bootstrap;
	}

	/**
	 * @return string
	 */
	private function createHead()
	{
		$head = self::SHEBANG;
		$head .= PHP_EOL;
		$head .= PHP_EOL;
		$head .= self::HEADLINE;
		$head .= PHP_EOL;

		return $head;
	}

}