<?php

use app\controllers\ControllerCliente;
use app\controllers\ControllerDisciplina;
use app\controllers\ControllerHome;
use app\controllers\ControllerLogin;
use app\Middleware\Middleware;
use Slim\Routing\RouteCollectorProxy;

$app->get('/', ControllerHome::class . ':home')->add(Middleware::route());


$app->get('/login', ControllerLogin::class . ':login')->add(Middleware::route());
$app->post('/login/authenticate', ControllerLogin::class . ':authenticate');
$app->post('/usuario/insert', ControllerLogin::class . ':insert');


$app->group('/cliente', function (RouteCollectorProxy $group) {
    $group->get('/cadastro', ControllerCliente::class . ':cadastro');
});
$app->group('/disciplina', function (RouteCollectorProxy $group) {
    $group->get('/lista', ControllerDisciplina::class . ':lista');
    $group->get('/cadastro', ControllerDisciplina::class . ':cadastro');
    $group->get('/alterar/{id}', ControllerDisciplina::class . ':alterar');
    $group->post('/delete', ControllerDisciplina::class . ':delete');
    $group->post('/insert', ControllerDisciplina::class . ':insert');
});