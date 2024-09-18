<?php
use app\database\builder\DeleteQuery;

header("Content-type: application/json; charset=utf-8");

$response = [
    'status' => false,
    'msg' => 'Erro ao processar a solicitação.',
    'id' => $_POST['id']
];

try {
    $result = DeleteQuery::table('disciplina')
        ->where('id', '=', $_POST['id'])
        ->delete();

    if ($result) {
        $response['status'] = true;
        $response['msg'] = 'Removido com sucesso.';
    } else {
        $response['msg'] = 'Falha ao remover.';
    }
} catch (Exception $e) {
    // Log do erro, se necessário
    error_log($e->getMessage());
    $response['msg'] = 'Erro inesperado: ' . $e->getMessage();
}

echo json_encode($response);