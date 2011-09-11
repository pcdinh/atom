<?php

/**
 * Database configuration
 *
 * @var     array
 */
return array(

	/**
	 * Default Database Connection
	 *
	 * The name of your default database connection.
	 *
	 * This connection will be the default for all database operations unless a
	 * different connection is specified when performing the operation.
	 *
	 * @var     string
	 */
	'default' => 'application',

	/**
	 * Database Connections
	 *
	 * All of the database connections used by your application.
	 *
	 * Supported drivers: 'mysql', 'pgsql', 'sqlite'
	 *
	 * Note: When using the SQLite driver, the path and "sqlite" extension will
	 *       be automatic. You only need to specify the database name.
	 *
	 * Using a driver that isn't supported? You can still establish a PDO
	 * connection. Simply specific the driver and DSN option:
	 *
	 *     'odbc' => array(
	 *			'driver'   => 'odbc',
	 *			'dsn'      => 'your-dsn',
	 *			'username' => 'username',
	 *			'password' => 'password',
	 *		)
	 *
	 * Note: When using an unsupported driver, Eloquent and the fluent query
	 *       builder may not work as expected.
	 */
	'connections' => array(

		/**
		 * Development environment connections
		 *
		 * @var     array
		 */
		'development' => array(

			'application' => array(
				'driver'   => 'sqlite',
				'database' => 'database',
			),

			// An example of a mysql connection
			'application_mysql' => array(
				'driver'   => 'mysql',
				'host'     => 'localhost',
				'database' => 'database',
				'username' => 'root',
				'password' => '',
				'charset'  => 'utf8',
			),

			// An example of a pgsql connection
			'application_pgsql' => array(
				'driver'   => 'pgsql',
				'host'     => 'localhost',
				'database' => 'database',
				'username' => 'root',
				'password' => '',
				'charset'  => 'utf8',
			),

		),

	),

);

/* End of file db.php */