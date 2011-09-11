<?php

/**
 * Core Atom library namespace. This namespace contains all of the fundamental
 * components of the Atom framework, plus additional utilities that are provided
 * by default. Some of these default components have sub namespaces if they
 * provide children objects.
 *
 * @package    Atom
 * @subpackage Library
 */
namespace Atom;

/**
 * Input class
 *
 * The input class allows you to access HTTP parameters, load server variables
 * and user agent details.
 *
 * @package    Atom
 * @subpackage Library
 */
class Input implements Design\Invokable {

	/**
	 * Retrievable input sources
	 *
	 * @var    array
	 */
	protected static $sources = array();

	/**
	 * Magic method called when creating a new instance of the object from the
	 * autoloader.
	 *
	 * @return   void             No value is returned
	 */
	public static function invoke()
	{
		static::$sources = array(
			'get' => $_GET,
			'post' => $_POST,
			'request' => array_merge($_GET, $_POST),
			'cookie' => $_COOKIE,
			'server' => $_SERVER,
		);
	}

	/**
	 * Simplified retrieval of information that exists within the $sources
	 *
	 * @param    string          The input source
	 * @param    string          The index key
	 * @param    mixed           The default value to return if none is found, defaults to null
	 * @return   mixed           Returns the value of the index, otherwise $default
	 */
	public static function __callStatic($method, $parameters)
	{
		if(isset(static::$sources[$method]))
		{
			return Arr::get(static::$sources[$method], Arr::get($parameters, 0), Arr::get($parameters, 1));
		}

		throw new Exception\Basic('Call to undefined method Atom\Input::'.$method.'()');
	}

	/**
	 * Return's the input method used (GET, POST, DELETE, etc..)
	 *
	 * @return   string          The input method used for this request
	 */
	public static function method()
	{
		return static::server('REQUEST_METHOD', 'GET');
	}

	/**
	 * Fetch an item from the php://input for put arguments
	 *
	 * @param    string          The index key
	 * @param    mixed           The default value to return if none is found, defaults to null
	 * @return   mixed           Returns the value of the index, otherwise $default
	 */
	public static function put($index, $default = null)
	{
		static $_PUT;

		if(static::method() !== 'PUT')
		{
			return $default;
		}

		if(!isset($_PUT))
		{
			parse_str(file_get_contents('php://input'), $_PUT);
			!is_array($_PUT) and $_PUT = array();
		}

		return Arr::get($_PUT, $index, $default);
	}

	/**
	 * Fetch an item from the php://input for delete arguments
	 *
	 * @param    string          The index key
	 * @param    mixed           The default value to return if none is found, defaults to null
	 * @return   mixed           Returns the value of the index, otherwise $default
	 */
	public static function delete($index, $default = null)
	{
		static $_DELETE;

		if(static::method() !== 'DELETE')
		{
			return $default;
		}

		if(!isset($_DELETE))
		{
			parse_str(file_get_contents('php://input'), $_DELETE);
			!is_array($_DELETE) and $_DELETE = array();
		}

		return Arr::get($_DELETE, $index, $default);
	}
}

/* End of file input */