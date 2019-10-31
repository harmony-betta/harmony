<?php

namespace App\Support\Commands;

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as DB;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle as OutputFormatterStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SeedersTruncate extends Command
{
    protected $commandArgumentName = "name";
    protected $commandArgumentDescription = "This command will truncate seed data for specific table"; 

    protected function configure()
    {
        $this->setName('seed:truncate')
            ->setDescription('Truncate specific seeds data')
            ->addArgument(
                $this->commandArgumentName,
                InputArgument::OPTIONAL,
                $this->commandArgumentDescription
            );

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {   
        $style = new OutputFormatterStyle('green');
        $output->getFormatter()->setStyle('fire', $style);

        $container = new Container;
        $db = $this->initEloquent($container);

        $name = $input->getArgument($this->commandArgumentName);

        // Seeders Directory
        $seedDirectory = dirname(dirname(__DIR__)). '/Database/Seeds/';

        if ($name) {
            
            if (file_exists($seedDirectory . $name . '.php')) {
                
                $seeder     = "App\Database\Seeds\\".$name;
                $table      = new $seeder();
                $run        = $table->run();
                $modelName  = ucfirst(str_replace('Seed', '', $run['table']));
                $model      = "App\Models\\". $modelName;
                $db->statement("SET foreign_key_checks=0");
                $model::truncate();
                $db->statement("SET foreign_key_checks=1");

            } else {
                echo "File " . $name . ' is not exists!' . PHP_EOL;
            }

            $output->writeln('<fire>Successfully truncate '. $name .' migration</fire>');
        } else {

            $files = array_values(array_diff(scandir($seedDirectory), array('.', '..')));
            for ($i=0; $i < count($files); $i++) { 
                $filename = str_replace('.php', '', $files[$i]);

                $seeder     = "App\Database\Seeds\\".$filename;
                $table      = new $seeder();
                $run        = $table->run();
                $modelName  = ucfirst(str_replace('Seed', '', $run['table']));
                $model      = "App\Models\\". $modelName;
                $db->statement("SET foreign_key_checks=0");
                $model::truncate();
                $db->statement("SET foreign_key_checks=1");
                $output->writeln('<fire>Successfully truncate '. $filename .' migration</fire>');
                sleep(2);
            }
        }
    }

    protected function initEloquent($container)
    {
        $capsule = new DB($container);
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