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

// Aliasing rules
use Atom\Exception;

/**
 * Cache
 *
 * @package    Atom
 * @subpackage Library
 */
class Cache {

	/**
	 * All of the active cache drivers.
	 *
	 * @var    array
	 */
	public static $drivers = array();

	/**
	 * Pass all other methods to the default driver.
	 *
	 * Passing method calls to the driver instance provides a better API for you.
	 * For instance, instead of saying Cache::driver()->foo(), you can just say Cache::foo().
	 */
	public static function __callStatic($method, $parameters)
	{
		return call_user_func_array(array(static::driver(), $method), $parameters);
	}

	/**
	 * Get a cache driver instance.
	 *
	 * If no driver name is specified, the default cache driver will be returned
	 * as defined in the cache configuration file.
	 *
	 * @param    string           The driver name, or null for the configured default
	 * @return   Cache\Driver
	 */
	public static function driver($driver = null)
	{
		is_null($driver) and $driver = Config::get('cache.driver');

		if(!array_key_exists($driver, static::$drivers))
		{
			switch ($driver)
			{
				case 'file':
				{
					return static::$drivers[$driver] = new Cache\Driver\File;
				}
				break;
				case 'memcached':
				{
					return static::$drivers[$driver] = new Cache\Driver\Memcached;
				}
				break;
				case 'apc':
				{
					return static::$drivers[$driver] = new Cache\Driver\APC;
				}
				break;
				case 'redis':
				{
					return static::$drivers[$driver] = new Cache\Driver\Redis;
				}
				break;
				default:
				{
					throw new Exception\Basic('Cache driver [%s] is not supported.', $driver);
				}
				break;
			}
		}

		return static::$drivers[$driver];
	}

	/**
	 * Get an item from the cache
	 *
	 * @param    string
	 * @param    mixed
	 * @param    string
	 * @return   mixed
	 */
	public static function get($key, $default = null, $driver = null)
	{
		if(is_null($item = static::driver($driver)->get($key)))
		{
			return is_callable($default) ? call_user_func($default) : $default;
		}

		return $item;
	}

	/**
	 * Get an item from the cache. If the item doesn't exist in the cache, store
	 * the default value in the cache and return it.
	 *
	 * @param    string
	 * @param    mixed
	 * @param    integer
	 * @param    string
	 * @return   mixed
	 */
	public static function remember($key, $default, $minutes = null, $driver = null)
	{
		if(!is_null($item = static::get($key, null, $driver)))
		{
			return $item;
		}

		$default = is_callable($default) ? call_user_func($default) : $default;

		static::driver($driver)->set($key, $default, $minutes);

		return $default;
	}
}

/* End of file cache.php */