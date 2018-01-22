<?php

namespace App\Support\Commands;

use App\Support\Commands\HelperCommands\HelperCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle as OutputFormatterStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class GeneratorAuthScaffolding extends Command
{
    protected $commandName = 'make:auth-full';
    protected $commandDescription = "Create Full Auth Scaffolding for your application";
    protected function configure()
    {
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription);
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $process = new Process('php artisan make:auth && php artisan make:auth-password && php artisan db:generate Users && php artisan make:model Users');
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $dir = dirname(__FILE__).'/HelperCommands/Templates/authTemp';//"path/to/targetFiles";
        $dirNew = dirname(dirname(dirname(dirname(__FILE__)))).'/resources/views/auth';//path/to/destination/files
        
        $this->rcopy($dir , $dirNew);

        $text = "Create Full Auth Scaffolding Successfully!";

        $style = new OutputFormatterStyle('green');
        $output->getFormatter()->setStyle('fire', $style);
        $output->writeln('<fire>'.$text.'</fire>');

    }

    // Function to remove folders and files 
    protected function rrmdir($dir) {
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file)
                if ($file != "." && $file != "..") $this->rrmdir("$dir/$file");
            rmdir($dir);
        }
        else if (file_exists($dir)) unlink($dir);
    }

    // Function to Copy folders and files       
    protected function rcopy($src, $dst) {
        if (file_exists ( $dst ))
            $this->rrmdir ( $dst );
        if (is_dir ( $src )) {
            mkdir ( $dst );
            $files = scandir ( $src );
            foreach ( $files as $file )
                if ($file != "." && $file != "..")
                    $this->rcopy ( "$src/$file", "$dst/$file" );
        } else if (file_exists ( $src ))
            copy ( $src, $dst );
    }
}