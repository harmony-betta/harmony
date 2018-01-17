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

$date = new Twig_SimpleFunction('date', function($string=null){
    return ($string == '') ? date('d-M-Y') : date($string);
});

$asset = new Twig_SimpleFunction('asset', function($data){
    return  '../storage/public/'.$data;
});

$public = new Twig_SimpleFunction('public', function($data){
    return  '../assets/'.$data;
});