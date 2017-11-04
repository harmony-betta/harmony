<?php

namespace App\Controllers;

use Slim\Views\Twig as View;

class AdminController extends Controller
{
    public function index($request, $response)
    {
        $this->view->render($response, 'admin/home.twig');
    }

    public function error_404($request, $response)
    {
        return $this->view->render($response, 'errors/404.twig');
    }
}