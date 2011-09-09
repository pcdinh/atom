<?php

/**
 * The Http namespaces aims to provide a convenient and powerful set of tools
 * for working with data in an http context (both incoming and outgoing).
 *
 * @package    Atom
 * @subpackage Library
 */
namespace Atom\Http;

// Aliasing rules
use Atom\Config;
use Atom\Benchmark;
use Atom\Profiler;
use Atom\View;

/**
 * Response class
 *
 * @package    Atom
 * @subpackage Library
 */
class Response extends \Atom\Http {

	/**
	 * Factory method for creating a new response instance
	 *
	 * @see      Response::__construct()
	 */
	public static function make($body = null, $status = 200)
	{
		return new static($body, $status);
	}

	/**
	 * The response headers
	 *
	 * @var    array
	 */
	public $headers = array();

	/**
	 * The HTTP status code of this response.
	 *
	 * @var    integer
	 */
	public $status = 200;

	/**
	 * The body of the response.
	 *
	 * @var    mixed
	 */
	public $body;

	/**
	 * Create a new response instance
	 *
	 * @param    mixed            The initial response body
	 * @param    integer          The initial response status code
	 * @return   Response         The current instance of the object
	 */
	public function __construct($body = null, $status = 200)
	{
		$this->body($body);
		$this->set_status($status);
	}

	/**
	 * Get the parsed body of this response.
	 *
	 * @return   string           The parsed body of this response
	 */
	public function __toString()
	{
		return $this->body();
	}

	/**
	 * Add a header to the request
	 *
	 * @param    string           The literal name of the header
	 * @param    string           The value of the header
	 * @return   Response         The current instance of the object
	 */
	public function set_header($name, $value)
	{
		$this->headers[$name] = $value;
		return $this;
	}

	/**
	 * Set the response status code
	 *
	 * @param    integer          The response status code
	 * @return   Response         The current instance of the object
	 */
	public function set_status($status = 200)
	{
		$this->status = $status;
		return $this;
	}

	/**
	 * Set or get the parsed body of this response
	 *
	 * @param    string           The response body to be set
	 * @return   Response|string  The current instance of the object, or the body of the response if $value is === false
	 */
	public function body($body = false)
	{
		if($body === false)
		{
			return (string) $this->body;
		}

		$this->body = $body;
		return $this;
	}

	/**
	 * Send the response headers to the browser.
	 *
	 * @return   Response         The current instance of the object
	 */
	public function send_headers()
	{
		header(static::$protocol.' '.$this->status.' '.static::$statuses[$this->status]);

		foreach($this->headers as $name => $value)
		{
			header($name.': '.$value, true);
		}

		return $this;
	}

	/**
	 * Send the response to the browser
	 *
	 * @return   Response         The current instance of the object
	 */
	public function send()
	{
		if(!array_key_exists('Content-Type', $this->headers))
		{
			$this->set_header('Content-Type', 'text/html; charset='.Config::get('application.encoding', 'utf-8'));
		}

		if(!headers_sent())
		{
			$this->send_headers();
		}

		if($this->body instanceof View)
		{
			$this->body = $this->body->render();
		}

		// Since profiling is so vital to our developers, we're just going to
		// assume it's just as vital to you. We might make this toggleable in
		// the future.
		echo str_replace(array('{exec_time}', '{mem_usage}'), array(Benchmark::check('atom_start_time').' seconds', Benchmark::memory()), $this->body);

		return $this;
	}
}

/* End of file response.php */