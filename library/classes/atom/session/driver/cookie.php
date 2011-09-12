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
use Atom\Cookie;
use Atom\Config;
use Atom\Crypt;

/**
 * @package    Atom
 * @subpackage Library
 */
class Cookie implements Driver {

	/**
	 * Create a new Cookie session driver instance.
	 *
	 * @return   void             No value is returned
	 */
	public function __construct()
	{
		if(Config::get('application.key') == '')
		{
			throw new Exception\Basic('You must set an application key before using the Cookie session driver.');
		}
	}

	/**
	 * Load a session by ID.
	 *
	 * @param    string
	 * @return   array
	 */
	public function load($id)
	{
		if(Cookie::has('session_payload'))
		{
			return unserialize(Crypt::decrypt(Cookie::get('session_payload')));
		}
	}

	/**
	 * Save a session.
	 *
	 * @param    array
	 * @return   void             No value is returned
	 */
	public function save($session)
	{
		if(!headers_sent())
		{
			extract(Config::get('session'));
			Cookie::set('session_payload', Crypt::encrypt(serialize($session)), $lifetime, $path, $domain, $https, $http_only);
		}
	}

	/**
	 * Delete a session by ID.
	 *
	 * @param    string
	 * @return   void             No value is returned
	 */
	public function delete($id)
	{
		Cookie::forget('session_payload');
	}
}

/* End of file cookie.php */