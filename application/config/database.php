<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

$active_group = 'default';
$query_builder = true;

$db['default'] = [
    'dsn' => '',
    'hostname' => '127.0.0.1',
    'username' => 'root',
    'password' => 'root',
    'database' => 'ci_base',
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
    'save_queries' => true,
];

$capsule = new Capsule();

$capsule->addConnection([
    'driver' => 'mysql',
    'host' => $db['default']['hostname'],
    'database' => $db['default']['database'],
    'username' => $db['default']['username'],
    'password' => $db['default']['password'],
    'charset' => $db['default']['char_set'],
    'collation' => $db['default']['dbcollat'],
    'prefix' => $db['default']['dbprefix'],
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();