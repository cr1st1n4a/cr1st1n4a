<?php

namespace app\controllers;

class ControllerLogin extends Base
{
    public function login($request, $response)
    {
        $TempleteData = [
            'titulo' => 'Cadastrar usuário'
        ];
        return $this->getTwig()
            ->render($response, $this->setView('login'), $TempleteData)
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
        try {
            return $this->getTwig()
                ->render($response, $this->setView('login'))
                ->withHeader('Content-Type', 'text/html')
                ->withStatus(200);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}