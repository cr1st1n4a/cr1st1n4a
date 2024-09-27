<?php

use app\controllers\ControllerCliente;
use app\controllers\ControllerDisciplina;
use app\controllers\ControllerHome;
use app\controllers\ControllerLogin;
use app\controllers\ControllerUsuario;
use app\Middleware\Middleware;
use Slim\Routing\RouteCollectorProxy;

// Rota para a página inicial
$app->get('/', ControllerHome::class . ':home')->add(Middleware::route());

// Rota para a página de login
$app->get('/login', ControllerLogin::class . ':login');

// Rota para autenticação de usuários
$app->post('/authenticate', ControllerLogin::class . ':authenticate');

// Rota para registro de usuários
$app->post('/login/register', ControllerLogin::class . ':register');

// Rota para mudança de senha
$app->post('/login/change-password', ControllerLogin::class . ':changePassword');

// Grupo de rotas relacionadas a Cliente
$app->group('/cliente', function (RouteCollectorProxy $group) {
    // Rota para cadastro de clientes
    $group->get('/cadastro', ControllerCliente::class . ':cadastro');
});

// Grupo de rotas relacionadas a Usuário
$app->group('/usuario', function (RouteCollectorProxy $group) {
    // Rota para inserir um novo usuário
    $group->post('/insert', ControllerUsuario::class . ':insert');
});

// Grupo de rotas relacionadas a Disciplina
$app->group('/disciplina', function (RouteCollectorProxy $group) {
    // Rota para listar disciplinas
    $group->get('/lista', ControllerDisciplina::class . ':lista');
    // Rota para cadastro de disciplinas
    $group->get('/cadastro', ControllerDisciplina::class . ':cadastro');
    // Rota para alterar disciplina por ID
    $group->get('/alterar/{id}', ControllerDisciplina::class . ':alterar');
    // Rota para deletar disciplina
    $group->post('/delete', ControllerDisciplina::class . ':delete');
    // Rota para inserir nova disciplina
    $group->post('/insert', ControllerDisciplina::class . ':insert');
});

