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
 * @package    Application
 * @subpackage Library
 */
class Home extends \Controller {

	/**
	 * 
	 */
	public function action_index()
	{
		$this->response->body = \View::make('template')->partial('content', 'home/index');
	}

	/**
	 * 
	 */
	public function action_test()
	{
	}
}

/* End of file home.php */