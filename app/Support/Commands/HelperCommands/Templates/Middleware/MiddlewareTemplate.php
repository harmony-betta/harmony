<?php

namespace App\Middleware;
use App\Middleware\System\Middleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class MiddlewareName extends Middleware
{
    public function __invoke(Request $request, RequestHandler $next)
    {
        /**
         * TODO Tulis middleware anda disini
         */

         $response = $next->handle($request);
         return $response;
    }
}