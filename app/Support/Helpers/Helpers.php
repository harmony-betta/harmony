<?php

$view->addExtension(new Twig_Extension_Debug());

// Twig custom filter
$twigCustomFilter = new Twig_SimpleFilter('hello', function ($data) {
    return 'hello world' . $data;
});


// Twig custom function
$now = new Twig_SimpleFunction('now', function($data=null){
    printf("Right now is %s", \Carbon\Carbon::now()->toDayDateTimeString());
});

$asset = new Twig_SimpleFunction('asset', function($data){
    return  '../storage/public/'.$data;
});