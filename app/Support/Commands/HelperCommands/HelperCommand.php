<?php

namespace App\Support\Commands\HelperCommands;

class HelperCommand
{
    public static function getFileAuth($filename, $auth_name)
    {
        $file = dirname(dirname(__FILE__)) .'/HelperCommands/Templates/Auth/' . $filename .'.php';

        if(file_exists($file)){
            $plaintext    = file_get_contents($file);
            $t_explode    = explode('/', $auth_name);
            $replace      = end($t_explode);
            $search       = $filename;
            $plaintext   = str_replace($search, $replace, $plaintext);
            return $plaintext;
        }else{
            return 'File Does not exists!';
        }
    }

    public static function getFileController($filename, $controller_name)
    {
        $file = dirname(dirname(__FILE__)) .'/HelperCommands/Templates/Controllers/' . $filename .'.php';

        if(file_exists($file)){
            $plaintext    = file_get_contents($file);
            $t_explode    = explode('/', $controller_name);
            $replace      = end($t_explode);
            $search       = 'ControllerName';
            $plaintext   = str_replace($search, $replace, $plaintext);
            return $plaintext;
        }else{
            return 'File Does not exists!';
        }
    }

    public static function getFileModel($filename, $model_name)
    {
        $file = dirname(dirname(__FILE__)) .'/HelperCommands/Templates/Models/' . $filename .'.php';

        if(file_exists($file)){
            $plaintext    = file_get_contents($file);
            $t_explode    = explode('/', $model_name);
            $replace      = end($t_explode);
            $search       = 'ModelName';
            $plaintext   = str_replace($search, $replace, $plaintext);
            return $plaintext;
        }else{
            return 'File Does not exists!';
        }
    }

    public static function getHelp($helper)
    {
        $help_file = dirname(dirname(__FILE__)) .'/HelperCommands/Helpers/Controllers/' . $helper .'.php';

        if(file_exists($help_file)){
            return file_get_contents($help_file);
        }else{
            return 'Helper File Does not exists!';
        }
    }

    public static function getFileMiddleware($filename, $middleware_name)
    {
        $file = dirname(dirname(__FILE__)) .'/HelperCommands/Templates/Middleware/' . $filename .'.php';
        
                if(file_exists($file)){
                    $plaintext    = file_get_contents($file);
                    $t_explode    = explode('/', $middleware_name);
                    $replace      = end($t_explode);
                    $search       = 'MiddlewareName';
                    $plaintext   = str_replace($search, $replace, $plaintext);
                    return $plaintext;
                }else{
                    return 'File Does not exists!';
                }
    }

    public static function getFileNewContainer($filename)
    {
        $file = dirname(dirname(__FILE__)) .'/HelperCommands/Templates/Container/' . $filename .'.php';
        
                if(file_exists($file)){
                    $plaintext    = file_get_contents($file);
                    $search       = '<?php';
                    $plaintext   = str_replace($search, '', $plaintext);
                    return $plaintext;
                }else{
                    return 'File Does not exists!';
                }
    }

    public static function getFileNewRoutes($filename)
    {
        $file = dirname(dirname(__FILE__)) .'/HelperCommands/Templates/Routes/' . $filename .'.php';
        
                if(file_exists($file)){
                    $plaintext    = file_get_contents($file);
                    $search       = '<?php';
                    $plaintext   = str_replace($search, '', $plaintext);
                    return $plaintext;
                }else{
                    return 'File Does not exists!';
                }
    }

    public static function getFileNewWelcome($filename)
    {
        $file = dirname(dirname(__FILE__)) .'/HelperCommands/Templates/' . $filename .'.php';
        
                if(file_exists($file)){
                    $plaintext    = file_get_contents($file);
                    return $plaintext;
                }else{
                    return 'File Does not exists!';
                }
    }

    public static function getFileMigration($filename)
    {
        $file = dirname(dirname(__FILE__)) .'/HelperCommands/Templates/Migrations/' . $filename .'.php';
        
                if(file_exists($file)){
                    $plaintext    = file_get_contents($file);
                    return $plaintext;
                }else{
                    return 'File Does not exists!';
                }
    }

    public static function getFileNewModels($filename)
    {
        $file = dirname(dirname(__FILE__)) .'/HelperCommands/Templates/Models/' . $filename .'.php';
        
                if(file_exists($file)){
                    $plaintext    = file_get_contents($file);
                    return $plaintext;
                }else{
                    return 'File Does not exists!';
                }
    }

    public static function getFileSeeders($filename)
    {
        $file = dirname(dirname(__FILE__)) .'/HelperCommands/Templates/Seeders/' . $filename .'.php';
        
        if(file_exists($file)){
            $plaintext    = file_get_contents($file);
            $replace      = $filename;
            $search       = 'SeedersTemplate';
            $plaintext   = str_replace($search, $replace, $plaintext);
            return $plaintext;
        }else{
            return 'File Does not exists!';
        }
    }
}