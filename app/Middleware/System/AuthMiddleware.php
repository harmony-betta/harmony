<?php

namespace App\Middleware\System;

class AuthMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        // Check if user not signed in
        if (!$this->container->auth->check()) {
            
            // display flash message
            $this->container->flash->addMessage('error', 'Please sing in before doing that');

            // redirect
            return $response->withRedirect($this->container->router->pathFor('auth.signin'));
        }

        $response = $next($request, $response);
        return $response;
    }
}