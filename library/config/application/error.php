<?php

/**
 * Error configurations
 *
 * @var    array
 */
return array(

	/**
	 * Error handler
	 *
	 * Because Atom makes no assumptions on how your application handles routing
	 * or display, all errors that occur will be passed to this function as an
	 * exception. This way, you have complete flexibility to manage error
	 * reporting and logging as you see fit.
	 *
	 * @var    mixed
	 */
	'handler' => function(Exception $e)
	{
		$content = ob_get_contents();

		while(ob_get_level() > 0)
		{
			ob_end_clean();
		}

		ob_start();

		$response = \Atom\Http\Response::make(null, 500);

		$response->body(View::make('template')->partial('content', 'error/fatal', array('title' => 'An error has occured!', 'e' => $e)));

		$response->send();

		exit(ob_get_clean());
	},

	/**
	 * Http error handler
	 *
	 * Similar to the error handler above, http errors are customizable to your
	 * preferences. These errors are separated from the normal error handler as
	 * they should be handled differently than a normal exception, to display
	 * some type of page based on the $code provided.
	 *
	 * The $code passed to this method is the http status code, as an integer.
	 * The $message passeed to this method is the human-readable equivelant to
	 * the code provided.
	 *
	 * @var    mixed
	 */
	'http_handler' => function($code, $message)
	{
		$titles = array('Aw, crap!', 'Bloody Hell!', 'Uh Oh!', 'Nope, not here.', 'Huh?');

		return View::make('template')->partial('content', 'error/http', array('title' => $titles[array_rand($titles)], 'code' => $code, 'message' => $message));
	},

);

/* End of errors.php */