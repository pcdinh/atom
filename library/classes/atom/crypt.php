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
 * @package    Atom
 * @subpackage Library
 */
class Crypt {

	/**
	 * @see      Crypt::__construct
	 */
	public static function make($cipher = 'rijndael-256', $mode = 'cbc')
	{
		return new static($cipher, $mode);
	}

	/**
	 * Magic Method for calling class methods statically, using default
	 * configurations
	 *
	 * @param    string
	 * @param    array
	 * @return   mixed
	 */
	public static function __callStatic($method, $parameters)
	{
		return static::make()->$method($parameters);
	}

	/**
	 * The encryption cipher.
	 *
	 * @var    string
	 */
	public $cipher;

	/**
	 * The encryption mode
	 *
	 * @var    string
	 */
	public $mode;

	/**
	 * Creates a new Crypt instance
	 *
	 * @param    string
	 * @return   void            No value is returned
	 */
	public function __construct($cipher = 'rijndael-256', $mode = 'cbc')
	{
		$this->cipher = $cipher;
		$this->mode = $mode;
	}

	/**
	 * Encrypt a value using the MCrypt library.
	 *
	 * @param    string
	 * @return   string
	 */
	public function encrypt($value)
	{
		$iv = mcrypt_create_iv($this->iv_size(), $this->randomizer());
		return base64_encode($iv.mcrypt_encrypt($this->cipher, $this->key(), $value, $this->mode, $iv));
	}

	/**
	 * Decrypt a value using the MCrypt library.
	 *
	 * @param    string
	 * @return   string
	 */
	public function decrypt($value)
	{
		if(!is_string($value = base64_decode($value, true)))
		{
			throw new \Exception('Decryption error. Input value is not valid base64 data.');
		}

		list($iv, $value) = array(substr($value, 0, $this->iv_size()), substr($value, $this->iv_size()));

		return rtrim(mcrypt_decrypt($this->cipher, $this->key(), $value, $this->mode, $iv), "\0");
	}

	/**
	 * Get the random number source available to the OS.
	 *
	 * @return   integer
	 */
	protected function randomizer()
	{
		if(defined('MCRYPT_DEV_URANDOM'))
		{
			return MCRYPT_DEV_URANDOM;
		}
		elseif(defined('MCRYPT_DEV_RANDOM'))
		{
			return MCRYPT_DEV_RANDOM;
		}

		return MCRYPT_RAND;
	}

	/**
	 * Get the application key from the application configuration file.
	 *
	 * @return   string
	 */
	private function key()
	{
		$key = Config::get('application.key');

		if(!empty($key))
		{
			return $key;
		}

		throw new Exception\Basic('The encryption class cannot be used without an encryption key.');
	}

	/**
	 * Get the input vector size for the cipher and mode.
	 *
	 * Different ciphers and modes use varying lengths of input vectors.
	 *
	 * @return   integer
	 */
	private function iv_size()
	{
		return mcrypt_get_iv_size($this->cipher, $this->mode);
	}
}

/* End of file crypt.php */