<?php

namespace app\controllers;

use app\database\builder\SelectQuery;

class ControllerLogin extends Base
{
    public function login($request,  $response, $args)
    {
        try {
            error_log("Tentativa de login recebida");

            // Renderiza o formulário de login
            $TemplateData = [
                'titulo' => 'Login',
            ];
            return $this->getTwig()
                ->render($response, $this->setView('login'), $TemplateData)
                ->withHeader('Content-Type', 'text/html')
                ->withStatus(200);
        } catch (\Exception $e) {
            throw new \Exception("Restrição: " . $e->getMessage(), 1);
        }
    }

    public function entrar($request, $response, $args)
    {
        $data = $request->getParsedBody();
        $username = filter_var($data['username'], FILTER_SANITIZE_STRING);
        $password = $data['password'];

        $usuario = (array) SelectQuery::select()
            ->from('usuario')
            ->where('username', '=', $username)
            ->fetch();

        if ($usuario && password_verify($password, $usuario['password_hash'])) {
            $_SESSION['usuario'] = [
                'logado' => true,
                'id' => $usuario['id'],
                'username' => $usuario['username'],
            ];

            return $response->withJson(['status' => true]);
        } else {
            return $response->withJson(['status' => false, 'message' => 'Credenciais inválidas'], 401); // Adiciona uma mensagem
        }
    }
}
