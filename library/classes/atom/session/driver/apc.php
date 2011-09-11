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
namespace Atom\Session\Driver;

// Aliasing rules
use Atom\Cache;
use Atom\Config;

/**
 * @package    Atom
 * @subpackage Library
 */
class APC implements Driver {

	/**
	 * Load a session by ID.
	 *
	 * @param    string
	 * @return   array
	 */
	public function load($id)
	{
		return Cache::driver('apc')->get($id);
	}

	/**
	 * Save a session.
	 *
	 * @param    array
	 * @return   void             No value is returned
	 */
	public function save($session)
	{
		Cache::driver('apc')->put($session['id'], $session, Config::get('session.lifetime'));
	}

	/**
	 * Delete a session by ID.
	 *
	 * @param    string
	 * @return   void             No value is returned
	 */
	public function delete($id)
	{
		Cache::driver('apc')->forget($id);
	}
}

/* End of file apc.php */