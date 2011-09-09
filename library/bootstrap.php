<?php

// Aliasing rules
use Atom\Config;
use Atom\Loader;

// Include functionality we cannot autoload
require_once CLASS_PATH.'atom/loader'.EXT;

// Bootstrap the auto-loader
Loader::bootstrap();

// Set the default timezone
date_default_timezone_set(Config::get('application.timezone'));

/* End of file bootstrap.php */