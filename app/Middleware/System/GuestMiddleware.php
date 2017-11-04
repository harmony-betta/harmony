<?php

namespace App\Middleware\System;

class GuestMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        // Check if not authenticated
        if ($this->container->auth->check()) {
            return $response->withRedirect($this->container->router->pathFor('home'));
        }

        $response = $next($request, $response);
        return $response;
    }
}