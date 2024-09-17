<?php

namespace app\controllers;

use app\database\builder\UpdateQuery;

class ControllerHome extends Base
{
    public function home($request, $response)
    {
        $TempleteData = [
            'titulo' => 'Você esta no inicio, jovem gafanhoto'
        ];
        return $this->getTwig()
            ->render($response, $this->setView('pagina-inicial'), $TempleteData)
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }
    public function Update($request, $response)
    {
        $FieldsAndValues = [];
        $IsSave = UpdateQuery::table('users')
            ->set($FieldsAndValues)
            ->where('id', '=', 1)
            ->update();
    }
}
