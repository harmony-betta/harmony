<?php

/** 
 * 
 * Website Route
 * 
 * Berikut adalah default route Harmony Framework
 * mohon untuk tidak mengubah default route dari Harmony Framework.
 * 
 * Silahkan definikan route buatan anda di baris paling bawah.
 * 
 */

$app->get('/', function($request, $response){

    return $this->view->render($response, 'welcome.twig');

});