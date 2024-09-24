<?php

namespace app\controllers;

use app\database\builder\InsertQuery;

class ControllerUsuario extends Base{
public function insert($request, $response)
{
    try {
        $form = $request->getParsedBody();
        #Recupera os dados do nome e converte para uma string.
        $nome = filter_var($form['nome'], FILTER_UNSAFE_RAW);
        $passsword = password_hash(filter_var($form['senha'], FILTER_UNSAFE_RAW), PASSWORD_DEFAULT);
        $IsSave = InsertQuery::table('usuario')
            ->save([
                'nome' => $nome,
                'senha' => $passsword
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
            die;
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
        die;
    } catch (\Exception $e) {
        return $response->withStatus(403)
            ->withHeader('Content-type', 'application/json');

    }
}
};