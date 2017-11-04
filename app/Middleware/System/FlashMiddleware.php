<?php

namespace App\Middleware\System;

class FlashMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        $this->container->view->getEnvironment()->addGlobal('flash', $this->container->flash);
        $response = $next($request, $response);
        return $response;
    }
}