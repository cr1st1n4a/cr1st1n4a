<?php

namespace app\controllers;

use app\database\builder\InsertQuery;
use app\database\builder\SelectQuery;

class ControllerUsuario extends Base
{
    public function insert($request, $response)
    {
        try {
            $form = $request->getParsedBody();
            #Recupera os dados do nome e converte para uma string.
            $nome = filter_var($form['nome'], FILTER_UNSAFE_RAW);
            $login = filter_var($form['login'], FILTER_UNSAFE_RAW);
            $email = filter_var($form['email'], FILTER_UNSAFE_RAW);
            $senha = password_hash(filter_var($form['senha'], FILTER_UNSAFE_RAW), PASSWORD_DEFAULT);
            $FieldsAndValues = [
                'nome'  => $nome,
                'login' => $login,
                'email' => $email,
                'senha' => $senha
            ];
            $IsSave = InsertQuery::table('usuario')->save($FieldsAndValues);
            if ($IsSave != true) {
                $data = [
                    'status' => false,
                    'msg' => 'Restrição: ' . $IsSave,
                    'id' => 0
                ];
                $json = json_encode($data, JSON_UNESCAPED_UNICODE);
                $response->getBody()
                    ->write($json);
                return $response->withStatus(403)
                    ->withHeader('Content-type', 'application/json');
            }
            $data = [
                'status' => true,
                'msg' => 'Cadastro realizado com sucesso!',
                'id' => 0
            ];
            $json = json_encode($data, JSON_UNESCAPED_UNICODE);
            $response->getBody()
                ->write($json);
            return $response->withStatus(201)
                ->withHeader('Content-type', 'application/json');
        } catch (\Exception $e) {
            $data = [
                'status' => false,
                'msg' => 'Restrição: ' . $e->getMessage(),
                'id' => 0
            ];
            $json = json_encode($data, JSON_UNESCAPED_UNICODE);
            $response->getBody()
                ->write($json);
            return $response->withStatus(403)
                ->withHeader('Content-type', 'application/json');
        }
    }
    public function autenticar($request, $response)
    {
        try {
            $form = $request->getParsedBody();
            #Recupera os dados do nome e converte para uma string.
            $login = filter_var($form['login'], FILTER_UNSAFE_RAW);
            $senha = filter_var($form['senha'], FILTER_UNSAFE_RAW);
            #selecionar o usuario no banco pelo login ou pelo e-mail

            $user = (array) SelectQuery::select('usuario')
                    ->select()
                    ->where('login', '=', $login)
                    ->where('email', '=', $login)
                    ->fetch();
            if (!$user) {
                #Mandando a mensagem que o usuario não existe.
            }
            if (password_verify($senha, PASSWORD_DEFAULT) != true) {
                #Informamos que o usuário digitou ou a senha o o nome de usuário inválido
            }
            if ($user['ativo'] != true) {
                #informamos que o usuário não tem permissão ou autorização para acessar o sistema
            }
            $_SESSION['usuario'] = [
                'logado' => true,
                'nome' => $user['nome']
            ];
            #Depois redirecionamos para a pagina home.
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}