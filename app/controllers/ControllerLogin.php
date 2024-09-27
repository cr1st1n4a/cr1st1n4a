<?php

namespace app\controllers;

use app\database\builder\InsertQuery;
use app\database\builder\SelectQuery;
use app\database\Connection;

class ControllerLogin extends Base
{
    public function login($request, $response)
    {
        return $this->renderLoginPage($response);
    }

    public function register($request, $response)
    {
        $form = $request->getParsedBody();
        $login = filter_var($form['login'], FILTER_UNSAFE_RAW);
        $senha = filter_var($form['senha'], FILTER_UNSAFE_RAW);
        $email = filter_var($form['email'], FILTER_UNSAFE_RAW);

        // Verificar se o login já existe
        $selectQuery = SelectQuery::select('login')->from('usuario')->where('login', '=', $login);
        $existingUser = $selectQuery->fetch();

        if ($existingUser) {
            return $response->withStatus(400)->write('Login já existe.');
        }

        $insertQuery = InsertQuery::table('usuario');
        $hashedPassword = password_hash($senha, PASSWORD_DEFAULT);

        if ($insertQuery->save(['login' => $login, 'senha' => $hashedPassword, 'email' => $email])) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        return $response->withStatus(500)->write('Erro ao cadastrar usuário.');
    }

    public function changePassword($request, $response)
    {
        $form = $request->getParsedBody();
        $senhaAtual = filter_var($form['senhaAtual'], FILTER_UNSAFE_RAW);
        $novaSenha = filter_var($form['novaSenha'], FILTER_UNSAFE_RAW);
        $confirmeSenha = filter_var($form['confirmeSenha'], FILTER_UNSAFE_RAW);

        if ($novaSenha !== $confirmeSenha) {
            return $response->withStatus(400)->write('As senhas não coincidem.');
        }

        session_start();
        $login = $_SESSION['login'];

        $selectQuery = SelectQuery::select('senha')->from('usuario')->where('login', '=', $login);
        $user = $selectQuery->fetch();

        if (!$user || !password_verify($senhaAtual, $user->senha)) {
            return $response->withStatus(400)->write('Senha atual incorreta.');
        }

        $hashedPassword = password_hash($novaSenha, PASSWORD_DEFAULT);

        $sql = "UPDATE usuario SET senha = :senha WHERE login = :login";
        $connection = Connection::open();
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':senha', $hashedPassword);
        $stmt->bindParam(':login', $login);

        if ($stmt->execute()) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        return $response->withStatus(500)->write('Erro ao mudar a senha.');
    }

    public function authenticate($request, $response)
    {
        $form = $request->getParsedBody();
        $login = filter_var($form['login'], FILTER_UNSAFE_RAW);
        $senha = filter_var($form['senha'], FILTER_UNSAFE_RAW);

        $selectQuery = SelectQuery::select('*')->from('usuario')->where('login', '=', $login);
        $user = $selectQuery->fetch();

        if (!$user || !password_verify($senha, $user->senha)) {
            return $response->withStatus(401)->write('Login ou senha incorretos.');
        }

        session_start();
        $_SESSION['login'] = $user->login; // Armazena o login na sessão

        return $response->withHeader('Location', '/dashboard')->withStatus(302);
    }

    private function renderLoginPage($response)
    {
        ob_start();
        include __DIR__ . '/../views/login.html';
        $html = ob_get_clean();
        $response->getBody()->write($html);
        return $response;
    }
}
