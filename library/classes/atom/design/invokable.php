<?php

/**
 * Design namespace. This namespace is meant for abstract concepts and in most
 * cases simply just interfaces that in someway structures the general design
 * used in the core components.
 *
 * @package    Atom
 * @subpackage Library
 */
namespace Atom\Design;

/**
 * Interface for requiring the autoloader to invoke a class when first loaded.
 *
 * @package    Atom
 * @subpackage Library
 */
interface Invokable {

	/**
	 * Magic method called when creating a new instance of the object from the
	 * autoloader.
	 *
	 * @return   void             No value is returned
	 */
	public static function invoke();
}

/* End of file invokable.php */