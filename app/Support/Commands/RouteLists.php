<?php

namespace App\Support\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class RouteLists extends Command
{
    protected function configure()
    {
        $this->setName('route:list')
            ->setDescription('Outputs all routes in your application');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {       
        $table = new Table($output);

        $table->setHeaders(array('Routes', 'Names', 'Methods'));

        require_once dirname(dirname(dirname(__DIR__))).'/bootstrap/app.php';

        $routes = app()->getRouteCollector()->getRoutes();
        
        $rows = array();
        foreach ($routes as $route) {

            $rows[] = array(
                $route->getPattern(),
                $route->getName(),
                implode(', ', $route->getMethods())
            );
        }
        
        $table->setRows($rows);
        $table->render();
    }
}