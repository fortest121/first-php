<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load .env variables
if (file_exists(__DIR__ . "/../.env")) {
    $env = parse_ini_file(__DIR__ . "/../.env");
} else {
    $env = $_ENV; // used in Wasmer production
}

// Assign variables
$host = $env['DB_HOST'];
$dbname = $env['DB_NAME'];
$username = $env['DB_USERNAME'];
$password = $env['DB_PASSWORD'];
$port = $env['DB_PORT'];

// Connect
try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
