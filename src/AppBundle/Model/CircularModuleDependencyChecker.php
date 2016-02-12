<?php
namespace AppBundle\Model;

use AppBundle\Document\Module;

class CircularModuleDependencyChecker
{

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
	 * @param string $moduleId
	 * @param string $dependencyId
	 * @return bool
	 */
	public function isCircularDependency($moduleId, $dependencyId)
	{

		$repository = $this->getDm()->getRepository('AppBundle:Module');

		/** @var Module $module */
		$module = $repository->findOneById($moduleId);

		$allDependencies = $this->getAllDependencies($module);

		$isCircularDependency = in_array($dependencyId, $allDependencies);

		return $isCircularDependency;
	}

	/**
	 * @param Module $module
	 * @return string[]
	 */
	private function getAllDependencies($module)
	{
		$dependencies = [];

		$this->addAllDependenciesRecursively($module, $dependencies);

		return $dependencies;
	}

	/**
	 * @param Module $module
	 * @param string[] $dependencies
	 */
	private function addAllDependenciesRecursively($module, &$dependencies)
	{
		$repository = $this->getDm()->getRepository('AppBundle:Module');

		foreach ($module->getDependsOn() as $dependencyID) {

			$dependencies[] = $dependencyID;
			$dependency = $repository->findOneById($dependencyID);

			$this->addAllDependenciesRecursively($dependency, $dependencies);

		}
	}

	/**
	 * @param string[] $moduleIds
	 * @return array
	 * @throws \Exception
	 */
	public function juggle($moduleIds)
	{
		/** @var Module[] $modules */
		$modules = $this->getModulesMapByIds($moduleIds);

		return $this->juggleMap($modules);
	}

	/**
	 * @param Module[] $modules
	 * @return array
	 * @throws \Exception
	 */
	public static function juggleMap($modules)
	{
		$result = [];

		foreach ($modules as $moduleID => $module) {
			self::juggleMapEntry($modules, $moduleID, $result);
		}

		return $result;
	}

	/**
	 * @param Module[] $modules
	 * @param $moduleId
	 * @param $result
	 * @throws \Exception
	 */
	private static function juggleMapEntry(&$modules, $moduleId, &$result)
	{
		if (in_array($moduleId, $result)) {
			return;
		}

		if (!isset($modules[$moduleId])) {
			throw new \Exception('Could not juggle modules. Required module with ID ' . $moduleId . ' is missing.');
		}

		$module = $modules[$moduleId];

		if (!is_null($module->getDependsOn())) {
			foreach ($module->getDependsOn() as $requiredModuleId) {
				self::juggleMapEntry($modules, $requiredModuleId, $result);
			}
		}

		$result[] = $moduleId;
		unset($modules[$moduleId]);
		return;
	}

	/**
	 * @param $moduleIds
	 * @return Module[]
	 */
	private function getModulesMapByIds($moduleIds)
	{
		$repository = $this->getDm()->getRepository('AppBundle:Module');

		/** @var Module[] $modules */
		$modules = [];

		foreach ($moduleIds as $moduleId) {

			$module = $repository->findOneById($moduleId);

			if ($module) {
				$modules[$moduleId] = $module;
			}

		}

		return $modules;
	}

}