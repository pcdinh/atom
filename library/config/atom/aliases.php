<?php

/**
 * Class Aliases
 *
 * Here, you can specify any class aliases that you would like registered when
 * Atom loads. Aliases are lazy-loaded, so add as many as you want.
 *
 * Aliases make it more convenient to use namespaced classes, instead of
 * referring to the class using its full namespace, you may simply use the
 * alias defined here.
 *
 * We have already aliases common Atom classes to make your life easier.
 *
 * @var    array
 */
return array(
	'Arr'        => 'Atom\\Arr',
	'Asset'      => 'Atom\\Asset',
	'Benchmark'  => 'Atom\\Benchmark',
	'Cache'      => 'Atom\\Cache',
	'Controller' => 'Atom\\MVC\\Controller',
	'Config'     => 'Atom\\Config',
	'DB'         => 'Atom\\DB',
	'Eloquent'   => 'Atom\\DB\\Eloquent\\Model',
	'Exception'  => 'Atom\\Exception',
	'Http'       => 'Atom\\Http',
	'Inflector'  => 'Atom\\Inflector',
	'Invokable'  => 'Atom\\Design\\Invokable',
	'Loader'     => 'Atom\\Loader',
	'Renderable' => 'Atom\\Design\\Renderable',
	'Str'        => 'Atom\\Str',
	'Url'        => 'Atom\\Url',
	'Version'    => 'Atom\\Version',
	'View'       => 'Atom\\View',
);

/* End of file aliases.php */