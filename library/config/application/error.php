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
	 * Because Atom makes no assumptions of how your application handles routing
	 * or display, all errors that occur will be passed to this function as
	 * an exception. This way, you get complete flexibility to manage error
	 * reporting/logging as you see fit.
	 *
	 * @var    mixed
	 */
	'handler' => function(Exception $e)
	{
		$contents = ob_get_contents();

		while(ob_get_level() > 0)
		{
			ob_end_clean();
		}

		ob_start();

		echo '<pre>'.print_r($e, true).'</pre>';

		exit(1);
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