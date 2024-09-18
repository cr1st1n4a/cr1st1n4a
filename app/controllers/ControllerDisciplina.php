<?php

namespace app\controllers;

use app\database\builder\DeleteQuery;
use app\database\builder\SelectQuery;

class ControllerDisciplina extends Base
{
    public function lista($request, $response)
    {

        try {
            $disciplina = (array) SelectQuery::select()
                ->from('disciplina')
                ->fetchAll();

            $TemplateData = [
                'titulo' => 'Lista de Disciplinas',
                'disciplina' => $disciplina
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
            ->withoutHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }
    public function alterar($request, $response, $args)
    {
        $TemplateData = [
            'acao' => 'e',
            'titulo' => 'Lista de Disciplinas'
        ];
    }
    public function delete($request, $response)
    {
        try {
            $form = $request->getParsedBody();
            $id = filter_var($form['id'], FILTER_SANITIZE_NUMBER_INT); // Use um filtro mais seguro
            $data = [
                'status' => true,
                'msg' => 'Registro excluído com sucesso!',
                'id' => $id
            ];

            $result = DeleteQuery::table('disciplina')
                ->where('id', '=', $id)
                ->delete();

                $data = [
                    'status' => $result,
                    'msg' => $result ? 'Registro excluído com sucesso!' : 'Falha ao excluir o registro.',
                    'id' => $id
                ];
          
            $json = json_encode($data, JSON_UNESCAPED_UNICODE);
            $response->getBody()->write($json);
            return $response->withStatus(200)
                ->withHeader('Content-type', 'application/json');
        } catch (\Exception $e) {
            $data = [
                'status' => false,
                'msg' => $e->getMessage(), // Mensagem de erro
            ];
            $json = json_encode($data, JSON_UNESCAPED_UNICODE);
            $response->getBody()->write($json);
            return $response->withStatus(500) // Código de erro apropriado
                ->withHeader('Content-type', 'application/json');
        }
    }  
    
}
