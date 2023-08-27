<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig as View;

class ControllerName extends Controller
{
    /**
     * Method INDEX
     *
     * This is the default controller for ControllerName
     * @param [object] $request
     * @param [object] $response
     * @return object
     * 
     * Rendering view based on route request
     * routes/
     * |__ web.php
     * 
     * $app->get('/controller/method/params', 'ControllerName:index')
     */

     # protected $property = value|blank;

    public function index(Request $request, Response $response)
    {
        return View::fromRequest($request)->render($response, 'view.twig');
    }
}