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

/**
 * Default exception, mainly used for general errors. All Atom specific
 * exceptions extend this exception.
 *
 * @package    Atom
 * @subpackage Library
 */
class Exception extends \Exception {

	/**
	 * Standard exception, indicates that this is a GUI themed error (a non
	 * critical error)
	 *
	 * @param     string           The error message, in a printf-alike formatted string or just a normal string
	 * @param     mixed            Optional argument #n for formatting
	 * @return    void             No value is returned
	 */
	public function __construct()
	{
		if(!\func_num_args())
		{
			parent::__construct('Unknown error');
		}
		else
		{
			parent::__construct(\call_user_func_array('\sprintf', \func_get_args()));
		}
	}
}

/* End of file exception.php */