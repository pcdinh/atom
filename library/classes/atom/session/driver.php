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
interface Driver {

	/**
	 * Load a session by ID.
	 *
	 * @param    string
	 * @return   array
	 */
	public function load($id);

	/**
	 * Save a session.
	 *
	 * @param    array
	 * @return   void             No value is returned
	 */
	public function save($session);

	/**
	 * Delete a session by ID.
	 *
	 * @param    string
	 * @return   void             No value is returned
	 */
	public function delete($id);
}

/* End of file driver.php */