<?php
require_once 'config.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';

$errors = [];

if (!$name) $errors['name'] = 'Nome é obrigatório';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'E-mail inválido';
if (strlen($password) < 6) $errors['password'] = 'A senha deve ter ao menos 6 caracteres';
if ($password !== $password_confirm) $errors['password_confirm'] = 'As senhas não conferem';

if ($errors) {
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'E-mail já cadastrado']);
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
    $stmt->execute([$name, $email, $hash]);

    echo json_encode(['success' => true, 'message' => 'Conta criada com sucesso!']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro no servidor']);
}
?>
