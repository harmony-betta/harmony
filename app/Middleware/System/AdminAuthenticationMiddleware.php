<?php

namespace App\Middleware\System;

class AdminAuthenticationMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        $this->container->view->getEnvironment()->addGlobal('admin', [
           'checkAdmin' => $this->container->admin->checkAdmin(),
           'admin' => $this->container->admin->admin(),
        ]);

        $response = $next($request, $response);
        return $response;
    }
}