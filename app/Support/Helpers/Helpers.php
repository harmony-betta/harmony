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

$app_name = new Twig_SimpleFunction('app_name', function(){
    return  env('APP_NAME');
});

$public = new Twig_SimpleFunction('public', function($data){
	if (env('APP_HOST') === 'http://localhost:8081') {
		$assets = sprintf(
		    "%s://%s",
		    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
		    $_SERVER['SERVER_NAME'] . '/assets/'.$data
		);
	} else {
		$assets = rtrim(env('APP_HOST'), '/') . '/assets/'.$data;
	}
	return $assets;
});
