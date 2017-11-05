<?php

/**
 * END DANGER ZONE !!!
 * 
 * Silahkan definisakn container buatan anda di bawah area ini.
*/

$container['auth'] = function($container){
    return new \App\Support\Auth\Auth;
};

$container['admin'] = function($container){
    return new \App\Support\Auth\AuthAdmin;
};

$container['HomeController'] = function($container){
    return new \App\Controllers\HomeController($container);
};

$container['AdminController'] = function($container){
    return new \App\Controllers\AdminController($container);
};

$container['AuthController'] = function($container){
    return new \App\Controllers\Auth\AuthController($container);
};

$container['PasswordController'] = function($container){
    return new \App\Controllers\Auth\PasswordController($container);
};

$container['AuthAdminController'] = function($container){
    return new \App\Controllers\AuthAdmin\AuthAdminController($container);
};

$container['AdminPasswordController'] = function($container){
    return new \App\Controllers\AuthAdmin\AdminPasswordController($container);
};

$container['EmailController'] = function($container) {
    return new \App\Controllers\EmailController($container);
};

