<?php
header('Content-Type: application/json');

// Conectar ao banco de dados PostgreSQL
$host = "localhost"; // ou o endereço do seu servidor
$dbname = "senac"; // nome do seu banco de dados
$user = "cr1st1n4a"; // usuário do banco de dados
$password = "c09262824"; // senha do banco de dados

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Capturar dados do POST
    $nome = $_POST['nome'];
    $login = $_POST['login'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT); // Criptografar a senha

    // Preparar e executar a consulta
    $stmt = $conn->prepare("INSERT INTO usuario (nome, login, email, senha) VALUES (:nome, :login, :email, :senha)");
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':login', $login);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao cadastrar.']);
    }

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}