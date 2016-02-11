<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Module
{

	/**
	 * @MongoDB\Id
	 */
	protected $id;

	/**
	 * @MongoDB\String
	 */
	protected $name;

	/**
	 * @MongoDB\String
	 */
	protected $code;

	/**
	 * @MongoDB\Collection
	 */
	protected $dependsOn;

	/**
	 * @MongoDB\Boolean
	 */
	protected $default;

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $id
	 * @return Module
	 */
	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param mixed $name
	 * @return Module
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * @param mixed $code
	 * @return Module
	 */
	public function setCode($code)
	{
		$this->code = $code;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDependsOn()
	{
		return $this->dependsOn;
	}

	/**
	 * @param mixed $dependsOn
	 * @return Module
	 */
	public function setDependsOn($dependsOn)
	{
		$this->dependsOn = $dependsOn;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDefault()
	{
		if (is_null($this->default)) {
			return false;
		}

		return $this->default;
	}

	/**
	 * @param mixed $default
	 * @return Module
	 */
	public function setDefault($default)
	{
		if (is_null($this->default)) {
			$this->default = false;
		}

		$this->default = $default;

		return $this;
	}

	/**
	 * @param array $array
	 * @return Module
	 */
	public function fillByArray($array)
	{
		if (isset($array['id'])) {
			$this->setId($array['id']);
		}

		if (isset($array['name'])) {
			$this->setName($array['name']);
		}

		if (isset($array['code'])) {
			$this->setCode($array['code']);
		}

		if (isset($array['default'])) {
			$this->setDefault($array['default']);
		}

		if (isset($array['depends_on'])) {
			$this->setDependsOn($array['depends_on']);
		}

		return $this;
	}

	/**
	 * @param array $array
	 * @return Module
	 */
	public function fromArray($array)
	{
		if (isset($array['id'])) {
			$this->setId($array['id']);
		} else {
			$this->id = null;
		}

		if (isset($array['name'])) {
			$this->setName($array['name']);
		} else {
			$this->name = null;
		}

		if (isset($array['code'])) {
			$this->setCode($array['code']);
		} else {
			$this->code = null;
		}

		if (isset($array['default'])) {
			$this->setDefault($array['default']);
		} else {
			$this->default = null;
		}

		if (isset($array['depends_on'])) {
			$this->setDependsOn($array['depends_on']);
		} else {
			$this->dependsOn = null;
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		$result = array();
		$result['id'] = (string)$this->getId();
		$result['name'] = (string)$this->getName();
		$result['code'] = (string)$this->getCode();
		$result['default'] = $this->getDefault();
		$result['depends_on'] = $this->getDependsOn();

		return $result;
	}

}
