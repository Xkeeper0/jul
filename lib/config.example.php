<?php

/**
 * Forum configuration. Values entered here will override those from defaults.php.
 */

$sql_settings = array(
	// MySQL connection options.
	'host' => 'localhost',
	'user' => '',
	'pass' => '',
	'name' => '', // Database name
	// Table prefix: use this to run multiple installations on the same database.
	'prefix' => 'jul_',
);

$forum_settings = array(
	// Board settings.
	'board_name' => 'Board Name',
	'board_title' => 'Board Title',
	'site_url' => 'http://example.com',
	'site_name' => 'Site Name',
);
