<?php

namespace app\controllers;

use app\database\builder\InsertQuery;

class ControllerUsuario extends Base
{
    public function cadastro($request, $response)
    {

        try {
            $FieldsAndValues = [
                
                'nome'  => $_POST['nome'],
                'login' => $_POST['login'],
                'email' => $_POST['email'],
                'senha' => $_POST['passsword'],
                'ativo' => $_POST['ativo']
            ];
            $usuarios = (array) InsertQuery::table('usuario')->save($FieldsAndValues);
        } catch (\Exception $e) {
            
        }
    }
    public function insert($request, $response){
        try{
            $form = $request->getParsedBody();
            $nome = filter_var($form['nome'], FILTER_UNSAFE_RAW);
            $login = filter_var($form['login'], FILTER_UNSAFE_RAW);
            $email = filter_var($form['email'], FILTER_UNSAFE_RAW);
            $passsword = password_hash(filter_var($form['senha'], FILTER_UNSAFE_RAW), PASSWORD_DEFAULT);
            $ativo = filter_var($form['ativo'], FILTER_UNSAFE_RAW);
            $IsSave = InsertQuery::table('usuario')
                ->save([
                    'nome'  => $nome,
                    'login' => $login,
                    'email' => $email,
                    'senha' => $passsword,
                    'ativo' => $ativo
                ]);
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
                'msg' => 'Registro salvo com sucesso!',
                'id' => 0
            ];
            $json = json_encode($data, JSON_UNESCAPED_UNICODE);
            $response->getBody()
                ->write($json);
            return $response->withStatus(201)
                ->withHeader('Content-type', 'application/json');
        }catch (\Exception $e) {
            return $response->withStatus(403)
                ->withHeader('Content-type', 'application/json');
        }
    }
}
