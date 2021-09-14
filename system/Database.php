<?php

namespace system;

use system\EnvLoader;
use mysqli;
/**
 * Class Database
 */
class Database
{
	/**
	 * @var string
	 */
	private $host;

	/**
	 * @var int
	 */
	private $port;

	/**
	 * @var string
	 */
	private $userName;

	/**
	 * @var string
	 */
	private $password;

	/**
	 * @var string
	 */
	private $dbName;

	/**
	 * @var mysqli
	 */
	private $connection;

	/**
	 * @var string
	 */
	private $query = '';

	/**
	 * data from mysqli SELECT
	 * @var
	 */
	private $data;


	/**
	 * connect to DB
	 * Database constructor.
	 */
	public function __construct ()
	{
		$this->host = EnvLoader::getEnvValue('DB_HOST');
		$this->userName = EnvLoader::getEnvValue('DB_USER_NAME');
		$this->password = EnvLoader::getEnvValue('DB_PASSWORD');
		$this->dbName = EnvLoader::getEnvValue('DB_DATABASE');
		$this->port = EnvLoader::getEnvValue('DB_PORT');

		$this->connection = new mysqli(
			$this->host,
			$this->userName,
			$this->password,
			$this->dbName,
			$this->port
		);
	}

	/**
	 * set mysql query manualy
	 * @param string $query
	 *
	 * @return $this
	 */
	public function setQuery (string $query):Database
	{
		$this->query = $query;
		var_dump($this->query);

		return $this;
	}

	/**
	 * composition QUERY
	 * @param string $table
	 * @param array  $columns
	 *
	 * @return $this
	 */
	public function select (string $table, array $columns = []):Database
	{
		$columns = $columns ? '`' . implode('`, `', $columns) . '`' : '*';
		$this->query = "SELECT {$columns} FROM `{$table}`";

		return $this;
	}

	/**
	 * composition QUERY
	 * @param string $table
	 * @param array  $columns
	 *
	 * @return $this
	 */
	public function update (string $table, array$columns = []):Database
	{
		$this->query = "UPDATE `{$table}` SET ";

		$qColumns = array_map(function ($value, $column) {
			$value = $this->escape($value);
			return "`{$column}` = '{$value}'";
		}, $columns, array_keys($columns));

		$this->query .= implode(", ", $qColumns);

		return $this;
	}

	/**
	 * composition QUERY
	 * @param string $table
	 * @param array  $columns
	 *
	 * @return $this
	 */
	public function insert (string $table, array $columns = []):Database
	{
		$this->query = "INSERT INTO `{$table}` SET ";

		$qColumns = array_map(function ($value, $column) {
			$value = $this->escape($value);
			return "`{$column}` = '{$value}'";
		}, $columns, array_keys($columns));

		$this->query .= implode(", ", $qColumns);

		return $this;
	}

	/**
	 * composition QUERY
	 * @param string $table
	 *
	 * @return $this
	 */
	public function delete (string $table):Database
	{
		$this->query = "DELETE FROM `{$table}`";

		return $this;
	}

	/**
	 * composition QUERY
	 * @param array $statements
	 *
	 * @return $this
	 */
	public function where (array $statements):Database
	{
		if ($statements) {
			$this->query .= " WHERE ";

			$qStatements = array_map(function ($value, $column) {
				$value = $this->escape($value);
				return "`{$column}` = '{$value}'";
			}, $statements, array_keys($statements));

			$this->query .= implode(" AND ", $qStatements);
		}

		return $this;
	}

	/**
	 * composition QUERY
	 * @param string $column
	 * @param string $type
	 *
	 * @return $this
	 */
	public function orderBy (string $column, string $type = 'ASC'):Database
	{
		$this->query .= " ORDER BY `{$column}` {$type}";

		return $this;
	}

	/**
	 * return 1 value from SELECT
	 * @return mixed|null
	 */
	public function getValue ()
	{
		$this->query .= " LIMIT 1";
		$this->run();

		if (!$this->data) {
			return null;
		}

		$value = $this->data->fetch_row()[0];
		return $value;
	}

	/**
	 * return values from one column SELECT
	 * @return array
	 */
	public function getValues ():array
	{
		$this->run();
		$rows = $this->data->fetch_all();

		$values = array_map(function ($row) {
			return $row[0];
		}, $rows, array_keys($rows));

		return $values;
	}

	/**
	 * return one row from SELECT as associative array
	 * @return array|null
	 */
	public function getRow ()
	{
		$this->query .= " LIMIT 1";
		$this->run();

		if (!$this->data) {
			return null;
		}

		$row = $this->data->fetch_assoc();
		return $row;
	}

	/**
	 * return more rows from SELECT
	 * @return array
	 */
	public function getRows ()
	{
		$this->run();
		$fields = $this->data->fetch_fields();
		$dbRows = $this->data->fetch_all();

		$rows = array_map(function ($dbRow) use ($fields) {
			$row = [];
			foreach ($dbRow as $column => $value) {
				$row[ $fields[$column]->name ] = $value;
			}
			return $row;
		}, $dbRows, array_keys($dbRows));

		return $rows;
	}

	/**
	 * execute query
	 */
	private function run ()
	{
		if (substr($this->query, -1) != ';') {
			$this->query .= ";";
		}

		$this->data = $this->connection->query( $this->query );
	}

	/**
	 * @param string $value
	 *
	 * @return string
	 */
	public function escape ($value):string
	{
		$escapedValue = $this->connection->real_escape_string($value);

		return $escapedValue;
	}

	/**
	 * execute query
	 * @return $this
	 */
	public function execute ():Database
	{
		$this->run();
		return $this;
	}

	/**
	 * return last insert ID
	 * @return int
	 */
	public function insertId ():int
	{
		return $this->connection->insert_id;
	}

	/**
	 * @return Database
	 */
	public static function init ()
	{
		return new Database();
	}
}