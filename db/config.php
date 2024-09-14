<?php
// Cargar autoload para php dotenv
require_once __DIR__ . '/../vendor/autoload.php';

// Cargar las variables del archivo .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Conexión a la base de datos
$serverName = $_ENV['DB_HOST']; // Obtener las variables de entorno
$connectionOptions = array(
    "Database" => $_ENV['DB_NAME'],
    "Uid" => $_ENV['DB_USER'],
    "PWD" => $_ENV['DB_PASS'],
    "LoginTimeout" => 30,
    "Encrypt" => 1,
    "TrustServerCertificate" => 0
);

$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}
