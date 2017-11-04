<?php

namespace App\Support\Commands;

use App\Support\Commands\HelperCommands\HelperCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle as OutputFormatterStyle;

class GeneratorMiddleware extends Command
{
    protected $commandName = 'make:middleware';
    protected $commandDescription = "Create Middleware for your application";
    protected $commandArgumentName = "name";
    protected $commandArgumentDescription = "This command will create Middleware for your application";
    // protected $commandOptionName = "m"; // should be specified like "app:greet John --cap"
    // protected $commandOptionDescription = 'If set, it will create Middleware for controller';    
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
            // ->addOption(
            //    $this->commandOptionName,
            //    null,
            //    InputOption::VALUE_NONE,
            //    $this->commandOptionDescription
            // )
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument($this->commandArgumentName);
        if ($name) {
            
            $pagename = $name;
            $MiddlewareName = 'app/Middleware/'.$pagename.".php";
            $newFileContent = HelperCommand::getFileMiddleware('MiddlewareTemplate', $pagename);
            if (!file_exists($MiddlewareName)) {
                function make_path($MiddlewareName)
                {
                    $dir = pathinfo($MiddlewareName , PATHINFO_DIRNAME);
                    
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
                make_path($MiddlewareName);
                if (file_put_contents($MiddlewareName, $newFileContent, FILE_APPEND) !== false && file_put_contents(
                    dirname(dirname(dirname(__DIR__)))."/bootstrap/middleware.php",
                    "\$app->add(new \App\Middleware\\$pagename(\$container));\n",
                    FILE_APPEND
                ) !== false) {
                    $text = "File created (" . basename($MiddlewareName) . ") in app/Middleware/".$pagename.".php";
                } else {
                    $text = "Cannot create file (" . basename($MiddlewareName) . ")";
                }
            }else{
                $text = "File exists in Middleware";
            }
        } else {
            $text = HelperCommand::getHelp('ErrorControllerCommand');
        }
        
        // if ($input->getOption($this->commandOptionName) && $input->getArgument($this->commandArgumentName)) {
            
        //     $Middlewarename = str_replace('Controller','s',$name);
            
        //     $MiddlewareFile = 'app/Middleware/'.ucfirst($Middlewarename).".php";
        //     $newFileContent = HelperCommand::getFileMiddleware('MiddlewareTemplate', $Middlewarename);
        //     if (!file_exists($MiddlewareFile)) {
            
        //         if (file_put_contents($MiddlewareFile, $newFileContent, FILE_APPEND) !== false) {
        //             $text = "Controller created Successfully\nMiddleware created Successfully";
        //         } else {
        //             $text = "Cannot create file (" . basename($MiddlewareFile) . ")";
        //         }
        //     }else{
        //         $text = "File exists in Middleware";
        //     }
        // }
        $style = new OutputFormatterStyle('green');
        $output->getFormatter()->setStyle('fire', $style);
        $output->writeln('<fire>'.$text.'</fire>');
    }
}