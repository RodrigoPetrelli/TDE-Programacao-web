<?php
header('Content-Type: application/json');
session_start();
require_once 'config.php';

function send_response($success, $message = '', $errors = [], $data = []) {
    echo json_encode(array_merge([
        'success' => $success,
        'message' => $message,
        'errors' => $errors
    ], $data));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_response(false, 'Método inválido');
}

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']);

$errors = [];

if (!$email) {
    $errors['email'] = 'Email inválido';
}

if (strlen($password) < 6) {
    $errors['password'] = 'Senha deve ter no mínimo 6 caracteres';
}

if ($errors) {
    send_response(false, 'Por favor corrija os erros', $errors);
}

try {
    $stmt = $pdo->prepare('SELECT id, name, password FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        send_response(false, 'Email ou senha incorretos');
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];

    send_response(true, 'Login realizado com sucesso', [], [
        'user_id' => $user['id'],
        'user_name' => $user['name']
    ]);

} catch (PDOException $e) {
    send_response(false, 'Erro ao processar login');
}
?>