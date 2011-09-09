<?php

/**
 * Error configurations
 *
 * @var    array
 */
return array(

	/**
	 * Debug mode
	 *
	 * Details error messages contain information about the file in which an
	 * error occurs, a strack track, and a snapshot of the source code in which
	 * the error occured.
	 *
	 * If your application is in production, consider turning off error details
	 * for enhanced security and user experience.
	 *
	 * @var    boolean
	 */
	'debug' => true,

	/**
	 * Error logging
	 *
	 * Error logging will use the "logger" method defined below to log error
	 * messages, which gives you complete freedom to determine how error
	 * error messages are logged. Enjoy the flexibility.
	 *
	 * @var    boolean
	 */
	'log' => false,

	/**
	 * Error logger
	 *
	 * Because of the various ways there are to manage error logging, you get
	 * complete freedom to manage error logging as you see fit.
	 *
	 * This function will be called when an error occurs in your application.
	 * You can log the error however you like.
	 *
	 * The error "severity" passed to the method is a human-readable severity
	 * level such as "Parsing Error" or "Fatal Error".
	 *
	 * A simple logging system has been set up for you. By default, all errors
	 * will be logged in the storage/error.log file.
	 *
	 * @var    mixed
	 */
	'logger' => function($severity, $message, $trace)
	{
		// Do some fancy error logging here, in the future. kthx.
	},

	/**
	 * Http error handler
	 *
	 * Because Atom makes no assumption of your routing logic, all Http errors
	 * are handled through the Exception\Http\Response class.
	 *
	 * This method is designed to be a callback to return the value of your
	 * response, which gives you the ability to render errors however you
	 * choose.
	 *
	 * The error "code" passed to this method is the http code, as an integer.
	 * The "message" value is the human-readable equivelant to the code
	 * provided.
	 *
	 * @var    mixed
	 */
	'http_error' => function($code, $message)
	{
		$titles = array('Aw, crap!', 'Bloody Hell!', 'Uh Oh!', 'Nope, not here.', 'Huh?');

		return View::make('template')->partial('content', 'errors/http', array('title' => $titles[array_rand($titles)], 'code' => $code, 'message' => $message));
	},

);

/* End of errors.php */