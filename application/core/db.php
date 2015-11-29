<?php

require_once(realpath(dirname(__FILE__) . "/../../resources/SecureConfig.php"));
use Illuminate\Database\Capsule\Manager as Capsule;
$capsule = new Capsule();
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => SecureConfig::$db['host'],
    'username' => SecureConfig::$db['user'],
    'password' => SecureConfig::$db['password'],
    'database' => SecureConfig::$db['schema'],
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => ''
]);

$capsule->bootEloquent();
?>
