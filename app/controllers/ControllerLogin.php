<?php

namespace app\controllers;


class ControllerLogin extends Base
{
    public function login($request, $response)
    {
        $TempleteData = [
            'titulo' => 'Tela de Login'
        ];
        return $this->getTwig()
            ->render($response, $this->setView('login'), $TempleteData)
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }
}