<?php

use app\controllers\ControllerDisciplina;
use app\controllers\ControllerHome;
use Slim\Routing\RouteCollectorProxy;

$app->get('/', ControllerHome::class . ':home');

$app->group('/disciplina', function (RouteCollectorProxy $group) {
    $group->get('/lista', ControllerDisciplina::class . ':lista');
    $group->get('/cadastro', ControllerDisciplina::class . ':cadastro');
    $group->get('/alterar/{id}', ControllerDisciplina::class . ':alterar');
});
