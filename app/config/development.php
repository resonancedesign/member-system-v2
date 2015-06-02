<?php

return [
	'app' => [
		'url' => 'http://localhost',
		'hash' => [
			'algo' => PASSWORD_BCRYPT,
			'cost' => 10
		]
	],

	'db' => [
		'driver' => 'mysql',
		'host' => '127.0.0.1',
		'name' => 'resdes_membersys_v2',
		'username' => 'rezinunts',
		'password' => 'Mungching1!',
		'charset' => 'utf8',
		'collation' => 'utf8_unicode_ci',
		'prefix' => ''
	],

	'auth' => [
		'session' => 'user_id',
		'remember' => 'user_r'
	],

	'mail' => [
		'smtp_auth' => true,
		'smtp_secure' => 'tls',
		'host' => 'smtp.gmail.com',
		'username' => 'resonance.designs.com@gmail.com',
		'password' => 'Mungching1!',
		'port' => 587,
		'html' => true
	],

	'twig' => [
		'debug' => true,
	],

	'csrf' => [
		'session' => 'csrf_token'
	]
];