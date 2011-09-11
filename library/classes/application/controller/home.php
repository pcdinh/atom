<?php

/**
 * Application controller namespace. In here, all of your primary application
 * controller logic takes place.
 *
 * @package    Application
 * @subpackage Library
 */
namespace Application\Controller;

/**
 * Home controller
 *
 * A basic example of a controller.
 *
 * @package    Application
 * @subpackage Library
 */
class Home extends \Controller {

	/**
	 * Index action
	 *
	 * @return   void             No value is returned
	 */
	public function action_index()
	{
		$this->response->body = \View::make('template')->partial('content', 'home/index');
	}
}

/* End of file home.php */