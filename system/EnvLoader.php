<?php

namespace system;

use http\Exception\InvalidArgumentException;

class EnvLoader
{
	private $envPath = __DIR__ . '/../.env';

	/**
	 *
	 */
	public function loadEnvFile ()
	{
		$lines = file($this->envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		foreach ($lines as $line) {
			if (strpos(trim($line), '#') === 0) {
				continue;
			}

			list($name, $value) = explode('=', $line, 2);
			$name = trim($name);
			$value = trim($value);

			putenv(sprintf('%s=%s', $name, $value));
		}
	}

	/**
	 * @param string $path
	 */
	public function setEnvPath (string $path)
	{
		if (substr($path, -4) == '.env' && is_file($path)) {
			$this->envPath = $path;
		}
	}

	/**
	 * @return EnvLoader
	 */
	public static function init ():EnvLoader
	{
		return new EnvLoader();
	}

	/**
	 * @param string $key
	 *
	 * @return array|bool|string|null
	 */
	public static function getEnvValue (string $key)
	{
		$value = getenv($key);

		switch (strtolower($value)) {
			case 'true':
			case '(true)':
				return true;
			case 'false':
			case '(false)':
				return false;
			case 'empty':
			case '(empty)':
				return '';
			case 'null':
			case '(null)':
				return null;
		}

		if (strlen($value) > 1 && substr($value, 0, 1) == '"' && substr($value, -1) == '"') {
			return substr($value, 1, -1);
		}

		return $value;
	}
}