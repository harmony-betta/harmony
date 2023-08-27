<?php

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

$app->get('/', function (Request $request, Response $response) {
    return Twig::fromRequest($request)->render($response, 'welcome.twig');
});