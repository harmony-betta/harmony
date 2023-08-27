<?php

namespace App\Middleware\System;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Views\Twig;

class FlashMiddleware extends Middleware
{
    public function __invoke(Request $request, RequestHandler $next)
    {
        Twig::fromRequest($request)->getEnvironment()->addGlobal('flash', container('flash'));
        
        $response = $next->handle($request);
        return $response;
    }
}