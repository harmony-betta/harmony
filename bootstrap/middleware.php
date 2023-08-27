<?php
/**************************************************************
 *                  DON'T REMOVE THIS LINE !!!                *
 * ************************************************************
 * 
 * 
 * Ini adalah middleware default yang digunakan oleh sistem
 * Silahkan anda buat middleware buatan anda di baris paling bawah
 * 
 */
$app->add(new \App\Middleware\System\ValidationErrorsMiddleware($container));
$app->add(new \App\Middleware\System\OldInputMiddleware($container));
$app->add(new \App\Middleware\System\CsrfViewMiddleware($container));
$app->add(new \App\Middleware\System\FlashMiddleware($container));
$app->add(new \App\Middleware\System\HelperMiddleware($container));