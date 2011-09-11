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
namespace Atom\Session;

/**
 * @package    Atom
 * @subpackage Library
 */
interface Sweeper {

	/**
	 * Delete all expired sessions.
	 *
	 * @param    integer
	 * @return   void             No value is returned
	 */
	public function sweep($expiration);
}

/* End of file sweeper.php */