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

class Seeders extends Command
{
    protected $commandArgumentName = "name";
    protected $commandArgumentDescription = "This command will create seed data for specific table, if you leave it empty: will run all seed files";

    protected function configure()
    {
        $this->setName('seed:migrate')
            ->setDescription('Migrate specific seeds data')
            ->addArgument(
                $this->commandArgumentName,
                InputArgument::OPTIONAL,
                $this->commandArgumentDescription
            );

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {   
        $container = new Container;
        $db = $this->initEloquent($container);

        $name = $input->getArgument($this->commandArgumentName);

        // Seeders Directory
        $seedDirectory = dirname(dirname(__DIR__)). '/Database/Seeds/';
        // If seed given name
        if ($name) {
            // check if file is exists in Seeds folder
            if (file_exists($seedDirectory . $name . '.php')) {
                // Catch Seeders Namespace
                $seeder     = "App\Database\Seeds\\".$name;
                // Instanciate seeder
                $table      = new $seeder();
                // Get array data like table and fields
                $run        = $table->run();
                // Catch Model Namespace
                $modelName  = ucfirst(str_replace('Seed', '', $run['table']));
                // Instanciate model name
                $model      = "App\Models\\". $modelName;
                // Get all fields
                $fields     = $run['fields'];
                // Count fields
                $size       = count($fields);

                if ($size > 1) { // if fields greater than 1

                    for ($i=0; $i < $size; $i++) { // loop through all fields
                        // Model create data
                        $model::create($fields[$i]);
                        sleep(1);
                    }

                } else { // If field just One

                    // Model create data
                    $model::create($fields[0]);

                }

                $style = new OutputFormatterStyle('green');
                $output->getFormatter()->setStyle('fire', $style);
                $output->writeln('<fire>Successfully run '. $name .' migration</fire>');

            } else {
                $style = new OutputFormatterStyle('red');
                $output->getFormatter()->setStyle('danger', $style);
                $output->writeln('<danger>File ' . $name . ' is not exists!</danger>');
            }

        } else { // and else don;t have name

            // list all file inside directory seedes and ingnore . and ..
            $files = array_values(array_diff(scandir($seedDirectory), array('.', '..')));
            // Loop through filename
            for ($i=0; $i < count($files); $i++) { 

                // Replace .php
                $filename = str_replace('.php', '', $files[$i]);
                // Check if file exist on seed directory
                if (file_exists($seedDirectory . $filename . '.php')) {
                    // Catch seed data namespace
                    $seeder     = "App\Database\Seeds\\".$filename;
                    // Instanciated seed
                    $table      = new $seeder();
                    // Returning results
                    $run        = $table->run();
                    // replace Seed name from filename
                    $modelName  = ucfirst(str_replace('Seed', '', $run['table']));
                    // Instanciate model name
                    $model      = "App\Models\\". $modelName;
                    // Returning all field defining on seed
                    $fields     = $run['fields'];
                    // Count all fields
                    $size       = count($fields);

                    if ($size > 1) { // Check if size greater than 1

                        for ($j=0; $j < $size; $j++) { // Loop through data
                            // create data by model
                            $model::create($fields[$j]);
                            sleep(1);
                        }

                        $style = new OutputFormatterStyle('green');
                        $output->getFormatter()->setStyle('fire', $style);
                        $output->writeln('<fire>Successfully run '. $filename .' migration</fire>');

                    } else {
                        // create data by model
                        $model::create($fields[0]);

                        $style = new OutputFormatterStyle('green');
                        $output->getFormatter()->setStyle('fire', $style);
                        $output->writeln('<fire>Successfully run '. $filename .' migration</fire>');

                    }

                } else {

                    $style = new OutputFormatterStyle('red');
                    $output->getFormatter()->setStyle('danger', $style);
                    $output->writeln('<danger>File ' . $name . ' is not exists!</danger>');

                }

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