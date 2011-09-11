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
 * @package    Atom
 * @subpackage Library
 */
class DB implements Driver, Sweeper {

	/**
	 * Load a session by ID.
	 *
	 * @param    string
	 * @return   array
	 */
	public function load($id)
	{
		$session = $this->table()->find($id);

		if(!is_null($session))
		{
			return array(
				'id'            => $session->id,
				'last_activity' => $session->last_activity,
				'data'          => unserialize($session->data)
			);
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
		$this->delete($session['id']);

		$this->table()->insert(array(
			'id'            => $session['id'], 
			'last_activity' => $session['last_activity'], 
			'data'          => serialize($session['data'])
		));
	}

	/**
	 * Delete a session by ID.
	 *
	 * @param    string
	 * @return   void             No value is returned
	 */
	public function delete($id)
	{
		$this->table()->delete($id);
	}

	/**
	 * Delete all expired sessions.
	 *
	 * @param    integer
	 * @return   void             No value is returned
	 */
	public function sweep($expiration)
	{
		$this->table()->where('last_activity', '<', $expiration)->delete();
	}

	/**
	 * Get a session database query.
	 *
	 * @return   Query
	 */
	private function table()
	{
		return \System\DB::connection()->table(Config::get('session.table'));		
	}
}

/* End of file db.php */