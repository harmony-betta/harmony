<?php

use Twig\TwigFunction;

$public = new TwigFunction('public', function($data){
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

// Register filter custom twig
$twig->getEnvironment()->addFilter($twigCustomFilter);

// Register function custom twig
$twig->getEnvironment()->addFunction($now);
$twig->getEnvironment()->addFunction($date);
$twig->getEnvironment()->addFunction($app_name);
$twig->getEnvironment()->addFunction($public);