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
 * Benchmark class.
 *
 * This small and lightweight benchmarking class allows you to easily identify
 * any function or class which is taking a long time to load so that you can
 * concentrate your efforts on resolving issues faster.
 *
 * @package    Atom
 * @subpackage Library
 */
class Benchmark implements Design\Invokable {

	/**
	 * All of the benchmarked timer points.
	 *
	 * @var    array
	 */
	protected static $marks = array();

	/**
	 * Magic method called when creating a new instance of the object from the
	 * autoloader.
	 *
	 * @return   void             No value is returned
	 */
	public static function invoke()
	{
		static::$marks['atom_start_time'] = ATOM_START_TIME;
	}

	/**
	 * Mark a benchmarking point
	 *
	 * After starting a benchmark point, the elapsed time may be retrieved by
	 * using the "check" method.
	 *
	 * @param    string           The mark name
	 * @return   void             No value is returned
	 */
	public static function mark($name)
	{
		static::$marks[$name] = microtime(true);
	}

	/**
	 * Get the elapsed time, in milliseconds, since marking a benchmark
	 *
	 * @param    string           The name of the mark
	 * @param    integer          The decimal precision to return
	 * @return   float            The amount of time, in seconds, that has elapsed since the mark
	 */
	public static function check($name, $decimal = 4)
	{
		if(array_key_exists($name, static::$marks))
		{
			return round(microtime(true) - static::$marks[$name], $decimal);
		}

		return (float) 0.0;
	}

	/**
	 * Get the total memory usage
	 *
	 * @param    boolean          Whether the format the byte usage into a string
	 * @param    integer          The decimal precision to return
	 * @return   string|integer   The current memory usage, in megabytes
	 */
	public static function memory($formatted = true, $decimal = 4)
	{
		return round((memory_get_usage() / 1048576), $decimal).($formatted ? ' mb' : '');
	}
}

/* End of file benchmark.php */