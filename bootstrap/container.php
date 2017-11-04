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
        return $container['view']->render($response, 'error/404.twig');
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

$container['auth'] = function($container){
    return new \App\Support\Auth\Auth;
};

$container['admin'] = function($container){
    return new \App\Support\Auth\AuthAdmin;
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

    return $view;
};

$conteiner['storage'] = function($conteiner) {
    return $conteiner['settings']['storage'];
};

$container['validator'] = function($container) {
    return new \App\Support\Validation\Validator;
};

$container['csrf'] = function($container){
    return new \Slim\Csrf\Guard;
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

$container['AuthAdminController'] = function($container){
    return new \App\Controllers\AuthAdmin\AuthAdminController($container);
};

$container['AdminPasswordController'] = function($container){
    return new \App\Controllers\AuthAdmin\AdminPasswordController($container);
};

/**
 * END DANGER ZONE !!!
 * 
 * Silahkan definisakn container buatan anda di bawah area ini.
 */

$container['EmailController'] = function($container) {
return new \App\Controllers\EmailController($container);
};