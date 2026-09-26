<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Dotenv\Dotenv;

$envFile = __DIR__ . '/../.env';

if (file_exists($envFile)) {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
}

$host = $_ENV['DB_HOST'] ?? getenv('DB_HOST');
$port = $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?: 5432;
$dbname = $_ENV['DB_NAME'] ?? getenv('DB_NAME');
$username = $_ENV['DB_USER'] ?? getenv('DB_USER');
$password = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD');

$dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";

$pdo = new PDO($dsn, $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);