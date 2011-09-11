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
 * The Arr class provides helper functionality for dealing with arrays.
 *
 * @package    Atom
 * @subpackage Library
 */
class Arr {

	/**
	 * Get an item from an array.
	 *
	 * If the specified key is null, the entire array will be returned. The
	 * array may also be accessed using JavaScript "dot" style notation.
	 * Retrieving items nested in multiple arrays is also supported.
	 *
	 * @param    array            The array to be searched
	 * @param    string           The key to retrieve, using "dot" style notation
	 * @param    mixed            A default value to provide if none if found, defaults to null
	 * @return   mixed            Returns the value of the key, otherwise the default
	 */
	public static function get($array, $key, $default = null)
	{
		if($key === null)
		{
			return $array;
		}

		foreach(\explode('.', $key) as $segment)
		{
			if(!\is_array($array) or !isset($array[$segment]))
			{
				return \is_callable($default) ? \call_user_func($default) : $default;
			}

			$array = $array[$segment];
		}

		return $array;
	}

	/**
	 * Set an array item to a given value.
	 *
	 * This method is primarily helpful for setting the value in an array with
	 * a variable depth, such as configuration arrays.
	 *
	 * If the specified item doesn't exist, it will be created. If the item's
	 * parents do not exist, they will also be created as arrays.
	 *
	 * Like the Arr::get method, JavaScript "dot" notation is supported.
	 *
	 * @param    array            The array to be append
	 * @param    string           The key, or chain of keys to set
	 * @param    mixed            The value to be assigned to the key
	 * @return   void             No value is returned
	 */
	public static function set(&$array, $key, $value)
	{
		if($key === null)
		{
			return $array = $value;
		}

		$keys = \explode('.', $key);

		while(\count($keys) > 1)
		{
			$key = array_shift($keys);

			if(!isset($array[$key]) or !\is_array($array[$key]))
			{
				$array[$key] = array();
			}

			$array =& $array[$key];
		}

		$array[\array_shift($keys)] = $value;
	}
}

/* End of file arr.php */