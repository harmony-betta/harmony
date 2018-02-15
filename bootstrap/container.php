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

/**
 * DANGER ZONE !!!
 */
$container = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        return $container['view']->render($response, 'error/404.twig', ['status' => 404, 'message' => 'The page you seek does not exist.']);
    };
};

$container['notAllowedHandler'] = function ($c) {
    return function ($request, $response, $methods) use ($c) {
        return $container['view']->render($response, 'error/404.twig', ['status' => 405, 'message' => 'The page you seek does not allowed for you.']);
    };
};

$container['db'] = function($container) use ($capsule) {
    return $capsule;
};

$container['mailer'] = function($container) {
    $mailer = new Nette\Mail\SmtpMailer($container['settings']['nate.email']);
    return $mailer;
};

$container['phpmig.adapter'] = function ($container) {
    return new Adapter\Illuminate\Database($container['db'], 'migrations');
};

$container['phpmig.migrations_path'] = function () {
    return dirname(__DIR__) . '/app/Database/migrations';
};

$container['schema'] = function () {
    return Capsule::schema();
};

$container['flash'] = function($container){
    return new \Slim\Flash\Messages;
};

$container['view'] = function($container) {
    $view = new \Slim\Views\Twig(dirname(__DIR__) . '/resources/views', [
            'debug' => true,
            'cache' => false,
    ]);

    $view->addExtension(new \Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));
    $view->addExtension(new \App\Support\Views\DebugExtension);

    require dirname(__DIR__) . '/app/Support/Helpers/bootstrap.php';

    return $view;
};

$conteiner['storage'] = function($conteiner) {
    return $conteiner['settings']['storage'];
};

$container['validator'] = function($container) {
    return new \App\Support\Validation\Validator;
};

$container['csrf'] = function($container){
    $guard = new Slim\Csrf\Guard();
    $guard->setFailureCallable(function ($request, $response, $next) {
        return $response->write(file_get_contents('../resources/views/error/csrf.twig'));
    });
    return $guard;
};