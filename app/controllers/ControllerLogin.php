<?php

namespace app\controllers;

use app\database\builder\InsertQuery;
use app\database\builder\SelectQuery;
use app\traits\Template;

class ControllerLogin extends Base
{
    use Template;

    public function login($request, $response)
    {
        return $this->getTwig()->render($response, 'login.html', []);
    }

    public function authenticate($request, $response)
    {
        $params = $request->getParsedBody();
        $login = $params['login'] ?? '';
        $senha = $params['senha'] ?? '';

        // Verifica se o login existe
        $user = SelectQuery::select('*')
            ->from('usuario')
            ->where('login', '=', $login)
            ->fetch();

        // Se o usuário existe, verifica a senha
        if ($user && password_verify($senha, $user->senha)) { // Usando password_verify
            $_SESSION['login'] = [
                'logado' => true,
                'user_id' => $user->id, // Certifique-se de que 'id' está correto
            ];
            return $response->withHeader('Location', HOME . '/')->withStatus(302);
        }

        $response->getBody()->write('Login ou senha inválidos.');
        return $response->withStatus(401);
    }

    public function insert($request, $response)
    {
        $params = json_decode($request->getBody(), true);

        // Adiciona log para verificar os dados recebidos
        error_log(print_r($params, true));

        $nome = trim($params['nome'] ?? '');
        $login = trim($params['login'] ?? '');
        $email = trim($params['email'] ?? '');
        $senha = trim($params['senha'] ?? '');

        // Validação básica dos campos
        if (empty($nome) || empty($login) || empty($email) || empty($senha)) {
            $response->getBody()->write('Todos os campos são obrigatórios.');
            return $response->withStatus(400);
        }

        // Verifica se o login já existe
        $existingUser = SelectQuery::select('*')
            ->from('usuario')
            ->where('login', '=', $login)
            ->fetch();

        if ($existingUser) {
            $response->getBody()->write('Login já existe.');
            return $response->withStatus(400);
        }

        // Insere o novo usuário no banco de dados
        try {
            InsertQuery::table('usuario')->save([
                'nome' => $nome,
                'login' => $login,
                'email' => $email,
                'senha' => password_hash($senha, PASSWORD_DEFAULT), // Usando password_hash
                'ativo' => true
            ]);
            $response->getBody()->write('Cadastro realizado com sucesso!');
            return $response->withStatus(201);
        } catch (\Exception $e) {
            $response->getBody()->write('Erro ao cadastrar: ' . $e->getMessage());
            return $response->withStatus(500);
        }
    }
}
