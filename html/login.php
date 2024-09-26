<?php

require 'vendor/autoload.php';


// Conectar ao banco de dados PostgreSQL
$host = "localhost"; // ou o endereço do seu servidor
$dbname = "senac"; // nome do seu banco de dados
$user = "cr1st1n4a"; // usuário do banco de dados
$password = "c09262824"; // senha do banco de dados

try {
    // Conectar ao banco de dados
    $pdo = new PDO("pgsql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepara e executa a consulta
    $stmt = $pdo->prepare('SELECT * FROM usuario WHERE login = :login');
    $stmt->execute(['login' => $login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se o usuário existe e se a senha está correta
    if ($user && password_verify($senha, $user['senha'])) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Login ou senha inválidos.']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>