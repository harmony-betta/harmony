<?php

date_default_timezone_set("Asia/Jakarta");
require_once 'bootstrap/app.php';
use Phpmig\Adapter;


$container['phpmig.adapter'] = function ($container) {
    return new Adapter\Illuminate\Database($container['db'], 'migrations');
};

$container['phpmig.migrations_path'] = function () {
    return dirname(__DIR__) . '/app/Database/migrations';
};

$container['phpmig.migrations_template_path'] = function($container){
    return $container['phpmig.migrations_path'] . DIRECTORY_SEPARATOR . '.template.php';
};

$container['schema'] = function () {
    return Capsule::schema();
};

return $container;