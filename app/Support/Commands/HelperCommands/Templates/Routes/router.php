<?php

/**
 * Auth Group First Block
 *
 * 
 * This is route only avalible for Guest to :
 * - Login
 * - SignUp
 * - Forgot Password -> Reset Password
 *
 * ==== Using GuestMiddleware to Protect Route ====
 */
$app->group('/auth', function(){

	$this->get('/signin', 'AuthController:getSignIn')->setName('auth.signin');
	$this->post('/signin', 'AuthController:postSignIn');

	$this->get('/signup', 'AuthController:getSignUp')->setName('auth.signup');
	$this->post('/signup', 'AuthController:postSignUp');

	$this->get('/forgot-password', 'PasswordController:forgotPassword')->setName('auth.forgot.password');
	$this->post('/forgot-password', 'PasswordController:postForgotPassword');

	$this->get('/reset-password', 'PasswordController:resetPassword')->setName('auth.reset.password');
	$this->post('/reset-password', 'PasswordController:postResetPassword');

})->add(new GuestMiddleware($container));

/**
 * Auth Group Second Block
 *
 * 
 * This is route only avalible for User is Authenticated to :
 * - After Login [display] Home Page / Dashboard
 * - Change Password
 * - Sign Out also
 *
 * ==== Using AuthMiddleware to Protect Route ====
 */
$app->group('/auth', function() {

	$this->get('/password/change', 'PasswordController:getChangePassword')->setName('auth.password.change');
	$this->post('/password/change', 'PasswordController:postChangePassword');

	$this->get('/home', 'AuthController:index')->setName('auth.home');

	$this->get('/signout', 'AuthController:getSignOut')->setName('auth.signout');

})->add(new AuthMiddleware($container));