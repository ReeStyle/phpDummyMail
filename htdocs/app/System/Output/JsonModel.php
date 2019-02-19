<?php

namespace App\System\Output;

use App\System\Interfaces\Output as OutputInterface;
use DateTime;

class JsonModel
implements OutputInterface
{

	/**
	 * @var array|object
	 */
	protected $data;

	/**
	 * @var int
	 */
	protected $options = 0;

	/**
	 * @var int
	 */
	protected $depth = 512;

	/**
	 * @var bool
	 */
	protected $expires = true;

	/**
	 * @var int
	 */
	protected $expiryDate = 0;

	/**
	 * JsonModel constructor
	 *
	 * @param array|object|int|bool|string $data
	 * @param int $options
	 * @param int $depth Greater than zero
	 */
	public function __construct($data = null, $options = 0, $depth = 512)
	{
		$this->setData($data);
		$this->setOptions($options);
		$this->setDepth($depth);
	}

	/**
	 * @return array|object
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @param array|object $data
	 *
	 * @return $this
	 */
	public function setData($data)
	{
		$this->data = $data;
		return $this;
	}

	/**
	 * @param int $options Bitmask
	 *
	 * @brief JSON_HEX_QUOT, JSON_HEX_TAG, JSON_HEX_AMP, JSON_HEX_APOS, JSON_NUMERIC_CHECK, JSON_PRETTY_PRINT, JSON_UNESCAPED_SLASHES, JSON_FORCE_OBJECT, JSON_PRESERVE_ZERO_FRACTION, JSON_UNESCAPED_UNICODE, JSON_PARTIAL_OUTPUT_ON_ERROR, JSON_UNESCAPED_LINE_TERMINATORS, JSON_THROW_ON_ERROR
	 *
	 * @return $this
	 */
	public function setOptions($options)
	{
		if (is_int($options)) {
			$this->options = $options;
		}

		return $this;
	}

	/**
	 * @return int
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * @return mixed
	 */
	public function getDepth()
	{
		return $this->depth;
	}

	/**
	 * @param mixed $depth
	 *
	 * @return $this
	 */
	public function setDepth($depth)
	{
		if (is_int($depth)) {
			$this->depth = $depth;
		}

		return $this;
	}

	/**
	 * @return bool
	 */
	public function willExpire()
	{
		return $this->expires === true;
	}

	/**
	 * @param bool $expires
	 *
	 * @return $this
	 */
	public function setExpires($expires)
	{
		$this->expires = $expires;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getExpiryDate()
	{
		return $this->expiryDate;
	}

	/**
	 * @param int $expiryDate
	 *
	 * @return $this
	 */
	public function setExpiryDate( $expiryDate)
	{
		if (is_string($expiryDate)) {
			$expiryDate = strtotime($expiryDate);
		}

		if ($expiryDate instanceof DateTime) {
			$expiryDate = $expiryDate->getTimestamp();
		}

		if (is_int($expiryDate)) {
			$this->expiryDate = $expiryDate;
		}

		return $this;
	}

	/**
	 * JSONify
	 */
	public function stream()
	{
		header('Content-type: application/json; charset: utf-8');

		if ($this->willExpire()) {
			header('Pragma: no-cache');
			header("Cache-Control: no-cache, no-store, must-revalidate");
			header("Expires: " . date('f', $this->getExpiryDate()));
		}

		print json_encode($this->getData(), $this->getOptions());
	}
}