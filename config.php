<?php
$config = [
	'local' => _LOCAL_,
	'db' => [
		'driver' => 'pdo_mysql',
		'host' => 'localhost:3306',
		'dbname' => 'teste_coderock',
		'user' => 'teste_coderock',
		'password' => 'teste_coderock',
		'charset'  => 'utf8',
        'driverOptions' => array(
            1002 => 'SET NAMES utf8'
        )
	],
	'settings' => [
        'displayErrorDetails' => true
  ],
];

if(!_LOCAL_) {
    $config['db'] = [
        'driver' => 'pdo_mysql',
        'host' => '',
        'dbname' => '',
        'user' => '',
        'password' => '',
        'charset'  => 'utf8',
        'driverOptions' => array(
            1002 => 'SET NAMES utf8'
        )
    ];
}