<?php

// Register filter custom twig
$view->getEnvironment()->addFilter($twigCustomFilter);

// Register function custom twig
$view->getEnvironment()->addFunction($now);
$view->getEnvironment()->addFunction($asset);