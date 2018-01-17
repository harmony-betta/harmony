<?php

namespace App\Support\Views;

class DebugExtension extends \Twig_Extension
{
    
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('dp', array($this, 'dp')),
        ];
    }

    public function dp($var)
    {
        return is_array($var) ? dd($var) : dump($var);
    }


}
