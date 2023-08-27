<?php

namespace App\Middleware\System;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Views\Twig;

class OldInputMiddleware extends Middleware
{
    public function __invoke(Request $request, RequestHandler $next)
    {
        Twig::fromRequest($request)->getEnvironment()->addGlobal('old', @$_SESSION['old']);
        $_SESSION['old'] = $request->getQueryParams();

        $response = $next->handle($request);
        return $response;
    }
}