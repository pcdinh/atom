<?php

/**
 * Core Atom library namespace. This namespace contains all of the fundamental
 * components of the Atom framework, plus additional utilities that are provided
 * by default. Some of these default components have sub namespaces if they
 * provide children objects.
 *
 * @package    Atom
 * @subpackage Library
 */
namespace Atom;

// Aliasing rules
use Atom\Exception;

/**
 * Autoloader
 *
 * @package    Atom
 * @subpackage Library
 */
class Loader {

	/**
	 * Default separator for classes, this is commonly '_' for non-namespaced
	 * code, and '\\' for namespaced.
	 *
	 * @var    string
	 */
	public static $separator = '\\';

	/**
	 * Class aliases
	 *
	 * @var    array
	 */
	public static $aliases = array();

	/**
	 * Default root to load from, defaults to the classes path.
	 *
	 * @var    string
	 */
	public static $root = \CLASS_PATH;

	/**
	 * Bootstrap's the autoloader by adding itself to the SPL autoload stack, as
	 * well as loading in useful configurations.
	 *
	 * @see       http://php.net/spl_autoload_register
	 * @return    void             No value is returned
	 */
	public static function bootstrap()
	{
		\spl_autoload_register('Atom\Loader::load', true, true);

		static::$aliases = (array) Config::get('aliases', array());
	}

	/**
	 * Normalize a class name into a path
	 *
	 * @param    string           The class to convert
	 * @return   string           Returns the matching path
	 */
	public static function get_normalized_path($name)
	{
		if(\strpos($name, static::$separator) !== false)
		{
			$name = \str_replace(static::$separator, '/', $name);
		}

		$path = \strtolower(\trim($name));

		return static::$root.$path.EXT;
	}

	/**
	 * Autoloads a class, if a class fails to load, an exception is thrown via
	 * the spl_autoload registry.
	 *
	 * @param    string           The class or interface to autoload
	 * @return   boolean          Returns true if loaded, otherwise false if loading failed
	 */
	public static function load($name)
	{
		if(static::exists($name))
		{
			return true;
		}

		if(isset(static::$aliases[$name]))
		{
			return \class_alias(static::$aliases[$name], $name);
		}

		$path = static::get_normalized_path($name);

		if(!is_file($path))
		{
			return false;
		}

		require $path;

		if(!static::exists($name))
		{
			return false;
		}

		if(($ifaces = \class_implements($name, true)) !== false and isset($ifaces['Atom\Design\Invokable']))
		{
			\call_user_func(array($name, 'invoke'));
		}

		return true;
	}

	/**
	 * Check whether a class or interface exists without attempting to autoload
	 * them.
	 *
	 * @param    string           The class or interface to check
	 * @return   boolean          True if exists, otherwise false
	 */
	public static function exists($name)
	{
		return (bool) (\class_exists($name, false) or \interface_exists($name, false));
	}
}

/* End of file loader.php */