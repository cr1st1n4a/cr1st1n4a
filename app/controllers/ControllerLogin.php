<?php

namespace app\controllers;

class ControllerLogin extends Base
{
    public function login($request, $response, $args)
    {
        $TemplateData = [
                'titulo' => 'Login'
            ];
            return $this->getTwig()
                ->render($response, $this->setView('login'), $TemplateData)
                ->withHeader('Content-Type', 'text/html')
                ->withStatus(200);
    }
}