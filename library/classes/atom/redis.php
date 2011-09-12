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
namespace Atom\Cache;

/**
 * This code is based on Resident, a Redis interface for the modest among us.
 *
 * It has been modifed to work with Atom and to improve code slightly.
 *
 * @author     Justin Poliey          <jdb34@njit.edu>
 * @copyright  2009 Justing Poliey    <jdb34@njit.edu>
 * @license    The MIT License        <http://www.opensource.org/licenses/mit-license.php>
 */
class Redis {

	/**
	 * The established redis instances
	 *
	 * @var    array
	 */
	protected static $instances = array();

	/**
	 * Get a redis instance. If no redis name is specified, the default
	 * instance will be returned as defined in the redis configuration file.
	 *
	 * @param    string           The redis name, from the redis config file.
	 * @return   Redis            The current, or a newly instanciated instance of Redis
	 */
	public static function instance($name = 'default')
	{
		if(isset(static::$instances[$name]))
		{
			return static::$instances[$name];
		}

		if(empty(static::$instances))
		{
			$config = Config::get('redis.'.$name);
		}

		if(!$config)
		{
			throw new Exception\Redis('Invalid instance name given.');
		}

		static::$instances[$name] = new static($config);

		return static::$instances[$name];
	}

	/**
	 * Whether the current connection is active or not
	 *
	 * @var    boolean
	 */
	protected $connection = false;

	/**
	 * Create a new redis instance.
	 *
	 * @param    array            The redis configuration array
	 * @return   Redis            A new instance of Redis
	 */
	public function  __construct(array $config = array())
	{
		if(!($this->connection = @fsockopen($config['hostname'], $config['port'], $errno, $errstr)))
		{
			throw new Exception\Redis('[%d] %s', $errno, $errstr);
		}
	}

	/**
	 * Simple closes the active redis instance
	 *
	 * @return   void             No value is returned
	 */
	public function  __destruct()
	{
		@fclose($this->connection);
	}

	/**
	 * Magic Method for handling dynamic method calls.
	 */
	public function __call($name, $args)
	{
		$response = null;

		$name = strtoupper($name);

		$command = '*'.(count($args) + 1).CRLF;
		$command .= '$'.strlen($name).CRLF;
		$command .= $name.CRLF;

		foreach ($args as $arg)
		{
			$command .= '$'.strlen($arg).CRLF;
			$command .= $arg.CRLF;
		}

		fwrite($this->connection, $command);

		$reply = trim(fgets($this->connection, 512));

		switch(substr($reply, 0, 1))
		{
			// Error
			case '-':
			{
				throw new Exception\Redis(substr(trim($reply), 4));
			}
			break;
			// In-line reply
			case '+':
			{
				$response = substr(trim($reply), 1);
			}
			break;
			// Bulk reply
			case '$':
			{
				if($reply == '$-1')
				{
					$response = null;
					break;
				}

				$read = 0;
				$size = substr($reply, 1);

				do
				{
					$block_size = ($size - $read) > 1024 ? 1024 : ($size - $read);
					$response .= fread($this->connection, $block_size);
					$read += $block_size;
				}
				while($read < $size);

				fread($this->connection, 2);
			}
			break;
			// Mult-Bulk reply
			case '*':
			{
				$count = substr($reply, 1);

				if($count == '-1')
				{
					return null;
				}

				$response = array();

				for ($i = 0; $i < $count; $i++)
				{
					$bulk_head = trim(fgets($this->connection, 512));
					$size = substr($bulk_head, 1);

					if($size == '-1')
					{
						$response[] = null;
					}
					else
					{
						$read = 0;
						$block = '';

						do
						{
							$block_size = ($size - $read) > 1024 ? 1024 : ($size - $read);
							$block .= fread($this->connection, $block_size);
							$read += $block_size;
						}
						while($read < $size);

						fread($this->connection, 2); /* discard crlf */
						$response[] = $block;
					}
				}
			}
			break;
			// Integer Reply
			case ':':
			{
				$response = substr(trim($reply), 1);
			}
			break;
			// Don't know what to do?  Throw it outta here
			default:
			{
				throw new Exception\Redis('Invalid server response: %s', $reply);
			}
			break;
		}

		return $response;
	}
}

/* End of file redis.php */