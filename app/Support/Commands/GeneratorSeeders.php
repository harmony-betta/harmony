<?php

namespace App\Support\Commands;

use App\Support\Commands\HelperCommands\HelperCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle as OutputFormatterStyle;

class GeneratorSeeders extends Command
{
    protected $commandName = 'seed:generate';
    protected $commandDescription = "Create New Seeder for your application";
    protected $commandArgumentName = "name";
    protected $commandArgumentDescription = "This command will create Seeder file for your application";
    protected function configure()
    {
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription)
            ->addArgument(
                $this->commandArgumentName,
                InputArgument::OPTIONAL,
                $this->commandArgumentDescription
            );
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument($this->commandArgumentName);
        if ($name) {
            
            $pagename = $name;
            $controllerName = 'app/Database/Seeds/'.$pagename.".php";
            $newFileContent = HelperCommand::getFileSeeders('SeedersTemplate', $pagename);
            if (!file_exists($controllerName)) {
                if (file_put_contents($controllerName, $newFileContent, FILE_APPEND) !== false) {
                    $text = basename($controllerName) ." created Successfully";
                } else {
                    $text = "Cannot create file (" . basename($controllerName) . ")";
                }
            } else {
                $text = $controllerName . " file is exists!";
            }


        } else {
            $text = HelperCommand::getHelp('Seeders','ErrorSeedersCommand');
        }

        $style = new OutputFormatterStyle('green');
        $output->getFormatter()->setStyle('fire', $style);
        $output->writeln('<fire>'.$text.'</fire>');
    }
}