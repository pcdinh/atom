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
 * String helpers with encoding support. PHP needs to be compiled with the
 * --enable-mbstring option or a fallback without encoding support will be used.
 *
 * @package    Atom
 * @subpackage Library
 */
class Str {

	/**
	 * Convert a string to lowercase.
	 *
	 * @param    string           The value to convert
	 * @return   string           The converted value
	 */
	public static function lower($value)
	{
		if(function_exists('mb_strtolower'))
		{
			return mb_strtolower($value, static::encoding());
		}

		return strtolower($value);
	}

	/**
	 * Convert a string to uppercase
	 *
	 * @param    string           The value to convert
	 * @return   string           The converted value
	 */
	public static function upper($value)
	{
		if(function_exists('mb_strtoupper'))
		{
			return mb_strtoupper($value, static::encoding());
		}

		return strtoupper($value);
	}

	/**
	 * Convert a string to title case (ucwords).
	 *
	 * @param    string           The value to convert
	 * @return   string           The converted value
	 */
	public static function title($value)
	{
		if(function_exists('mb_convert_case'))
		{
			return mb_convert_case($value, MB_CASE_TITLE, static::encoding());
		}

		return ucwords(strtolower($value));
	}

	/**
	 * Get the length of a string
	 *
	 * @param    string           The value to determine from
	 * @return   string           The length of the value
	 */
	public static function length($value)
	{
		if(function_exists('mb_strlen'))
		{
			return mb_strlen($value, static::encoding());
		}

		return strlen($value);
	}

	/**
	 * Convert a string to 7-bit ASCII
	 *
	 * @param    string           The value to convert
	 * @return   string           The converted value
	 */
	public static function ascii($value)
	{
		$foreign = IoC::container()->resolve('laravel.config')->get('ascii');

		$value = preg_replace(array_keys($foreign), array_values($foreign), $value);

		return preg_replace('/[^\x09\x0A\x0D\x20-\x7E]/', '', $value);
	}

	/**
	 * Generate a random alpha or alpha-numeric string.
	 *
	 * @param    integer          The length of the random string
	 * @param    string           The character pool to use, from Str::pool
	 * @return   string           Returns the generated random characters
	 */
	public static function random($length = 16, $type = 'alnum')
	{
		$value = '';

		$pool_length = strlen($pool = static::pool($type)) - 1;

		for ($i = 0; $i < $length; $i++)
		{
			$value .= $pool[mt_rand(0, $pool_length)];
		}

		return $value;
	}

	/**
	 * Get a chracter pool.
	 *
	 * @param    string           The type of pool to generate, defaults to 'alnum'
	 * @return   string           The pool characters
	 */
	public static function pool($type = 'alnum')
	{
		switch(static::lower($type))
		{
			case 'alnum':
			case 'alphanum':
			{
				return '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			}
			break;
			case 'num':
			case 'numeric':
			{
				return '1234567890';
			}
			break;
			case 'nozero':
			{
				return '123456789';
			}
			break;
			case 'sym':
			case 'symb':
			case 'symbol':
			{
				return '~`!@#$%^&*()_-+=/?><,.:;"\'[]{}|\\';
			}
			break;
			case 'alnumsym':
			case 'alnumsymb':
			case 'alnumsymbol':
			{
				return '~`!@#$%^&*()_-+=/?><,.:;"\'[]{}|\\0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			}
			break;
			case 'distinct':
			{
				return '2345679ACDEFHJKLMNPRSTUVWXYZ';
			}
			break;
			case 'hexdec':
			case 'hexadecimal':
			{
				return '0123456789abcdef';
			}
			break;
			case 'alpha':
			default:
			{
				return 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			}
			break;
		}
	}

	/**
	 * Creates a random string of characters
	 *
	 * @param    string           The type of string to generate, defaults to alnumsym
	 * @param    integer          The number of characters
	 * @return   string           The random string
	 */
	public static function random($type = 'alnumsym', $length = 16)
	{
		$pool = static::pool($type);
		$str  = '';

		for($i = 0; $i < $length; ++$i)
		{
			$str .= substr($pool, mt_rand(0, strlen($pool)-1), 1);
		}

		return $str;
	}
}

/* End of file str.php */