<?php

namespace App\Middleware;
use App\Middleware\System;

class MiddlewareName extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        /**
         * TODO Tulis middleware anda disini
         */

        $response = $next($request, $response);
        return $response;
    }
}