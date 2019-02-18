<?php

namespace TemplateerPHP;

use Exception;

/**
 * Class TemplateerPHP
 *
 * @package TemplateerPHP
 * @author R. den Heijer
 * @license None - Free, distribute, be happy!
 *
 * Simple PHP template renderer with layout support
 */
class TemplateerPHP
{

	/**
	 * @var array
	 */
	protected $data = [];

	/**
	 * @var string
	 */
	protected $baseDir = '';

	/**
	 * @var string
	 */
	protected $resource = null;

	/**
	 * @var bool
	 */
	protected $implicitLayout = true;

	/**
	 * @var string
	 */
	protected $layout = 'layout/default';

	/**
	 * @var array
	 */
	protected $helpers = [];

	/**
	 * TemplateerPHP constructor.
	 */
	public final function __construct()
	{
		$this->init();
	}

	/**
	 * @throws Exception
	 */
	public function init()
	{
		$this->addHelper(__NAMESPACE__ . '\\Helper\\Partial');
	}

	/**
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @param array $data
	 *
	 * @return $this
	 */
	public function setData(array $data)
	{
		$this->data = $data;
		return $this;
	}

	/**
	 * @param $keyOrMass
	 * @param null $val
	 *
	 * @return $this
	 */
	public function assign($keyOrMass, $val = null)
	{
		if (is_array($keyOrMass)) {
			foreach ($keyOrMass as $var => $val) {
				$this->data[$var] = $val;
			}
		} else {
			if (is_string($keyOrMass)) {
				$this->data[$keyOrMass] = $val;
			}
		}

		return $this;
	}

	/**
	 * @param string $helperClass
	 *
	 * @return $this
	 * @throws Exception
	 */
	public function addHelper($helperClass)
	{
		$helper = new $helperClass($this);

		$helperKey = strtolower(basename($helperClass));

		$this->helpers[$helperKey] = $helper;

		return $this;
	}

	/**
	 * @param string $dir
	 *
	 * @return $this
	 * @throws Exception
	 */
	public function setBaseDir($dir)
	{
		if (is_dir($dir)) {
			$this->baseDir = $dir;
		} else {
			throw new Exception(sprintf('%s is not a directory', $dir), 500);
		}

		return $this;
	}

	/**
	 * @return string
	 */
	public function getBaseDir()
	{
		return $this->baseDir;
	}

	/**
	 * @return string
	 */
	public function getResource()
	{
		return $this->resource;
	}

	/**
	 * @param string $resource
	 *
	 * @return string
	 */
	public function setResource($resource)
	{
		$this->resource = $resource;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isImplicitLayout()
	{
		return $this->implicitLayout;
	}

	/**
	 * @param bool $implicitLayout
	 *
	 * @return $this
	 */
	public function setImplicitLayout($implicitLayout)
	{
		$this->implicitLayout = $implicitLayout;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getLayout()
	{
		return $this->layout;
	}

	/**
	 * @param string $layout
	 *
	 * @return $this
	 */
	public function setLayout($layout)
	{
		$this->layout = $layout;
		return $this;
	}

	/**
	 * @param null $resource
	 * @param bool $stream
	 *
	 * @return null
	 * @throws Exception
	 */
	public function render($resource = null, $stream = true)
	{
		if ($resource === null) {
			$resource = $this->getResource();
		}

		$templateFile = sprintf('%s/%s.phtml', $this->baseDir , $resource);

		// Using a function (lambda in this case) isolates the scope from $this to $templateer
		// Essentially the same, but you can no longer use private and protected methods
		$render = $this->templateLambda();

		$buffer = $render($templateFile, 'template');

		if ($this->isImplicitLayout()) {
			$this->assign([
				'layoutContent' => $buffer,
				'title' => 'The Templateer!',
			]);
			$layoutFile = sprintf('%s/%s.phtml', $this->baseDir , $this->getLayout());

			$buffer = $render($layoutFile, 'layout');
		}

		if ($stream === true) {
			print $buffer;
		}

		return $buffer;
	}

	/**
	 * @return \Closure
	 */
	protected function templateLambda()
	{
		$templateer = $this;

		return function($templateFile, $templatetype) use ($templateer)
		{
			if (empty($templateFile)) {
				throw new Exception(sprintf('No %s file specified', $templatetype));
			}

			if (file_exists($templateFile)) {
				extract($templateer->getData());

				ob_start();

				require $templateFile;

				return ob_get_clean(); // get buffer and cleanup
			} else {
				throw new Exception(sprintf('Cannot find %s "%s"', $templatetype, $templateFile));
			}
		};
	}
}