<?php
defined('BASEPATH') or exit('No direct script access allowed');



$active_group = 'casabela';
$query_builder = true;
$db['agrotec'] = [
    'dsn'   => '',
    'hostname' => 'localhost',
    'username' => 'wltoposc_root',
    'password' => 'Orange!@#',
    'database' => 'wltoposc_agrotec',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => false,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => false,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => false,
    'compress' => false,
    'stricton' => false,
    'failover' => [],
    'save_queries' => true
];

$db['casabela'] = [
    'dsn'   => '',
    'hostname' => 'localhost',
    'username' => 'wltoposc_root',
    'password' => 'Orange!@#',
    'database' => 'wltoposc_casabela',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => false,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => false,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => false,
    'compress' => false,
    'stricton' => false,
    'failover' => [],
    'save_queries' => true
];
