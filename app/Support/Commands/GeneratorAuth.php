<?php

namespace App\Support\Commands;

use App\Support\Commands\HelperCommands\HelperCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle as OutputFormatterStyle;

class GeneratorAuth extends Command
{
    protected $commandName = 'make:auth';
    protected $commandDescription = "Create Authentication Scaffolding for your application";
    protected function configure()
    {
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription);
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {          
        $pagename = 'AuthController';
        $controllerName = 'app/Controllers/Auth/'.$pagename.".php";
        $newFileContent = HelperCommand::getFileAuth('AuthController', $pagename);
        if (!file_exists($controllerName)) {
            function make_path($controllerName)
            {
                $dir = pathinfo($controllerName , PATHINFO_DIRNAME);
                
                if( is_dir($dir) )
                {
                    return true;
                }
                else
                {
                    if( make_path($dir) )
                    {
                        if( mkdir($dir) )
                        {
                            chmod($dir , 0777);
                            return true;
                        }
                    }
                }
                
                return false;
            }
            make_path($controllerName);
            if (file_put_contents($controllerName, $newFileContent, FILE_APPEND)) {
                $text = "File created (" . basename($controllerName) . ") in app/Controllers/Auth/".$pagename.".php";
            } else {
                $text = "Cannot create file (" . basename($controllerName) . ")";
            }
        }else{
            $text = "File exists in Controllers";
        }

        $style = new OutputFormatterStyle('green');
        $output->getFormatter()->setStyle('fire', $style);
        $output->writeln('<fire>'.$text.'</fire>');
    }
}