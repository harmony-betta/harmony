<?php

namespace App\Middleware\System;

class HelperMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        $this->container->view->getEnvironment()->addGlobal('helper', [
            'storage' => $this->container['settings']['storage'],
        ]);

        $response = $next($request, $response);
        return $response;
    }
}