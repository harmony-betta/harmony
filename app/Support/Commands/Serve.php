<?php

namespace App\Support\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle as OutputFormatterStyle;

class Serve extends Command
{
    protected function configure()
    {
        $this->setName('serve')
            ->setDescription('Serve the application on the PHP development server.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {   
        $style = new OutputFormatterStyle('green');
        $output->getFormatter()->setStyle('fire', $style);
        $output->writeln('<fire>Harmony development running on http://localhost:8081</fire>');

        passthru(PHP_BINARY . " -S localhost:8081 -t public");
    }
}