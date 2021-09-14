<?php

namespace app\models;

use system\XmlModel;

class Employee extends XmlModel
{
	/**
	 * @var int
	 */
	public $id;

	/**
	 * @var string
	 */
	public $firstName;

	/**
	 * @var string
	 */
	public $lastName;

	/**
	 * @var string
	 */
	public $phone;

	/**
	 * @var string
	 */
	public $email;

	/**
	 * @var date
	 */
	public $birthDate;

	/**
	 * @var date
	 */
	public $employmentDate;

	/**
	 * @var int
	 */
	public $age;

	/**
	 * @var int
	 */
	public $employmentAge;

	/**
	 * @var string
	 */
	static protected $xmlFile = 'employees';

	/**
	 * @var string
	 */
	static protected $xmlNode = 'employee';

	/**
	 * @var array
	 */
	static protected $xmlProperties = [
		'id' => 'integer',
		'firstName' => 'string',
		'lastName' => 'string',
		'phone' => 'string',
		'email' => 'string',
		'birthDate' => 'date',
		'employmentDate' => 'date',
	];

	/**
	 * @var array
	 */
	static protected $dependentProperties = [
		'age',
		'employmentAge',
	];

	/**
	 * setter for dependent propertie
	 */
	protected function setAge ()
	{
		$this->age = floor((time() - strtotime($this->birthDate)) / 31556926);
	}

	/**
	 * setter for dependent propertie
	 */
	protected function setEmploymentAge ()
	{
		$this->employmentAge = floor((time() - strtotime($this->employmentDate)) / 31556926);
	}

	/**
	 * @param        $value
	 * @param string $name
	 *
	 * @return string
	 */
	public static function existValidator ($value, string $name):string
	{
		if (!Employee::get(['id' => $value])) {
			return "Zadaný zamestnanec neexistuje";
		}

		return "";
	}
}