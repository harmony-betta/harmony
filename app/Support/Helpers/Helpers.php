<?php

// Twig custom filter

use Twig\TwigFilter;
use Twig\TwigFunction;

$twigCustomFilter = new TwigFilter('hello', function ($data) {
    return 'hello world' . $data;
});


// Twig custom function
$now = new TwigFunction('now', function($data=null){
    printf("Right now is %s", \Carbon\Carbon::now()->toDayDateTimeString());
});

$date = new TwigFunction('date', function($string=null){
    return ($string == '') ? date('d-M-Y') : date($string);
});

$app_name = new TwigFunction('app_name', function(){
    return  env('APP_NAME');
});
