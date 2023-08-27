<?php

namespace App\Middleware\System;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Views\Twig;

class HelperMiddleware extends Middleware
{
    public function __invoke(Request $request, RequestHandler $next)
    {
        Twig::fromRequest($request)->getEnvironment()->addGlobal('helper', [
            'storage' => container('storage'),
        ]);

        $response = $next->handle($request);
        return $response;
    }
}
