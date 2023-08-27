<?php

session_start();
date_default_timezone_set('Asia/Jakarta');
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

use DI\Container;
use Respect\Validation\Validator as v;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Twig\Extra\Intl\IntlExtension;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Symfony\Component\VarDumper\VarDumper;


require_once dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(dirname(__DIR__));
$dotenv->load();

global $settings, $container, $app, $twig;

$settings = [
    "displayErrorDetails" => env('DISPLAY_ERROR'),
    "debug" => env('DEBUG'),
    'whoops.editor' => env('EDITOR'),
    "db" => [
        "driver" => env('DB_DRIVER'),
        "host" => env('DB_HOST'),
        "database" => env('DB_NAME'),
        "username" => env('DB_USER'),
        "password" => env('DB_PASS'),
        "charset" => "utf8",
        "collation" => "utf8_unicode_ci",
        "prefix" => ""
    ],
    'nate.email' => [
        'host' => env('EMAIL_HOST'),
        'username' => env('EMAIL_USERNAME'),
        'password' => env('EMAIL_PASSWORD'),
        'port' => env('EMAIL_PORT'),
        'secure' => env('EMAIL_SECURE'),
    ],
    'app_url' => env('APP_HOST'),
    "storage" => replaceDirectorySeparator(dirname(__DIR__) . '/storage/public')
];

$view_path = replaceDirectorySeparator(dirname(__DIR__) . '/resources/views');

$container = new Container();

$container->set('settings', function() use ($settings) {
    return $settings;
});

$container->set('environment', function() {
    return $_SERVER;
});

require_once dirname(__DIR__) . '/bootstrap/container.php';
require_once dirname(__DIR__) . '/config/app.container.php';

AppFactory::setContainer($container);
$app = AppFactory::create();

VarDumper::setHandler(function ($var) {

    $cloner = new VarCloner;

    $htmlDumper = new HtmlDumper;
    $htmlDumper->setStyles([
        'default' => 'background-color:#18171B; color:#FF8400; line-height:1.2em; font:12px Menlo, Monaco, Consolas, monospace; word-wrap: break-word; white-space: pre-wrap; position:relative; z-index:99999; word-break: break-all',
        'num' => 'font-weight:bold; color:#1299DA',
        'const' => 'font-weight:bold',
        'str' => 'font-weight:bold; color:#6de89e',
        'note' => 'color:#1299DA',
        'ref' => 'color:#A0A0A0',
        'public' => 'color:#FFFFFF',
        'protected' => 'color:#FFFFFF',
        'private' => 'color:#FFFFFF',
        'meta' => 'color:#B729D9',
        'key' => 'color:#6de89e',
        'index' => 'color:#1299DA',
        'ellipsis' => 'color:#FF8400',
    ]);

    $dumper = PHP_SAPI === 'cli' ? new CliDumper : $htmlDumper;

    $dumper->dump($cloner->cloneVar($var));
});

v::with('App\\Support\\Validation\\Rules\\');

require_once dirname(__DIR__) . '/bootstrap/middleware.php';
$app->add('csrf');
require_once dirname(__DIR__) . '/routes/web.php';

$twig = Twig::create($view_path, [
    'debug' => true,
    'cache' => false
]);
$twig->addExtension(new IntlExtension());
$twig->addExtension(new \Twig\Extension\DebugExtension());

$public = new \Twig\TwigFunction('public', function($data){
	if (env('APP_HOST') !== 'http://localhost:8081') {
		$assets = sprintf(
		    "%s://%s",
		    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
		    $_SERVER['SERVER_NAME'] . '/assets/'.$data
		);
	} else {
		$assets = rtrim(env('APP_HOST'), '/') . '/assets/'.$data;
	}
	return $assets;
});

$twig->getEnvironment()->addFunction($public);
// require_once dirname(__DIR__) . '/app/Support/Helpers/bootstrap.php';
$app->add(TwigMiddleware::create($app, $twig));
