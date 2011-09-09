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
 * File-based cache driver
 *
 * @package    Atom
 * @subpackage Library
 */
class File implements \Atom\Cache\Driver {

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
		if(!file_exists(CACHE_PATH.$key))
		{
			return null;
		}

		$cache = file_get_contents(CACHE_PATH.$key);

		// The cache expiration date is stored as a UNIX timestamp at the beginning
		// of the cache file. We'll extract it out and check it here.
		if(time() >= substr($cache, 0, 10))
		{
			return $this->forget($key);
		}

		return unserialize(substr($cache, 10));
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
		file_put_contents(CACHE_PATH.$key, (time() + ($minutes * 60)).serialize($value), LOCK_EX);
	}

	/**
	 * Delete an item from the cache.
	 *
	 * @param    string
	 * @return   void             No value is returned
	 */
	public function forget($key)
	{
		@unlink(CACHE_PATH.$key);
	}
}

/* End of file file.php */