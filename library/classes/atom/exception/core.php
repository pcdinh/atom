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
 * Core exceptions indicates a critical fault in the library, these are
 * primarily used during development processes and will not appear in production
 * ready environments.
 *
 * @package    Atom
 * @subpackage Library
 */
class Core extends \Atom\Exception\Basic {
}

/* End of file core.php */