<?php

namespace system;

use DateTime;
/**
 * Class Validator
 * @author Ing. Pavol Horvath
 */
class Validator
{
	/**
	 * @var
	 */
	private $value;

	/**
	 * @var string
	 */
	private $name = '';

	/**
	 * @var array
	 */
	private $errors = [];

	/**
	 * @param        $value
	 * @param string $name
	 *
	 * @return $this
	 */
	public function setVariable ($value, string $name):Validator
	{
		$this->setValue($value);
		$this->setName($name);

		return $this;
	}

	/**
	 * @return array
	 */
	public function getErrors ():array
	{
		return $this->errors;
	}

	/**
	 * run custom validator methode from model
	 * @param string $model
	 * @param string $validator
	 *
	 * @return $this
	 */
	public function custom (string $model, string $validator):Validator
	{
		$modelName = "app\\models\\{$model}";
		$mehodeName = "{$validator}Validator";
		$errorMsg = $modelName::$mehodeName($this->value, $this->name);

		if ($errorMsg) {
			$this->errors[] = $errorMsg;
		}

		return $this;
	}

	/**
	 * @param string $errorMsg
	 *
	 * @return $this
	 */
	public function isInteger (string $errorMsg = ''):Validator
	{
		if (strval(intval($this->value)) != $this->value) {
			$this->errors[] = $errorMsg != "" ? $errorMsg : "{$this->name} musí byť celé číslo.";
		}

		return $this;
	}

	/**
	 * @param string $errorMsg
	 *
	 * @return $this
	 */
	public function notEmpty (string $errorMsg = ''):Validator
	{
		if (empty($this->value)) {
			$this->errors[] = $errorMsg != "" ? $errorMsg : "{$this->name} musí byť vyplnený.";
		}

		return $this;
	}

	/**
	 * @param string $format
	 * @param string $errorMsg
	 *
	 * @return $this
	 */
	public function isDate (string $format = 'Y-m-d H:i:s', string $errorMsg = ''):Validator
	{
		if (DateTime::createFromFormat($format, $this->value) === false) {
			$this->errors[] = $errorMsg != "" ? $errorMsg : "{$this->name} nie je v správnom formáte.";
		}

		return $this;
	}

	/**
	 * @param $value
	 */
	private function setValue ($value)
	{
		$this->value = $value;
	}

	/**
	 * @param $name
	 */
	private function setName ($name)
	{
		$this->name = $name;
	}

	/**
	 * @param        $value
	 * @param string $name
	 *
	 * @return Validator
	 */
	public static function init ($value = null, string $name = '')
	{
		$validator = new Validator();
		$validator->setVariable($value, $name);

		return $validator;
	}
}