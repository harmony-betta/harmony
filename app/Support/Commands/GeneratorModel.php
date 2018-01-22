<?php

namespace App\Support\Commands;

use App\Support\Commands\HelperCommands\HelperCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle as OutputFormatterStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class GeneratorModel extends Command
{
    protected $commandName = 'make:model';
    protected $commandDescription = "Create Model for your application";
    protected $commandArgumentName = "name";
    protected $commandArgumentDescription = "This command will create Model for your Application"; 
    protected $commandOptionName = "migration"; // should be specified like "make:model Users --m"
    protected $commandOptionDescription = 'If you using flag <info>--m</info>, you will automatic generate Migration Also';
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
            
            $fix_name = ((substr($name, -1) == 's')) ? $name : $name.'s' ;
            $pagename = $fix_name;
            $modelName = 'app/Models/'.$pagename.".php";
            $newFileContent = HelperCommand::getFileModel('ModelTemplate', $pagename);
            if (!file_exists($modelName)) {
                function make_path($modelName)
                {
                    $dir = pathinfo($modelName , PATHINFO_DIRNAME);
                    
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
                make_path($modelName);
                if (file_put_contents($modelName, $newFileContent, FILE_APPEND) !== false) {
                    $text = "File created (" . basename($modelName) . ") in app/Models/".$pagename.".php";
                } else {
                    $text = "Cannot create file (" . basename($modelName) . ")";
                }
            }else{
                $text = "File exists in Models";
            }
        } else {
            $text = HelperCommand::getHelp('ErrorControllerCommand');
        }
        
        if ($input->getOption($this->commandOptionName) && $input->getArgument($this->commandArgumentName)) {
            
            $migrationName = str_replace('Controller','s',$name);
            $command = $this->getApplication()->find('db:generate');

            $arguments = array(
                'command' => 'db:generate',
                'name'    => $migrationName
            );

            $greetInput = new ArrayInput($arguments);
            $returnCode = $command->run($greetInput, $output);

            $text = "Model created Successfully\nMigration created Successfully";
        }
        $style = new OutputFormatterStyle('green');
        $output->getFormatter()->setStyle('fire', $style);
        $output->writeln('<fire>'.$text.'</fire>');
    }
}