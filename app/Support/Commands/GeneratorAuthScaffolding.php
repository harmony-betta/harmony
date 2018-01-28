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

        $dirEmail = dirname(__FILE__).'/HelperCommands/Templates/email';//"path/to/targetFiles";
        $dirNewEmail = dirname(dirname(dirname(dirname(__FILE__)))).'/resources/views/email';//path/to/destination/files

        $dirPartials = dirname(__FILE__).'/HelperCommands/Templates/partials';//"path/to/targetFiles";
        $dirNewPartials = dirname(dirname(dirname(dirname(__FILE__)))).'/resources/views/partials';//path/to/destination/files

        // Insert new line in Middleware file
        $pathNewContentForMiddleware        = dirname(dirname(dirname(__DIR__))).'/bootstrap/middleware.php';
        $templateNewContentForMiddleware    = "\n\$app->add(new \App\Middleware\System\UserAuthenticationMiddleware(\$container));";

        // Insert new line in app.container file
        $pathNewContainer                   = dirname(dirname(dirname(__DIR__))).'/config/app.container.php';
        $templatNewContainer                = HelperCommand::getFileNewContainer('newContainer');

        // Insert use Middleware
        $routerFile                         = dirname(dirname(dirname(__DIR__))).'/routes/web.php';

        $file_contents                      = file_get_contents($routerFile);
        $insertToRouter                     = "<?php\n\nuse App\Middleware\System\GuestMiddleware;\nuse App\Middleware\System\AuthMiddleware;\n";
        $file_contents                      = str_replace("<?php", $insertToRouter, $file_contents);

        $insertToRouterName                 = "})->setName('home');";
        $file_routes                        = str_replace("});", $insertToRouterName, $file_contents);

        // Insert template to router
        $templatNewRoutes                   = HelperCommand::getFileNewRoutes('router');

        // Replace default Menu in welcome page
        $welcomeTemplate                    = dirname(dirname(dirname(__DIR__))).'/resources/views/welcome.twig';
        $contentsFromTemplate               = file_get_contents($welcomeTemplate);
        $newWelcomeTempalate                = HelperCommand::getFileNewWelcome('Welcome');

        // Change Migrations
        $dirOfMigration                     = glob(dirname(dirname(dirname(__DIR__))).'/app/Database/migrations/*_Users.php');
        $migrationContent                   = '';
        if (is_array($dirOfMigration) && !empty($dirOfMigration)) {
            foreach($dirOfMigration as $file) 
            {
                $migrationContent = $file;
            }
        }
        $dirMigrationFile                   = $migrationContent;
        $contentFormMigration               = file_get_contents($migrationContent);
        $newTemplateForMigration            = HelperCommand::getFileMigration('userMigrations');

        // Change Models
        $dirModelExist                      = dirname(dirname(dirname(__DIR__))).'/app/Models/Users.php';
        $contentFormModelsExists            = file_get_contents($dirModelExist);
        $newTemplateForModels               = HelperCommand::getFileNewModels('AuthModel');

        if( $this->rcopy($dir , $dirNew) === true && $this->rcopy($dirEmail , $dirNewEmail) === true && $this->rcopy($dirPartials , $dirNewPartials) === true ){
            // Append Middleware
            file_put_contents($pathNewContentForMiddleware, $templateNewContentForMiddleware, FILE_APPEND);
            // Append Container
            file_put_contents($pathNewContainer, $templatNewContainer, FILE_APPEND);
            // Use Class of Middleware on Route
            file_put_contents($routerFile, $file_contents);
            // Create Route name
            file_put_contents($routerFile, $file_routes);
            // Append new Route Schema for Auth
            file_put_contents($routerFile, $templatNewRoutes, FILE_APPEND);
            // Replace default Welcome Pagae using Auth
            file_put_contents($welcomeTemplate, $newWelcomeTempalate);
            // Replace default UserMigrations
            file_put_contents($dirMigrationFile, $newTemplateForMigration);
            // Replace default Model Users
            file_put_contents($dirModelExist, $newTemplateForModels);

            $process = new Process('php artisan db:migrate');
            $process->run();

            // executes after the command finishes
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $text = "Create Full Auth Scaffolding Successfully! ";
        }

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

        return true;
    }
}