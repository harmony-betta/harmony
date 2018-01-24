<?php

namespace App\Support\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

class Tinker extends Command
{
    protected function configure()
    {
        $this->setName('tinker')
            ->setDescription('Outputs all routes in your application');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {   
    		$container = new Container;
				$dispatcher = new Dispatcher;
				$container['events'] = $dispatcher;
				$db = $this->initEloquent($container);
				// begin init SQL logger
				$dispatcher->listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
				  $msg =  "== SQL: ".$query->sql."\n";
				  $msg .= "== Params: ".join(', ', $query->bindings);
				  $msg .= "\n\n";
				  // if code is executed in CLI, echo message
				  if (php_sapi_name() == 'cli') {
				      echo $msg;
				  }
				  // if code executed by server, log message so stderr
				  else {
				      $msg = "[".date("Y-m-d H:i:s")."]\n" . $msg;
				      file_put_contents(__DIR__.'/db.log', $msg, FILE_APPEND); // log into file
				      error_log($msg); // log into stderr. usable in php builtin server
				  }
				});

        $configFile = dirname(dirname(dirname(__DIR__))).'/bin/psysh.config.php';
				$sh = new \Psy\Shell(new \Psy\Configuration(['configFile' => $configFile]));
				$sh->setScopeVariables(get_defined_vars());
				$sh->run();
    }

    protected function initEloquent($container)
    {
        $capsule = new Capsule($container);
        $capsule->addConnection([
            'driver' => env('DB_DRIVER'),
            'host' => env('DB_HOST'),
            'database' => env('DB_NAME'),
            'username' => env('DB_USER'),
            'password' => env('DB_PASS'),
            'charset' => 'utf8',
            'collation' => 'utf8_general_ci',
            'prefix' => ''
        ]);
        // $capsule->setAsGlobal();
        $capsule->bootEloquent();
        date_default_timezone_set('UTC');
        $db = $capsule->getConnection();
        return $db;
    }
}