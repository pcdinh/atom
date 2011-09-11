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
use Atom\Config;
use Atom\Crypt;

/**
 * @package    Atom
 * @subpackage Library
 */
class Cookie implements Driver {

	/**
	 * The Crypt instance.
	 *
	 * @var    Crypt
	 */
	private $crypt;

	/**
	 * Create a new Cookie session driver instance.
	 *
	 * @return   void             No value is returned
	 */
	public function __construct()
	{
		$this->crypt = new Crypt;

		if(Config::get('application.key') == '')
		{
			throw new \Exception("You must set an application key before using the Cookie session driver.");
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
		if(\System\Cookie::has('session_payload'))
		{
			return unserialize($this->crypt->decrypt(\System\Cookie::get('session_payload')));
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

			$payload = $this->crypt->encrypt(serialize($session));

			\System\Cookie::set('session_payload', $payload, $lifetime, $path, $domain, $https, $http_only);
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
		\System\Cookie::forget('session_payload');
	}
}

/* End of file cookie.php */