<?php

namespace App\Middleware\System;

class CsrfViewMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        $this->container->view->getEnvironment()->addGlobal('csrf', [
            'field' => '
                <input type="hidden" name="'. $this->container->csrf->getTokenNameKey() .'" value="'. $this->container->csrf->getTokenName() .'" id="csrf_name">
                <input type="hidden" name="'. $this->container->csrf->getTokenValueKey() .'" value="'. $this->container->csrf->getTokenValue() .'" id="csrf_value">
            '
        ]);

        $response = $next($request, $response);
        return $response;
    }
}