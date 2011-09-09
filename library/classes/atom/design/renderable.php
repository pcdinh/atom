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
 * Interface for all classes which are renderable
 *
 * @author      Chase Hutchins     <syntaqx@gmail.com>
 * @package     Atom
 * @subpackage  Library
 */
interface Renderable {

	/**
	 * Get the evaluate string content of the view.
	 *
	 * @return     string
	 */
	public function render();
}

/* End of file renderable.php */