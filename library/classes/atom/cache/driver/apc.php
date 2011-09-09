<?php

/**
 * Cache driver namespace, this contains all the main driver files. All sub
 * classes are stored in their relevant sub namespace named after the cache
 * driver.
 *
 * @package    Atom
 * @subpackage Library
 */
namespace Atom\Cache\Driver;

// Aliasing rules
use Atom\Config;

/**
 * APC cache driver
 *
 * This driver enables access to APC base caching using the apc extension
 *
 * @package    Atom
 * @subpackage Library
 */
class APC implements \Atom\Cache\Driver {

	/**
	 * Determine if an item exists in the cache.
	 *
	 * @param    string
	 * @return   boolean
	 */
	public function has($key)
	{
		return !is_null($this->get($key));
	}

	/**
	 * Get an item from the cache.
	 *
	 * @param    string
	 * @return   mixed
	 */
	public function get($key)
	{
		return !is_null($cache = apc_fetch(Config::get('cache.key').$key)) ? $cache : null;
	}

	/**
	 * Write an item to the cache.
	 *
	 * @param    string
	 * @param    mixed
	 * @param    integer
	 * @return   void             No value is returned
	 */
	public function set($key, $value, $minutes = null)
	{
		is_null($minutes) and $minutes = Config::get('cache.minutes', 60);
		apc_store(Config::get('cache.key').$key, $value, $minutes * 60);
	}

	/**
	 * Delete an item from the cache.
	 *
	 * @param    string
	 * @return   void             No value is returned
	 */
	public function forget($key)
	{
		apc_delete(Config::get('cache.key').$key);
	}
}

/* End of file apc.php */