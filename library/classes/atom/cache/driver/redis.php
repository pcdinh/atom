<?php

/**
 * Cache Access Layer implementation. This namespace controls all access to the
 * caching resources. Multiple drivers for the cache's can be loaded at the same
 * time, along with multiple connections, even to the same cache.
 *
 * @package    Atom
 * @subpackage Library
 */
namespace Atom\Cache;

/**
 * Redis cache driver
 *
 * This driver enables access to Redis base caching using the redis class.
 *
 * @package    Atom
 * @subpackage Library
 */
class Redis implements \Atom\Cache\Driver {

	/**
	 * Determine if an item exists in the cache.
	 *
	 * @param    string
	 * @return   boolean
	 */
	public function has($key);

	/**
	 * Get an item from the cache.
	 *
	 * @param    string
	 * @return   mixed
	 */	
	public function get($key);

	/**
	 * Write an item to the cache.
	 *
	 * @param    string
	 * @param    mixed
	 * @param    integer
	 * @return   void             No value is returned
	 */
	public function set($key, $value, $minutes = 30);

	/**
	 * Delete an item from the cache.
	 *
	 * @param    string
	 * @return   void             No value is returned
	 */
	public function forget($key);
}

/* End of file driver.php */