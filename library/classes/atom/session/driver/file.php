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

/**
 * @package    Atom
 * @subpackage Library
 */
class File implements Driver, Sweeper {

	/**
	 * Load a session by ID.
	 *
	 * @param    string
	 * @return   array
	 */
	public function load($id)
	{
		if(file_exists($path = SESSION_PATH.$id))
		{
			return unserialize(file_get_contents($path));
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
		file_put_contents(SESSION_PATH.$session['id'], serialize($session), LOCK_EX);
	}

	/**
	 * Delete a session by ID.
	 *
	 * @param    string
	 * @return   void             No value is returned
	 */
	public function delete($id)
	{
		@unlink(SESSION_PATH.$id);
	}

	/**
	 * Delete all expired sessions.
	 *
	 * @param    integer
	 * @return   void             No value is returned
	 */
	public function sweep($expiration)
	{
		foreach(glob(SESSION_PATH.'*') as $file)
		{
			if(filetype($file) == 'file' and filemtime($file) < $expiration)
			{
				@unlink($file);
			}
		}
	}
}

/* End of file file.php */