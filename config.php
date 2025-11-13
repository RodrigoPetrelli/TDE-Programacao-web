<?php
// Forçar HTTPS
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('Location: ' . $redirect, true, 301);
    exit;
}

// Cabeçalhos de segurança
header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header("Referrer-Policy: no-referrer-when-downgrade");
header("Content-Security-Policy: default-src 'self'; script-src 'self'; object-src 'none';");

// Sessões seguras
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();

// Conexão PDO segura (ajuste conforme seu ambiente)
$dbHost = 'localhost';
$dbName = 'web server';
$dbUser = 'root';
$dbPass = '';

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro de conexão ao banco']);
    exit;
}
?>
