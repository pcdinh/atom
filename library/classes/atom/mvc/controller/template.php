<?php

/**
 * Controller extensions namespace. In here, all extensions are stored for
 * MV-Controllers.
 *
 * @package    Atom
 * @subpackage Library
 */
namespace Atom\MVC\Controller;

// Aliasing rules
use Atom\MVC\Controller;
use Atom\View;

/**
 * Template controller class.
 *
 * A base controller for easily creating templated output.
 *
 * @package    Atom
 * @subpackage Library
 */
abstract class Template extends Controller {

	/**
	 * The page template
	 *
	 * @var    string
	 */
	public $template = 'template';

	/**
	 * Whether to automatically render the template or not.
	 *
	 * @var    boolean
	 */
	public $auto_render = true;

	/**
	 * Load the template and create the $this->template object from the view.
	**/
	public function before()
	{
		if($this->auto_render === true)
		{
			$this->template = View::make($this->template);
		}

		return parent::before();
	}

	/**
	 * After controller method which outputs content to the body.
	 */
	public function after()
	{
		if($this->auto_render === true)
		{
			$this->response->body($this->template);
		}

		return parent::after();
	}
}

/* End of file template.php */