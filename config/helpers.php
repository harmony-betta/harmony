<?php

use Slim\App;

if (!function_exists('app')) {
    /**
     * Get App Instance
     *
     * @return App|null
     */
    function app()
    {
        global $app;
        return $app;
    }
}

if (!function_exists('container')) {
    /**
     * Get Container
     *
     * @param string|null $name
     * @return mixed|null
     */
    function container(string $name = null)
    {
        if ($name === null) {
            return app()->getContainer();
        }

        return app()->getContainer()->get($name) ?? null;
    }
}

function replaceDirectorySeparator($path) {
    return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
}