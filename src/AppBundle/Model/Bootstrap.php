<?php
namespace AppBundle\Model;

class Bootstrap
{

	const BOOTSTRAP_FILE_EXTENSION = 'sh';

	/** @var  string */
	protected $name;

	/** @var  string */
	protected $content;

	/** @var  string */
	protected $path;

	/**
	 * Bootstrap constructor.
	 * @param string $name
	 * @param string $content
	 * @param string $path
	 */
	public function __construct($name, $content, $path)
	{
		$this->name = $name;
		$this->content = $content;
		$this->path = $path;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * @param string $content
	 */
	public function setContent($content)
	{
		$this->content = $content;
	}

	/**
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * @param string $path
	 * @return Bootstrap
	 */
	public function setPath($path)
	{
		$this->path = $path;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function save()
	{
		$finalName = $this->calculateFinalName();

		$this->setName($finalName);

		$absolutePath = $this->getPath() . DIRECTORY_SEPARATOR . $finalName . '.' . self::BOOTSTRAP_FILE_EXTENSION;

		file_put_contents($absolutePath, $this->getContent());

		return true;
	}

	/**
	 * @return string
	 */
	private function calculateFinalName()
	{
		$currentName = $this->getName();

		$currentName = str_replace('/', '_', $currentName);
		$currentName = str_replace(' ', '_', $currentName);

		$baseName = $currentName;

		for ($i = 2; $this->doesBootstrapAlreadyExist($currentName); $i++) {
			$currentName = $baseName . $i;
		}

		return $currentName;

	}

	/**
	 * @param string $name
	 * @return bool
	 */
	private function doesBootstrapAlreadyExist($name)
	{
		$dir = new \DirectoryIterator($this->getPath());

		foreach ($dir as $file) {

			if (!$file->isDot() && $file->getExtension() == self::BOOTSTRAP_FILE_EXTENSION) {

				$bootstrapAlreadyExists = $file->getFilename() == $name . '.' . self::BOOTSTRAP_FILE_EXTENSION;

				if ($bootstrapAlreadyExists) {

					return true;

				}

			}

		}

		return false;
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		$result = array();
		$result['name'] = (string)$this->getName();
		$result['content'] = (string)$this->getContent();

		return $result;
	}

}