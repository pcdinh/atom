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
namespace Atom;

/**
 * Session class
 *
 * This class is designed to provided helper methods for interfacing with
 * sessions.
 *
 * @package    Atom
 * @subpackage Library
 */
class Session {

	/**
	 * The active session driver
	 *
	 * @var    Session\Driver
	 */
	public static $driver;

	/**
	 * The session payload, which contains the session ID, data and last
	 * activity timestamp.
	 *
	 * @var    array
	 */
	public static $session = array();

	/**
	 * Get the active session driver
	 *
	 * @return   Session\Driver
	 */
	public static function driver()
	{
		if(static::$driver === null)
		{
			switch(Config::get('session.driver'))
			{
				case 'cookie':
				{
					return static::$driver = new Session\Driver\Cookie;
				}
				break;
				case 'file':
				{
					return static::$driver = new Session\Driver\File;
				}
				break;
				case 'db':
				{
					return static::$driver = new Session\Driver\DB;
				}
				break;
				case 'memcached':
				{
					return static::$driver = new Session\Driver\Memcached;
				}
				break;
				case 'apc':
				{
					return static::$driver = new Session\Driver\APC;
				}
				break;
				default:
				{
					throw new \Exception('Session driver [%s] is not supported.', $driver);
				}
				break;
			}			
		}

		return static::$driver;
	}

	/**
	 * Load a user session by ID.
	 *
	 * @param    string           The user session id
	 * @return   void             No value is returned
	 */
	public static function load($id)
	{
		static::$session = ($id === null) ? static::driver()->load($id) : null;

		if(static::invalid(static::$session))
		{
			static::$session = array('id' => Str::random(40), 'data' => array());
		}

		if(!static::has('csrf_token'))
		{
			static::set('csrf_token', Str::random(16));
		}

		static::$session['last_activity'] = time();
	}

	/**
	 * Determine if a session is valid.
	 *
	 * A session is considered valid if it exists and has not expired.
	 *
	 * @param    array
	 * @return   boolean
	 */
	private static function invalid($session)
	{
		return ($session === null) or (time() - $session['last_activity']) > (Config::get('session.lifetime') * 60);
	}

	/**
	 * Determine if the session or flash data contains an item.
	 *
	 * @param    string
	 * @return   boolean
	 */
	public static function has($key)
	{
		return !(static::get($key) === null);
	}

	/**
	 * Get an item from the session or flash data.
	 *
	 * @param    string
	 * @return   mixed
	 */
	public static function get($key, $default = null)
	{
		foreach(array($key, ':old:'.$key, ':new:'.$key) as $possibility)
		{
			if(array_key_exists($possibility, static::$session['data']))
			{
				return static::$session['data'][$possibility];
			}
		}

		return is_callable($default) ? call_user_func($default) : $default;
	}

	/**
	 * Write an item to the session.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return void
	 */
	public static function set($key, $value)
	{
		static::$session['data'][$key] = $value;
	}

	/**
	 * Write an item to the session flash data.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return void
	 */
	public static function flash($key, $value)
	{
		static::set(':new:'.$key, $value);
	}

	/**
	 * Remove an item from the session.
	 *
	 * @param  string  $key
	 * @return void
	 */
	public static function forget($key)
	{
		unset(static::$session['data'][$key]);
	}

	/**
	 * Remove all items from the session.
	 *
	 * @return void
	 */
	public static function flush()
	{
		static::$session['data'] = array();
	}

	/**
	 * Regenerate the session ID.
	 *
	 * @return void
	 */
	public static function regenerate()
	{
		static::driver()->delete(static::$session['id']);

		static::$session['id'] = Str::random(40);
	}

	/**
	 * Close the session.
	 *
	 * The session will be stored in persistant storage and the session cookie will be
	 * session cookie will be sent to the browser. The old input data will also be
	 * stored in the session flash data.
	 *
	 * @return void
	 */
	public static function close()
	{
		static::flash('laravel_old_input', Input::get());

		static::age_flash();

		static::driver()->save(static::$session);

		static::write_cookie();

		if(mt_rand(1, 100) <= 2 and static::driver() instanceof Session\Sweeper)
		{
			static::driver()->sweep(time() - (Config::get('session.lifetime') * 60));
		}
	}

	/**
	 * Age the session flash data.
	 *
	 * @return void
	 */
	private static function age_flash()
	{
		foreach(static::$session['data'] as $key => $value)
		{
			if(strpos($key, ':old:') === 0)
			{
				static::forget($key);
			}
		}

		foreach(static::$session['data'] as $key => $value)
		{
			if(strpos($key, ':new:') === 0)
			{
				static::set(':old:'.substr($key, 5), $value);

				static::forget($key);
			}
		}
	}

	/**
	 * Write the session cookie.
	 *
	 * @return void
	 */
	private static function write_cookie()
	{
		if(!headers_sent())
		{
			$minutes = (Config::get('session.expire_on_close')) ? 0 : Config::get('session.lifetime');

			Cookie::set('laravel_session', static::$session['id'], $minutes, Config::get('session.path'), Config::get('session.domain'), Config::get('session.https'), Config::get('session.http_only'));
		}
	}

}

/* End of file session.php */