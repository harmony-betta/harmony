<?php

/** 
 * 
 * Website Route
 * 
 * Berikut adalah default route Harmony Framework
 * mohon untuk tidak mengubah default route dari Harmony Framework.
 * 
 * Silahkan definikan route buatan anda di baris paling bawah.
 * 
 */

use App\Middleware\System\AuthMiddleware;
use App\Middleware\System\AuthAdminMiddleware;
use App\Middleware\System\GuestMiddleware;

$app->get('/', 'HomeController:index')->setName('home');
$app->post('/', 'HomeController:upload');

$app->group('', function(){

    $this->get('/auth/signup', 'AuthController:getSignUp')->setName('auth.signup');
    $this->post('/auth/signup', 'AuthController:postSignUp');
    
    $this->get('/auth/signin', 'AuthController:getSignIn')->setName('auth.signin');
    $this->post('/auth/signin', 'AuthController:postSignIn');

    $this->get('/admin/signin', 'AuthAdminController:getSignIn')->setName('admin.signin'); 
    $this->post('/admin/signin', 'AuthAdminController:postSignIn');       

})->add(new GuestMiddleware($container));

$app->group('', function() {

    $this->get('/auth/password/change', 'PasswordController:getChangePassword')->setName('auth.password.change');
    $this->post('/auth/password/change', 'PasswordController:postChangePassword');

    $this->get('/auth/home', 'AuthController:index')->setName('auth.home');
    
    $this->get('/auth/signout', 'AuthController:getSignOut')->setName('auth.signout');

})->add(new AuthMiddleware($container));

$app->group('', function(){
    
    $this->get('/admin/signup', 'AuthAdminController:getSignUp')->setName('admin.signup');
    $this->post('/admin/signup', 'AuthAdminController:postSignUp');
        
    $this->get('/admin/signout', 'AuthAdminController:getSignOut')->setName('admin.signout');

    $this->get('/admin/home', 'AdminController:index')->setName('admin.home');

    $this->get('/admin/password/change', 'AdminPasswordController:getChangePassword')->setName('admin.password.change');
    $this->post('/admin/password/change', 'AdminPasswordController:postChangePassword');

    $this->get('/admin/email', 'EmailController:index')->setName('email');
    $this->post('/admin/email', 'EmailController:sendEmail');

})->add(new AuthAdminMiddleware($container));

$app->post('/ajax/post', function($request, $response) {

    // CSRF token name and value
    $nameKey = $this->csrf->getTokenNameKey();
    $valueKey = $this->csrf->getTokenValueKey();
    $name = $request->getAttribute($nameKey);
    $value = $request->getAttribute($valueKey);

    $name = $request->getParam('name');
    echo "Hello ". $name;

})->setName('ajax.post');