<?php

require 'vendor/autoload.php';

use Slim\Factory\AppFactory;
use app\controllers\ControllerLogin;
use app\middleware\Middleware;

// Conectar ao banco de dados PostgreSQL
$host = "localhost"; // ou o endereço do seu servidor
$dbname = "senac"; // nome do seu banco de dados
$user = "cr1st1n4a"; // usuário do banco de dados
$password = "c09262824"; // senha do banco de dados
$db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$app = AppFactory::create();

// Adiciona o middleware de autenticação
$app->add(Middleware::route());

// Rota para servir o HTML de login
$app->get('/login', function ($request, $response) {
    return $response->write(file_get_contents('login.html'));
});

// Defina a rota para login
$app->post('/login', function ($request, $response) use ($db) {
    $controller = new ControllerLogin($db);
    return $controller->entrar($request, $response, []); // Passando o terceiro argumento
});

// Rota para a página inicial (ou outra página que você tenha)
$app->get('/', function ($request, $response) {
    return $response->write("Bem-vindo à página inicial!");
});

$app->run();