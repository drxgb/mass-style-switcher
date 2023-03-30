<?php

$config['db'] = [
	'host'			=> 'db',
	'port'			=> 3306,
	'user'			=> 'root',
	'password'		=> 'root',
	'schema'		=> 'mass_style_switcher',
	'user_table'	=> 'xf_user',
	'user_key'		=> 'user_id',
	'username'		=> 'username',
	'style_column'	=> 'style_id',
];
$config['app'] = [
	'name'		=> 'Mass Style Switcher',
	'version'	=> '1.0.0',
];
$config['storage'] = [
	'app_dir'	=> __DIR__ . '/storage/',
];
