<?php

/**
 * Core Atom library namespace. This namespace contains all of the fundamental
 * components of the Atom framework, plus additional utilities that are
 * provided by default. Some of these default components have sub namespaces
 * if they provide children objects.
 *
 * @package    Atom
 * @subpackage Library
 */
namespace Atom;

/**
 * Database class
 *
 * @author     Chase Hutchins     <syntaqx@gmail.com>
 * @package    Atom
 * @subpackage Library
 */
class DB {

	/**
	 * The established database connections.
	 *
	 * @var    array
	 */
	public static $connections = array();

	/**
	 * Magic Method for calling methods on the default database connection.
	 */
	public static function __callStatic($method, $parameters)
	{
		return call_user_func_array(array(static::connection(), $method), $parameters);
	}

	/**
	 * Get a database connection. If no database name is specified, the default
	 * connection will be returned as defined in the db configuration file.
	 *
	 * Note: Database connections are managed as singletons.
	 *
	 * @param    string           The connection name, from the db config file
	 * @return   DB\Connection    The current, or new instance of the DB\Connection object
	 */
	public static function connection($connection = null)
	{
		if(is_null($connection))
		{
			$connection = Config::get('db.default');
		}

		$environment = Config::get('application.environment');

		if(empty($environment))
		{
			throw new \Exception('Your application environment is not defined!');
		}

		if(!array_key_exists($connection, static::$connections))
		{
			if(is_null($config = Config::get('db.connections.'.$environment.'.'.$connection)))
			{
				throw new \Exception('Database connection ['.$connection.'] is not defined.');
			}

			static::$connections[$connection] = new DB\Connection($connection, (object) $config, new DB\Connector);
		}

		return static::$connections[$connection];
	}

	/**
	 * Begin a fluent query against a table.
	 *
	 * @param    string           The table to query
	 * @param    string           The connection name, from the db config. Defaults to the active connection
	 * @param    DB\Query         The new instance of the DB\Query object
	 */
	public static function table($table, $connection = null)
	{
		return static::connection($connection)->table($table);
	}
}

/* End of file db.php */