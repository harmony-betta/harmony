<?php

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

use Respect\Validation\Validator as v;
use Slim\App;

session_start();

require_once dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(dirname(__DIR__));
$dotenv->load();

$app = new App([
    "settings" => [
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
        "storage" => dirname(__DIR__).'/storage/public'
    ]
]);

require_once dirname(__DIR__) . '/bootstrap/container.php';
require_once dirname(__DIR__) . '/config/app.container.php';

require_once dirname(__DIR__) . '/bootstrap/middleware.php';

$app->add($container->csrf);
v::with('App\\Support\\Validation\\Rules\\');

require_once dirname(__DIR__) . '/routes/web.php';