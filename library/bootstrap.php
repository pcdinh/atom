<?php

// Aliasing rules
use Atom\Config;
use Atom\Loader;

// Include functionality we cannot autoload
require_once CLASS_PATH.'atom/loader'.EXT;

// Register Atom\Loader on the auto-loader stack.
\spl_autoload_register('Atom\Loader::load', true, true);

// Add available aliases to the Loader
Loader::$aliases = (array) Config::get('aliases', array());

// Set the default timezone
date_default_timezone_set(Config::get('application.timezone'));

// Set various handlers for errors, exceptions and shutdown
set_exception_handler(function($e) { call_user_func(Config::get('error.handler'), $e); });
set_error_handler(function($number, $error, $file, $line) { call_user_func(Config::get('error.handler'), new \ErrorException($error, $number, 0, $file, $line)); });
register_shutdown_function(function()
{
	if(($error = error_get_last()) !== null)
	{
		$exception = new \ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']);

		call_user_func(Config::get('error.handler'), $exception);
	}
});

/* End of file bootstrap.php */