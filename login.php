<?php
require_once 'config.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !$password) {
    echo json_encode(['success' => false, 'message' => 'E-mail ou senha inválidos']);
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT id, name, password_hash FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        echo json_encode(['success' => false, 'message' => 'Credenciais incorretas']);
        exit;
    }

    // Sessão ativa
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];

    echo json_encode([
        'success' => true,
        'user_id' => $user['id'],
        'user_name' => $user['name']
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro no servidor']);
}
?>
