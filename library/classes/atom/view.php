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
 * View class, this class serves as an object oriented way of handling display
 * data, mainly designed for use with MVC-like applications.
 *
 * @package    Atom
 * @subpackage Library
 */
class View implements Design\Renderable {

	/**
	 * Cached configuration variables
	 *
	 * @var     array
	 */
	protected static $config;

	/**
	 * Create a new view instance
	 *
	 * @see       $this->__construct()
	 */
	public static function make($view, $data = array(), $path = VIEW_PATH)
	{
		return new static($view, $data, $path);
	}

	/**
	 * The name of the view
	 *
	 * @var     string
	 */
	public $view;

	/**
	 * The view data
	 *
	 * @var     array
	 */
	public $data = array();

	/**
	 * The view name with dots replace by slashes
	 *
	 * @var     string
	 */
	protected $path;

	/**
	 * Create a new view instance
	 *
	 * @param     string
	 * @param     array
	 * @param     string
	 * @return    View
	 */
	public function __construct($view, array $data = array(), $path = VIEW_PATH)
	{
		$this->view   = $view;
		$this->data   = $data;
		$this->path   = $path.str_replace('.', '/', $view).EXT;

		if(!file_exists($this->path))
		{
			throw new \Exception('View ['.$this->path.'] does not exist');
		}
	}

	/**
	 * Magic method for getting items from the view data
	 *
	 * @param     string
	 * @return    mixed
	 */
	public function __get($key)
	{
		return $this->data[$key];
	}

	/**
	 * Magic method for setting items in the view data
	 *
	 * @param     string
	 * @param     mixed
	 * @return    void
	 */
	public function __set($key, $value)
	{
		$this->with($key, $value);
	}

	/**
	 * Magic method for determining if an item is in the view data
	 *
	 * @param     string
	 * @return    boolean
	 */
	public function __isset($key)
	{
		return array_key_exists($key, $this->data);
	}

	/**
	 * Magic method for removing an item from the view data.
	 *
	 * @param     string
	 * @return    void
	 */
	public function __unset($key)
	{
		unset($this->data[$key]);
	}

	/**
	 * Get the evaluate string content of the view.
	 *
	 * @see      $this->render();
	 */
	public function __toString()
	{
		return $this->render();
	}

	/**
	 * Add a view instance to the view data.
	 *
	 *     // Bind the view "partial/login" to the view
	 *     View::factory('home')->partial('login', 'partial/login');
	 *
	 *     // Equivalent binding using the "with" method
	 *     View::factory('home')->with('login', View::factory('partials/login'));
	 *
	 * @param     string
	 * @param     string
	 * @param     array
	 * @return    View
	 */
	public function partial($key, $view, $data = array(), $path = VIEW_PATH)
	{
		return $this->with($key, new static($view, $data, $path));
	}

	/**
	 * Add a key/value pair to the view data.
	 *
	 * Bound data will be available to the view as variables.
	 *
	 *     // Bind a "name" value to the view
	 *     View::factory('home')->with('name', 'Fred');
	 *
	 *     // Bind multiple values at once.
	 *     View::factory('home')->with(array(
	 *         'first_name' => 'Fred',
	 *         'last_name'  => 'Harrison',
	 *     ));
	 *
	 * @param     string|array
	 * @param     mixed|null
	 * @return    View
	 */
	public function with($keys, $value = null)
	{
		if ( ! is_array($keys))
		{
			$this->data[$keys] = $value;
		}
		else
		{
			foreach ($keys as $key => $data)
			{
				$this->data[$key] = $data;
			}
		}
		return $this;
	}

	/**
	 * Get the evaluate string content of the view.
	 *
	 * @param     string           The parser to use on the views content, if any
	 * @return    string           The rendered view contents
	 */
	public function render()
	{
		foreach($this->data as &$data)
		{
			if($data instanceof Design\Renderable)
			{
				$data = $data->render();
			}
		}

		ob_start() and extract($this->data, EXTR_SKIP);

		try
		{
			include $this->path;
		}
		catch(\Exception $e)
		{
			throw new Exception\Basic($e);
		}

		return ob_get_clean();
	}
}

/* End of file view.php */