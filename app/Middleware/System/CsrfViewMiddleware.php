<?php

namespace App\Middleware\System;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Views\Twig;

class CsrfViewMiddleware extends Middleware
{
    public function __invoke(Request $request, RequestHandler $next)
    {
        Twig::fromRequest($request)->getEnvironment()->addGlobal('csrf', [
            'field' => '
                <input type="hidden" name="'. container('csrf')->getTokenNameKey() .'" value="'. container('csrf')->getTokenName() .'" id="csrf_name">
                <input type="hidden" name="'. container('csrf')->getTokenValueKey() .'" value="'. container('csrf')->getTokenValue() .'" id="csrf_value">
            '
        ]);

        $response = $next->handle($request);
        return $response;
    }
}