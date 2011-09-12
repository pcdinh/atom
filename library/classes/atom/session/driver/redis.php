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
use Atom\Session;

/**
 * Redis session driver
 *
 * @package    Atom
 * @subpackage Library
 */
class Redis implements Driver {

	/**
	 * Load a session by ID.
	 *
	 * @param    string
	 * @return   array
	 */
	public function load($id)
	{
		return Cache::driver('redis')->get('session_'.$id);
	}

	/**
	 * Save a session.
	 *
	 * @param    array  $session
	 * @return   void             No value is returned
	 */
	public function save($session)
	{
		Cache::driver('redis')->set('session_'.$session['id'], $session, Config::get('session.lifetime'));
	}

	/**
	 * Delete a session by ID.
	 *
	 * @param    string
	 * @return   void             No value is returned
	 */
	public function delete($id)
	{
		Cache::driver('redis')->forget('session_'.$id);
	}
}

/* End of file redis.php */