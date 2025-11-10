<?php
header('Content-Type: application/json');
session_start();
require_once 'config.php';

function send_response($success, $message = '', $errors = []) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'errors' => $errors
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_response(false, 'Método inválido');
}

$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';

$errors = [];

if (!$name || strlen($name) < 3) {
    $errors['name'] = 'Nome deve ter no mínimo 3 caracteres';
}

if (!$email) {
    $errors['email'] = 'Email inválido';
}

if (strlen($password) < 6) {
    $errors['password'] = 'Senha deve ter no mínimo 6 caracteres';
}

if ($password !== $password_confirm) {
    $errors['password_confirm'] = 'As senhas não conferem';
}

if ($errors) {
    send_response(false, 'Por favor corrija os erros', $errors);
}

try {
    // Check if email exists
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        send_response(false, 'Este email já está cadastrado');
    }

    // Insert new user
    $stmt = $pdo->prepare('
        INSERT INTO users (name, email, password) 
        VALUES (?, ?, ?)
    ');
    
    $stmt->execute([
        $name,
        $email,
        password_hash($password, PASSWORD_DEFAULT)
    ]);

    send_response(true, 'Conta criada com sucesso');
} catch (PDOException $e) {
    send_response(false, 'Erro ao criar conta');
}
?>