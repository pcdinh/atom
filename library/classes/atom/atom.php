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
use Atom\Config;
use Atom\Http\Response;
use Atom\Uri;

/**
 * @package    Atom
 * @subpackage Library
 */
class Atom {

	/**
	 * Atom routing dispatcher
	 *
	 * The dispatcher instanciates a uri and http\response instance, and then
	 * passes the response object to the routed method for handling.
	 *
	 * @return   \Http\Response
	 */
	public static function dispatch()
	{
		$url = Url::current();

		$default = Config::get('routes._default_');
		$default = explode('/', $default);

		$controller = (isset($default[0]) ? ucfirst($default[0]) : false);
		$action = (isset($default[1]) ? 'action_'.$default[1] : 'action_index');

		if($controller == false)
		{
			throw new Exception\Basic('Your application does not have a default route configured. Please specify one now.');
		}

		$namespace = Config::get('routes.namespace', 'application');

		// Determine if we're attempting to load a package or the application
		if($url->get_segment(0) and strtolower($url->get_segment(0)) !== strtolower($namespace) and is_dir(\CLASS_PATH.$url->get_segment(0)))
		{
			$namespace = ucfirst($url->get_segment(0));

			// Detect our controller and action
			!is_null($url->get_segment(1)) and $controller = ucfirst($url->get_segment(1));
			!is_null($url->get_segment(2)) and $action = 'action_'.$url->get_segment(2);
		}
		else
		{
			$namespace = ucfirst($namespace);

			// Detect our controller and action
			!is_null($url->get_segment(0)) and $controller = ucfirst($url->get_segment(0));
			!is_null($url->get_segment(1)) and $action = 'action_'.$url->get_segment(1);
		}

		$class = '\\'.$namespace.'\Controller\\'.$controller;
		$response = Response::make();

		if(class_exists($class))
		{
			$controller = new $class($response);
		}

		if(!is_object($controller) or !method_exists($controller, $action))
		{
			throw new Exception\Http\Request(404, $response);
		}

		if(method_exists($controller, 'before'))
		{
			$controller->before();
		}

		$controller->{$action}();

		if(method_exists($controller, 'after'))
		{
			$controller->after();
		}

		return $response;
	}
}

/* End of file atom.php */