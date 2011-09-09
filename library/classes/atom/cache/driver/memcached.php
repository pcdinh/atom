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
use Atom\Memcached;
use Atom\Config;

/**
 * Memcached cache driver
 *
 * This driver enables access to Memcached base caching using the memcache
 * extension.
 *
 * @package    Atom
 * @subpackage Library
 */
class Memcached implements \Atom\Cache\Driver {

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
		return (($cache = Memcached::instance()->get(Config::get('cache.key').$key)) !== false) ? $cache : null;
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
		Memcached::instance()->set(Config::get('cache.key').$key, $value, 0, $minutes * 60);
	}

	/**
	 * Delete an item from the cache.
	 *
	 * @param    string
	 * @return   void             No value is returned
	 */
	public function forget($key)
	{
		Memcached::instance()->delete(Config::get('cache.key').$key);
	}
}

/* End of file memcached.php */