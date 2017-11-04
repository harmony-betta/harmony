<?php

namespace App\Controllers;

use Slim\Views\Twig as View;

class ControllerName extends Controller
{
    /**
     * Method INDEX
     *
     * Ini adalah default dari ControllerName
     * @param [object] $request
     * @param [object] $response
     * @return object
     * 
     * Rendering view sesuai routes request
     * routes/
     * |__ web.php
     * 
     * $app->get('/controller/method/params', 'ControllerName:index')
     */

     # protected $property = value|blank;

    public function index($request, $response)
    {
        /**
         * Anda bisa menyesuaikan view yang diiginkan dengan menggunakan
         * Psuedo Variabel [ $this ] yang mewakili container atau
         * wadah dari MVC Application ini.
         * 
         * ***********************************************************
         * 
         *      $this->view->render($response, 'page.twig');         *
         * 
         * ***********************************************************
         */

         # code..
    }
}