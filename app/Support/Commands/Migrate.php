<?php

namespace App\Support\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle as OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Migrate extends Command
{
    protected function configure()
    {
        $this->setName('migrate')
            ->setDescription('Migrate all table to database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {   
        $process = new Process('php artisan db:migrate');
        $process->run();

            // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $style = new OutputFormatterStyle('green');
        $output->getFormatter()->setStyle('fire', $style);
        $output->writeln('<fire>Successfully run all migration</fire>');
    }
}