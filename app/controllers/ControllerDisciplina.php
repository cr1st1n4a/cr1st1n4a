<?php

namespace app\controllers;

use app\database\builder\SelectQuery;
use app\database\builder\DeleteQuery;
use app\database\builder\InsertQuery;

class ControllerDisciplina extends Base
{
    public function lista($request, $response)
    {
        try {
            $disciplinas = (array) SelectQuery::select()
                ->from('disciplina')
                ->fetchAll();
            $TemplateData = [
                'nome' => $_SESSION['nome'],
                'idade' => $_SESSION['idade'],
                'titulo' => 'Lista de Disciplinas',
                'disciplinas' => $disciplinas
            ];
            return $this->getTwig()
                ->render($response, $this->setView('listadisciplina'), $TemplateData)
                ->withHeader('Content-Type', 'text/html')
                ->withStatus(200);
        } catch (\Exception $e) {
            throw new \Exception("Restrição: " . $e->getMessage(), 1);
        }
    }
    public function cadastro($request, $response)
    {
        $TemplateData = [
            'acao' => 'c',
            'titulo' => 'Lista de Disciplinas'
        ];
        return $this->getTwig()
            ->render($response, $this->setView('disciplina'), $TemplateData)
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }
    public function alterar($request, $response, $args)
    {
        $id = filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);
        $disciplina = (array) SelectQuery::select()
            ->from('disciplina')
            ->where('id', '=', $id)
            ->fetch();
        $TemplateData = [
            'id' => $id,
            'disciplina' => $disciplina,
            'acao' => 'e',
            'titulo' => 'Lista de Disciplinas'
        ];
        return $this->getTwig()
            ->render($response, $this->setView('disciplina'), $TemplateData)
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }
    public function delete($request, $response)
    {
        try {
            $form = $request->getParsedBody();
            $id = filter_var($form['id'], FILTER_UNSAFE_RAW);
            if (is_null($id)) {
                $data = [
                    'status' => false,
                    'msg' => 'Por favor informe o código do registro a ser excluído!',
                    'id' => 0
                ];
                $json = json_encode($data, JSON_UNESCAPED_UNICODE);
                $response->getBody()
                    ->write($json);
                return $response->withStatus(403)
                    ->withHeader('Content-type', 'application/json');
                die;
            }
            $IsDelete = DeleteQuery::table('disciplina')
                ->where('id', '=', $id)
                ->delete();
            if ($IsDelete != true) {
                $data = [
                    'status' => false,
                    'msg' => 'Restrição: ' . $IsDelete,
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
                'msg' => 'Registro excluído com sucesso!',
                'id' => $id
            ];
            $json = json_encode($data, JSON_UNESCAPED_UNICODE);
            $response->getBody()
                ->write($json);
            return $response->withStatus(200)
                ->withHeader('Content-type', 'application/json');
        } catch (\Exception $e) {
            $data = [
                'status' => true,
                'msg' => 'Registro excluído com sucesso!',
                'id' => $id
            ];
            $json = json_encode($data, JSON_UNESCAPED_UNICODE);
            $response->getBody()
                ->write($json);
            return $response->withStatus(200)
                ->withHeader('Content-type', 'application/json');
        }
    }
    public function insert($request, $response)
    {
        try {
            $form = $request->getParsedBody();
            #Recupera os dados do nome e converte para uma string.
            $nome = filter_var($form['nome'], FILTER_UNSAFE_RAW);
            $passsword = password_hash(filter_var($form['senha'], FILTER_UNSAFE_RAW), PASSWORD_DEFAULT);
            $IsSave = InsertQuery::table('disciplina')
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
}