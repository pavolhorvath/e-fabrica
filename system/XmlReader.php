<?php

namespace system;

use SimpleXMLIterator;
use system\View;

class XmlReader
{
	/**
	 * @var string
	 */
	private $file;

	/**
	 * @var string
	 */
	private $node;

	/**
	 * @var array
	 */
	private $properties = [];

	/**
	 * @var string
	 */
	private $filePath;

	/**
	 * @var array
	 */
	private $data;

	/**
	 * @var string
	 */
	static private $dirPath = __DIR__.'/../data/xml/';

	/**
	 * @param string $file
	 *
	 * @return $this
	 */
	public function setFile (string $file):XmlReader
	{
		$this->file = $file;
		$this->filePath = self::$dirPath . $file . '.xml';
		return $this;
	}

	/**
	 * @param string $node
	 *
	 * @return $this
	 */
	public function setNode (string $node):XmlReader
	{
		$this->node = $node;
		return $this;
	}

	/**
	 * @param array $properties
	 *
	 * @return $this
	 */
	public function setProperties (array $properties = []):XmlReader
	{
		$this->properties = $properties;
		return $this;
	}

	/**
	 * @param array $conditions
	 *
	 * @return array
	 */
	public function getData (array $conditions = []):array
	{
		$this->loadData();

		if ($conditions) {
			$filteredData = array_filter($this->data, function ($item) use ($conditions) {
				foreach ($conditions as $property => $value) {
					if ($item[$property] != $value) {
						return false;
					}
				}
				return true;
			});
			return $filteredData;
		}

		return $this->data;
	}

	/**
	 * @param string $keyNode
	 * @param array  $updateData
	 *
	 * @return bool
	 */
	public function update (string $keyNode, array $updateData)
	{
		if (!isset($updateData[$keyNode])) {
			return false;
		}

		$this->loadData();

		foreach ($this->data as &$item) {
			if ($item[$keyNode] == $updateData[$keyNode]) {
				foreach ($updateData as $key => $value) {
					if (isset($this->properties[$key])) {
						$item[$key] = $value;
					}
				}
			}
		}

		$this->storeData();
		return true;
	}

	/**
	 * @param string $keyNode
	 * @param array  $addData
	 * @param bool   $isAutoIncrement
	 *
	 * @return bool|int|mixed
	 */
	public function add ( string $keyNode, array $addData, bool $isAutoIncrement = true)
	{
		$this->loadData();

		if ($isAutoIncrement) {
			$maxKeyValue = 0;
			foreach ($this->data as $item) {
				if ($item[$keyNode] > $maxKeyValue) {
					$maxKeyValue = $item[$keyNode];
				}
			}
			$newKeyValue = $maxKeyValue + 1;
			$addData[$keyNode] = $newKeyValue;
		}

		foreach ( $addData as $key => $value) {
			if (!isset($this->properties[$key])) {
				unset($addData[$key]);
			}
		}

		if (!count($addData) || ($isAutoIncrement && count($addData) == 1)) {
			return false;
		} else {
			$this->data[] = $addData;
			$this->storeData();
			if ($isAutoIncrement) {
				return $newKeyValue;
			} else {
				return true;
			}
		}
	}

	/**
	 * @param string $keyNode
	 * @param        $keyValue
	 *
	 * @return bool
	 */
	public function remove (string $keyNode, $keyValue)
	{
		$this->loadData();

		foreach ($this->data as $index => $item) {
			if ($item[$keyNode] == $keyValue) {
				unset($this->data[$index]);
			}
		}

		$this->storeData();
		return true;
	}

	/**
	 * @throws \Exception
	 */
	private function loadData ()
	{
		if (!is_file($this->filePath)) {
			$this->data = [];

		} else {
			$xml = new SimpleXMLIterator($this->filePath, null, true);
			$node = $this->node;
			$items = $xml->$node;
			$data = [];

			if ($items->count()) {
				foreach ($items as $item) {
					$itemData = [];
					foreach ($this->properties as $property => $type) {
						$value = $item->$property->__toString();
						switch ($type) {
							case 'integer':
								$value = intval($value);
								break;
							case 'float':
								$value = floatval($value);
								break;
							case 'boolean':
								$value = strtolower($value) == 'false' || !$value ? false : true;
								break;
						}

						$itemData[$property] = $value;
					}

					if ($itemData) {
						$data[] = $itemData;
					}
				}
			}

			$this->data = $data;
		}
	}

	/**
	 *
	 */
	private function storeData ()
	{
		$xml = View::init()->render('xml', [
			'file' => $this->file,
			'node' => $this->node,
			'items' => $this->data,
		]);

		file_put_contents($this->filePath, $xml);
	}

	/**
	 * @param string $file
	 * @param string $node
	 * @param array  $properties
	 *
	 * @return XmlReader
	 */
	public static function init (string $file, string $node, array $properties):XmlReader
	{
		$XmlReader = new XmlReader();
		$XmlReader->setFile($file)->setNode($node)->setProperties($properties);
		return $XmlReader;
	}
}