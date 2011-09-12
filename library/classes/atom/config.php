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
 * Provides methods for working with configuration items residing within
 * configuration files
 *
 * @package    Atom
 * @subpackage Library
 */
class Config implements Design\Invokable {

	/**
	 * All of the loaded configuration items.
	 *
	 * @var     array
	 */
	public static $items = array();

	/**
	 * Magic method called when creating a new instance of the object from the
	 * autoloader.
	 *
	 * @return   void             No value is returned
	 */
	public static function invoke()
	{
		$url = static::get('application.url');

		if(empty($url))
		{
			if(Arr::get($_SERVER, 'HTTP_HOST'))
			{
				$url  = (strtolower(Arr::get($_SERVER, 'HTTPS', 'off')) !== 'off') ? 'https' : 'http';
				$url .= '://'.Arr::get($_SERVER, 'HTTP_HOST');
				$url .= str_replace(basename(Arr::get($_SERVER, 'SCRIPT_NAME')), '', Arr::get($_SERVER, 'SCRIPT_NAME'));
			}
			else
			{
				$url = 'http://localhost/';
			}

			static::set('application.url', $url);
		}
	}

	/**
	 * Determine if a configuration item or file exists.
	 *
	 * @param     string
	 * @return    boolean
	 */
	public static function has($key)
	{
		return !(static::get($key) === null);
	}

	/**
	 * Get a configuration item.
	 *
	 * Configuration items are retrieved using "dot" notation. So, asking for the
	 * "application.timezone" configuration item would return the "timezone" option
	 * from the "application" configuration file.
	 *
	 * If the name of a configuration file is passed without specifying an item, the
	 * entire configuration array will be returned.
	 *
	 * @param     string
	 * @param     string
	 * @return    array
	 */
	public static function get($key, $default = null)
	{
		list($module, $file, $key) = static::parse($key);

		if(!static::load($module, $file))
		{
			return is_callable($default) ? call_user_func($default) : $default;
		}

		if(is_null($key))
		{
			return static::$items[$module][$file];
		}

		return Arr::get(static::$items[$module][$file], $key, $default);
	}

	/**
	 * Set a configuration item.
	 *
	 * @param     string
	 * @param     mixed
	 * @return    void
	 */
	public static function set($key, $value)
	{
		list($module, $file, $key) = static::parse($key);

		if(!static::load($module, $file))
		{
			throw new \Exception("Error setting configuration option. Configuration file [$file] is not defined.");
		}

		Arr::set(static::$items[$module][$file], $key, $value);
	}

	/**
	 * Save configuration values changes to their configuration file
	 *
	 * [!!] This method is currently undeveloped, and will be developed as time
	 *      permits
	 *
	 * @param    string           The configuration file name
	 * @param    string|array	  A new set of configuration data, or the items key name from the config $item's
	 * @return   boolean          Returns true if the config file was successfully created, otherwise false.
	 */
	public static function save($file, $config)
	{
	}

	/**
	 * Parse a configuration key.
	 *
	 * The value on the left side of the dot is the configuration file
	 * name, while the right side of the dot is the item within that file.
	 *
	 * @param     string
	 * @return    array
	 */
	private static function parse($key)
	{
		$module = (strpos($key, '::') !== false) ? substr($key, 0, strpos($key, ':')) : 'application';

		if($module !== 'application')
		{
			$key = substr($key, strpos($key, ':') + 2);
		}

		$key = (count($segments = explode('.', $key)) > 1) ? implode('.', array_slice($segments, 1)) : null;

		return array($module, $segments[0], $key);
	}

	/**
	 * Load all of the configuration items from a file.
	 *
	 * Atom supports environment specific configuration files. So, the base
	 * configuration array will always be loaded first, and then any environment
	 * specific options will be merged in later.
	 *
	 * @param     string
	 * @param     string
	 * @return    boolean
	 */
	private static function load($module, $file)
	{
		if(isset(static::$items[$module]) and array_key_exists($file, static::$items[$module]))
		{
			return true;
		}

		// Load in the default (atom) configurations, if one exists. Once that
		// is loaded, we can merge the modules configuration options into the
		// base array. This allows for the convenient cascading of configuration
		// options.
		$config = (file_exists($path = CONFIG_PATH.'atom/'.$file.EXT) ? require $path : array());

		if($module != 'atom')
		{
			if(file_exists($path = CONFIG_PATH.$module.'/'.$file.EXT))
			{
				$config = array_merge($config, require $path);
			}
		}

		if(count($config) > 0)
		{
			static::$items[$module][$file] = $config;
		}

		return isset(static::$items[$module][$file]);
	}
}

/* End of file config.php */