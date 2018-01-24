<?php

$app->group('', function(){

		/** 
		* Insert your logic route in here
		* $this->get('/name/route', 'YourController:method');
		*/
		$this->get('/auth/signin', 'AuthController:getSignIn')->setName('auth.signin');
    $this->post('/auth/signin', 'AuthController:postSignIn');

    $this->get('/auth/signup', 'AuthController:getSignUp')->setName('auth.signup');
    $this->post('/auth/signup', 'AuthController:postSignUp');

    $this->get('/auth/confirm', 'AuthController:userVerification')->setName('auth.confirm');

})->add(new GuestMiddleware($container));
    
$app->group('', function() {
		
		/** 
		* Insert your logic route in here
		* $this->get('/name/route', 'YourController:method');
		*/

		$this->get('/auth/password/change', 'PasswordController:getChangePassword')->setName('auth.password.change');
    $this->post('/auth/password/change', 'PasswordController:postChangePassword');

    $this->get('/auth/home', 'AuthController:index')->setName('auth.home');
    
    $this->get('/auth/signout', 'AuthController:getSignOut')->setName('auth.signout');

})->add(new AuthMiddleware($container));