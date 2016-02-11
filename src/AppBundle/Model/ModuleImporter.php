<?php
namespace AppBundle\Model;

use AppBundle\Document\Module;

class ModuleImporter
{

	const MODULE_FILE_EXTENSION = 'json';

	protected $dm;

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
	 * @param string $modulesPath
	 * @return array
	 */
	public function importFromDirectory($modulesPath)
	{
		$dir = new \DirectoryIterator($modulesPath);

		$result = [];

		foreach ($dir as $file) {

			if (!$file->isDot() && $file->getExtension() == self::MODULE_FILE_EXTENSION) {

				$contents = file_get_contents($file->getRealPath());
				$contents = utf8_encode($contents);
				$data = json_decode($contents, true);

				// all imported modules are default modules
				$data['default'] = true;

				$imported = self::importFromArray($data);

				if ($imported !== false) {
					$result[] = $imported;
				}

			}

		}

		return $result;
	}

	public function importFromArray($array)
	{
		$dm = $this->getDm();

		if (isset($array['id'])) {

			/** @var Module $module */
			$module = $dm->getRepository('AppBundle:Module')->find($array['id']);

			if ($module) {
				return false;
			}

		}

		$module = new Module();
		$module->fromArray($array);

		$dm->persist($module);
		$dm->flush();

		return $module->toArray();
	}

}