<?php
// filepath: c:\Users\rodri_sgf12sh\TDE-Programacao-web\config\config.php
// Ajuste os valores abaixo com as credenciais do seu MySQL
$db_host = '127.0.0.1';
$db_name = 'web server';
$db_user = 'root';
$db_pass = '';
$dsn = "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (PDOException $e) {
    // não expor detalhes em produção
    http_response_code(500);
    echo "Erro de configuração do banco.";
    exit;
}
?>