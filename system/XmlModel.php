<?php

namespace system;

class XmlModel
{
	/**
	 * @var string
	 */
	static protected $xmlFile = '';

	/**
	 * @var string
	 */
	static protected $xmlNode = '';

	/**
	 * @var array
	 */
	static protected $xmlProperties = [];

	/**
	 * @var string
	 */
	static protected $xmlKeyProperties = 'id';

	/**
	 * @var array
	 */
	static protected $dependentProperties = [];

	/**
	 * XmlModel constructor.
	 *
	 * @param array $properties
	 */
	public function __construct (array $properties)
	{
		$this->set($properties);
	}

	/**
	 * @param array $properties
	 *
	 * @return $this
	 */
	public function set (array $properties)
	{
		foreach ($properties as $property => $value) {
			$this->$property = $value;
		}
		$this->setDependentProperties();

		return $this;
	}

	/**
	 * @return $this
	 */
	public function update ()
	{
		XmlReader::init(static::$xmlFile, static::$xmlNode, static::$xmlProperties)
			->update(static::$xmlKeyProperties, (array)$this);

		return $this;
	}

	/**
	 * @return $this
	 */
	public function add ()
	{
		$res = XmlReader::init(static::$xmlFile, static::$xmlNode, static::$xmlProperties)
			->add(static::$xmlKeyProperties, (array)$this);
		$keyName = static::$xmlKeyProperties;
		$this->$keyName = $res;

		return $this;
	}

	/**
	 * @return null
	 */
	public function remove ()
	{
		$keyName = static::$xmlKeyProperties;
		XmlReader::init(static::$xmlFile, static::$xmlNode, static::$xmlProperties)
			->remove(static::$xmlKeyProperties, $this->$keyName);

		return null;
	}

	/**
	 *
	 */
	protected function setDependentProperties ()
	{
		foreach (static::$dependentProperties as $property)
		{
			$methodeName = 'set' . ucfirst($property);
			$this->$methodeName();
		}
	}

	/**
	 * @param array $conditions
	 *
	 * @return array|mixed|null
	 */
	public static function get (array $conditions = [])
	{
		$xmlItems = XmlReader::init(static::$xmlFile, static::$xmlNode, static::$xmlProperties)
			->getData($conditions);

		$modaelName = get_called_class();
		$items = [];
		foreach ($xmlItems as $xmlItem) {
			$items[] = new $modaelName($xmlItem);
		}

		if (!count($items)) {
			return null;
		} elseif (count($items) == 1) {
			return $items[0];
		} else {
			return $items;
		}
	}
}