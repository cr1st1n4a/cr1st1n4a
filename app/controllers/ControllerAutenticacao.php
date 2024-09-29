<?php

namespace app\controllers;

use PDO;
use PDOException; // Para capturar exceções de PDO

class ControllerAutenticacao extends Base
{
    // Método para exibir a página de login
    public function login($request, $response)
    {
        $erro = $request->getQueryParams()['erro'] ?? null;
        return $this->getTwig()->render($response, $this->setView('login'), [
            'titulo' => 'Login',
            'erro' => $erro
        ])->withHeader('Content-Type', 'text/html')->withStatus(200);
    }

    // Método para autenticação
    public function autenticacao($request, $response)
    {
        $data = $request->getParsedBody();
        $usuario = $data['email'] ?? '';
        $senha = $data['senha'] ?? '';

        // Conectar ao banco de dados PostgreSQL
        try {
            $pdo = new PDO('pgsql:host=localhost;port=5432;dbname=aulasenac', 'gbsilva', 'guilherme10');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Erro na conexão: ' . $e->getMessage();
            return $response->withStatus(500);
        }

        // Consulta para verificar o usuário
        $stmt = $pdo->prepare('SELECT * FROM usuario WHERE login = :login AND senha = :senha');
        $stmt->execute(['login' => $usuario, 'senha' => $senha]);

        $usuarioEncontrado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se o usuário foi encontrado
        if ($usuarioEncontrado) {
            $_SESSION['login'] = [
                'logado' => true,
                'login' => $usuarioEncontrado['login'],
            ];
            return $response->withHeader('Location', HOME . '/home')->withStatus(302);
        } else {
            return $response->withHeader('Location', HOME . '/login?erro=usuario_invalido')->withStatus(302);
        }
    }

    // Método para logout
    public function logout($request, $response)
    {
        session_destroy();
        return $response->withHeader('Location', HOME . '/login')->withStatus(302);
    }

    // Método para exibir a página inicial
    public function inicio($request, $response)
    {
        $templeteData = [
            'titulo' => 'Você está no início, jovem gafanhoto'
        ];
        return $this->getTwig()
            ->render($response, $this->setView('inicio'), $templeteData)
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }
}
