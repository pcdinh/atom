<?php

/**
 * MVC (Model-View-Controller) namespace, this contains all the base
 * implementations of each of the building bricks and extensions for extending
 * them even further.
 *
 * @package    Atom
 * @subpackage Library
 */
namespace Atom\MVC;

/**
 * The base controller class for MVC components
 *
 * @package    Atom
 * @subpackage Library
 */
abstract class Controller {

	/**
	 * The current response object
	 *
	 * @var    Response
	 */
	public $response;

	/**
	 * Sets the controller request object.
	 *
	 * @param    Response         The current response object
	 * @return   Controller       The current instance of the object
	 */
	public function __construct(\Atom\Http\Response $response)
	{
		$this->response = $response;
	}

	/**
	 * This method gets called before the action is called
	 */
	public function before() {}

	/**
	 * This method gets called after the action is called
	 */
	public function after() {}
}

/* End of file controller.php */

