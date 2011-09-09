<?php

/**
 * Atom Exception namespace. This contains all of the core exceptions used
 * throughout the library.
 *
 * @package    Atom
 * @subpackage Library
 */
namespace Atom\Exception;

/**
 * Basic exception type, this is used for errors that should act as fatal
 * errors. If an exception of this type is caught by the default exception
 * handler it will terminate script execution.
 *
 * @package    Atom
 * @subpackage Library
 */
class Basic extends \Atom\Exception {

}

/* End of file basic.php */