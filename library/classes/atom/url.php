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

// Aliasing rules
use Atom\Arr;

/**
 * Provides methods for working with URL objects
 *
 * @package    Atom
 * @subpackage Library
 */
class Url {

	/**
	 * Holds the current Url object instance, saves reparsing.
	 *
	 * @var    Atom\Url
	 */
	protected static $current;

	/**
	 * The base url of the application
	 *
	 * @var    string
	 */
	protected static $base;

	/**
	 * Create a new url object
	 *
	 * @see      Uri::__construct()
	 */
	public static function make($url)
	{
		return new static($url);
	}

	/**
	 * Creates a url with the given uri, including the base url
	 *
	 * @param    string|array     s
	 * @param    string           The uri
	 * @param    array            Variable replacements for the uri, using :key syntax
	 * @param    array            $_GET variables to append to the url
	 * @return   string           The generated url
	 */
	public static function create($uri = null, array $variables = array(), array $get_variables = array())
	{
		$url = '';

		if(!preg_match('/^(http|https|ftp):\/\//i', $uri))
		{
			$url .= static::base();

			if(($index = Config::get('application.index')) != false)
			{
				$url .= $index;
			}
		}

		$url = $url.(is_null($uri) ? '' : '/'.$uri);

		substr($url, -1) != '/' and $url .= Config::get('application.url_suffix');

		if(!empty($get_variables))
		{
			$char = strpos($url, '?') === false ? '?' : '&';

			foreach($get_variables as $key => $val)
			{
				$url .= $char.$key.'='.$val;
				$char = '&';
			}
		}

		foreach($variables as $key => $val)
		{
			$url = str_replace(':'.$key, $val, $url);
		}

		return trim($url);
	}

	/**
	 * Generate a new URL object from the current URL data passed to the server.
	 * Save it for later re-use.
	 *
	 * @returns  Atom\Url         The current Url object
	 */
	public static function current()
	{
		if(static::$current == '')
		{
			$url = '';

			if(($input = Arr::get($_SERVER, 'PATH_INFO')) != false)
			{
				$url = $input;
			}
			elseif(($input = Arr::get($_SERVER, 'REQUEST_URI')) != false)
			{
				$url = $input;
			}

			// Remove the base url from the url
			$base_url = parse_url(static::base(), PHP_URL_PATH);

			if($url != '' and strncmp($url, $base_url, strlen($base_url)) === 0)
			{
				$url = substr($url, strlen($base_url));
			}

			// If we are using an index file (not mod_rewrite) then remove it
			$index_file = Config::get('application.index');

			if($index_file and strncmp($url, $index_file, strlen($index_file)) === 0)
			{
				$url = substr($url, strlen($index_file));
			}

			// Lastly, strip the defined url suffix from the url if needed
			if(($ext = Config::get('application.url_suffix')) != false)
			{
				strrchr($url, substr($ext, 0, 1)) === $ext and $url = substr($url, 0, -strlen($ext));
			}

			static::$current = static::make((string) $url);
		}

		return static::$current;
	}

	/**
	 * Returns the desired segment, or false if it does not exist.
	 *
	 * @access	public
	 * @param	int		The segment number
	 * @return	string
	 */
	public static function segment($segment, $default = null)
	{
		return \Request::active()->uri->get_segment($segment, $default);
	}

	/**
	 * Returns the application's base URL
	 *
	 * @return   string           The application's base url
	 */
	public static function base()
	{
		if(is_null(static::$base))
		{
			static::$base = Config::get('application.url', null);

			substr(static::$base, -1) != '/' and static::$base .= '/';
		}

		return static::$base;
	}

	/**
	 * The URL
	 *
	 * @var    string
	 */
	protected $url;

	/**
	 * The URL segments
	 *
	 * @var    array
	 */
	protected $segments;

	/**
	 * Class constructor
	 *
	 * @param    string           Raw URI data
	 * @return   Url              The current instance of the object
	 */
	public function __construct($url)
	{
		// Do some cleanup on the url
		$this->url = str_replace(array('//', '../'), '/', trim($url, '/'));

		// Format the url segments into an array
		$this->segments = array_merge(array(), array_filter(explode('/', $this->url)));
	}

	/**
	 * Returns the determined url
	 *
	 * @return    string
	 */
	public function get()
	{
		return $this->uri;
	}

	/**
	 * Returns all of the request segments
	 *
	 * @return   array            An array of all parsed segments
	 */
	public function get_segments()
	{
		return $this->segments;
	}

	/**
	 * Returns the desired segment, or false if it does not exist.
	 *
	 * @see      Arr::get
	 */
	public function get_segment($segment, $default = null)
	{
		return Arr::get($this->segments, $segment, $default);
	}

	/**
	 * Output the current URL
	 *
	 * @return   string           The current url
	 */
	public function __toString()
	{
		return (string) $this->url;
	}
}

/* End of file uri.php */