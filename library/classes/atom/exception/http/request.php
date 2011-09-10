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
namespace Atom\Exception\Http;

// Aliasing rules
use Atom\Config;
use Atom\Exception\Basic;
use Atom\Http;
use Atom\Http\Response;
use Atom\View;

/**
 * Http request errors
 *
 * @package    Atom
 * @subpackage Library
 */
class Request extends Basic {

	/**
	 * Create a new response rendered with the error code.
	 *
	 * @param     integer          The HTTP error code, defaults to a 404
	 * @param     Response         The current response object, if one is not defined, one will be generated
	 * @return    void             No value is returned
	 */
	public function __construct($code = 404, Response $response = null)
	{
		if($code < 400)
		{
			throw new Exception\Basic('An invalid call to Atom\\Exception\\Http\\Request was thrown. This exception is meant to handle HTTP errors.');
		}

		is_null($response) and $response = $response = Response::make();

		$response->set_status($code)->body(call_user_func(Config::get('error.http_handler'), $code, Http::$statuses[$code]))->send();

		exit;
	}
}

/* End of file exception.php */