<?php

return [
  'app' => [
    'url' => '',
    'hash' => [
      'algo' => PASSWORD_BCRYPT,
      'cost' => '10'
    ]
  ],

  'db' => [
    'driver'    => 'mysql',
    'host'      => '127.0.0.1',
    'database'  => '',
    'username'  => '',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
  ],

  'auth' => [
    'session' => 'user_id',
    'remember' => 'user_r'
  ],

  'email' => [
    'smtp_auth' => true,
    'smtp_secure' => 'tls',
    'host' => 'smtp.gmail.com',
    'username' => '',
    'password' => '',
    'port' => 587,
    'html' => true
  ],

  'twig' => [
    'debug' => true
  ],

  'csrf' => [
    'session' => 'csrf_token'
  ]

];
