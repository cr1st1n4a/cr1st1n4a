<?php

namespace app\traits;

use Slim\Views\Twig;

trait Template
{
    public function getTwig()
    {
        try {
            $twig = Twig::create(DIR_VIEW);
            return $twig;
        } catch (\Exception $e) {
            #RETORNAMOS O ERRO.
            throw new \Exception("Erro: " . $e->getMessage(), 1);
        }
    }
    public function setView($name)
    {
        return $name . EXT_VIEWS;
    }
}    
