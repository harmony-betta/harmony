<?php

/** 
 * -----------------------------------------------------
 *      SELAMAT DATANG DI HARMONY FRAMWORK             |
 * -----------------------------------------------------
 * 
 * @author Imam Ali Mustofa <bettadevindonesia@gmail.com>
 * @license MIT
 * @category Framework
 * @version 1.0.0
 * 
 * Framework ini dibangun dengan Slim Framework versi 3
 * dibuat untuk mempermudah pekerjaan sebagai web developer. 
 * 
 */

use Phpmig\Adapter\Illuminate\Database;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Twig\Loader\FilesystemLoader;

/**
 * DANGER ZONE !!!
 */
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($settings['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container->set('notFoundHandler', function ($container) {
    return function ($request, $response) use ($container) {
        return $container->get('view')->render($response, 'error/404.twig', ['status' => 404, 'message' => 'The page you seek does not exist.']);
    };
});

$container->set('notAllowedHandler', function ($c) {
    return function ($request, $response, $methods) use ($c) {
        return $c->get('view')->render($response, 'error/404.twig', ['status' => 405, 'message' => 'The page you seek does not allowed for you.']);
    };
});

$container->set('db', function () use ($capsule) {
    return $capsule;
});

$container->set('mailer', function () use ($settings) {
    $mailer = new Nette\Mail\SmtpMailer($settings['nate.email']);
    return $mailer;
});

$container->set('phpmig.adapter', function ($container) {
    return new Database($container['db'], 'migrations');
});

$container->set('phpmig.migrations_path', function () {
    return dirname(__DIR__) . '/app/Database/migrations';
});

$container->set('schema', function () use ($capsule) {
    return $capsule->schema();
});

$container->set('flash', function ($container) {
    return new \Slim\Flash\Messages;
});

$container->set('request', function() {
    return \Slim\Psr7\Factory\ServerRequestFactory::createFromGlobals();
});

$container->set('storage', function () use ($settings) {
    return $settings['storage'];
});

$container->set('validator', function () {
    return new \App\Support\Validation\Validator;
});

$container->set('responseFactory', function () {
    return new \Slim\Psr7\Factory\ResponseFactory();
});

$container->set('csrf', function ($container) {
    $responseFactory = $container->get('responseFactory');
    return new \Slim\Csrf\Guard($responseFactory);
});
