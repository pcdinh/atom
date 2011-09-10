<?php

// Aliasing rules
use Atom\Config;
use Atom\Loader;

// Include functionality we cannot autoload
require_once CLASS_PATH.'atom/loader'.EXT;

// Bootstrap the auto-loader
Loader::bootstrap();

// Set the default timezone
date_default_timezone_set(Config::get('application.timezone'));

// Set the exception handler
set_exception_handler(function($e)
{
	call_user_func(Config::get('error.handler'), $e);
});

// Set the error handler
set_error_handler(function($number, $error, $file, $line)
{
	$exception = new \ErrorException($error, $number, 0, $file, $line);

	call_user_func(Config::get('error.handler'), $exception);
});

// Set the shutdown function
register_shutdown_function(function()
{
	if(($error = error_get_last()) !== null)
	{
		$exception = new \ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']);

		call_user_func(Config::get('error.handler'), $exception);
	}
});

/* End of file bootstrap.php */