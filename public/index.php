<?php

/**
 * Atom - Elegant & Awesome, Simple & Powerful
 *
 * @version    1.0.0 Alpha 1 (Experimental)
 * @link       http://atomphp.com
 */

// Cache the starting time and memory usage for future benchmarking
define('ATOM_START_TIME', microtime(true));
define('ATOM_START_MEM', memory_get_usage());

/**
 * The file extension which all includes utilize
 *
 * @var    string
 */
define('EXT', '.php');

/**
 * Sets the path to where the root script is, if the constant CWD is defined
 * before including this file, then it will be used as the root directory.
 *
 * @var    string
 */
define('DOCROOT', (defined('CWD') ? CWD : __DIR__.'/'));

/**
 * The path to the library folder. This path is the containing folder for all of
 * the components found within atom.
 *
 * @var    string
 */
define('LIBRARY_PATH', realpath(DOCROOT.'../library').'/');

// Core framework pathing
define('CLASS_PATH',   LIBRARY_PATH.'classes/');
define('CONFIG_PATH',  LIBRARY_PATH.'config/');
define('LANG_PATH',    LIBRARY_PATH.'lang/');
define('STORAGE_PATH', LIBRARY_PATH.'storage/');
define('VENDOR_PATH',  LIBRARY_PATH.'vendors/');
define('VIEW_PATH',    LIBRARY_PATH.'views/');

// Core dependant pathing
define('CACHE_PATH',   STORAGE_PATH.'cache/');
define('DB_PATH',      STORAGE_PATH.'db/');
define('SESSION_PATH', STORAGE_PATH.'sessions/');
define('TMP_PATH',     STORAGE_PATH.'tmp/');

// Execute the bootstrapper
require LIBRARY_PATH.'bootstrap'.EXT;

// Dispatch routing
$response = Atom\Atom::dispatch();

// Give a bit of love to the Atom dev team?
// Remove this if you don't feel the need to acknowledge all the effort we've
// put into providing you the best framework we can.
$response->set_header('X-Powered-By', 'ATOM/'.Version::FULL.' PHP/'.PHP_VERSION);

// Send the response
$response->send();

// Ensure everything flushed to the browser
ob_end_flush();

/* End of file index.php */