<?php

namespace App\Support\Commands;

use App\Support\Commands\HelperCommands\HelperCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle as OutputFormatterStyle;

class GeneratorController extends Command
{
    protected $commandName = 'make:controller';
    protected $commandDescription = "Create Controller for your application";
    protected $commandArgumentName = "name";
    protected $commandArgumentDescription = "This command will create Controller for your application";
    protected $commandOptionName = "m"; // should be specified like "app:greet John --cap"
    protected $commandOptionDescription = 'If you using flag <info>--m</info>, you will automatic generate Model Also';    
    protected function configure()
    {
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription)
            ->addArgument(
                $this->commandArgumentName,
                InputArgument::OPTIONAL,
                $this->commandArgumentDescription
            )
            ->addOption(
               $this->commandOptionName,
               null,
               InputOption::VALUE_NONE,
               $this->commandOptionDescription
            )
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument($this->commandArgumentName);
        if ($name) {
            
            $pagename = $name;
            $controllerName = 'app/Controllers/'.$pagename.".php";
            $newFileContent = HelperCommand::getFileController('ControllerTemplate', $pagename);
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
                if (file_put_contents($controllerName, $newFileContent, FILE_APPEND) !== false && file_put_contents(
                    dirname(dirname(dirname(__DIR__)))."/config/app.container.php",
                    "\$container['$pagename'] = function(\$container) {\n\treturn new \App\Controllers\\$pagename(\$container);\n};\n\n",
                    FILE_APPEND
                ) !== false ) {
                    $text = "File created (" . basename($controllerName) . ") in app/Controllers/".$pagename.".php";
                } else {
                    $text = "Cannot create file (" . basename($controllerName) . ")";
                }
            }else{
                $text = "File exists in Controllers";
            }
        } else {
            $text = HelperCommand::getHelp('ErrorControllerCommand');
        }
        
        if ($input->getOption($this->commandOptionName) && $input->getArgument($this->commandArgumentName)) {
            
            $modelname = str_replace('Controller','s',$name);
            
            $modelFile = 'app/Models/'.ucfirst($modelname).".php";
            $newFileContent = HelperCommand::getFileModel('ModelTemplate', $modelname);
            if (!file_exists($modelFile)) {
            
                if (file_put_contents($modelFile, $newFileContent, FILE_APPEND) !== false) {
                    $text = "Controller created Successfully\nModel created Successfully";
                } else {
                    $text = "Cannot create file (" . basename($modelFile) . ")";
                }
            }else{
                $text = "File exists in Models";
            }
        }
        $style = new OutputFormatterStyle('green');
        $output->getFormatter()->setStyle('fire', $style);
        $output->writeln('<fire>'.$text.'</fire>');
    }
}