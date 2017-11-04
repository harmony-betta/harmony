<?php

namespace App\Support\Commands;

use App\Support\Commands\HelperCommands\HelperCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle as OutputFormatterStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class StorageLink extends Command
{
    protected function configure()
    {
        $this->setName('storage:link')
             ->setDescription('Create symlink storage to public')
             ->setHelp('This command allows you to create, read, update, and delete file or directory.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        if(exec('ln -s storage/public public/storage')) {
            
            $text = "Storage folder published!";

        }else{

            $text = "Storage folder not published!";
        }

        $style = new OutputFormatterStyle('green');
        $output->getFormatter()->setStyle('fire', $style);
        $output->writeln('<fire>'.$text.'</fire>');
    }
}