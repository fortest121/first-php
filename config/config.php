<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Local .env for localhost
if (file_exists(__DIR__ . "/../.env")) {
    // LOAD LOCAL ENV
    $env = parse_ini_file(__DIR__ . "/../.env");

    $host = $env['DB_HOST'];
    $dbname = $env['DB_NAME'];
    $username = $env['DB_USERNAME'];
    $password = $env['DB_PASSWORD'];
    $port = $env['DB_PORT'];

} else {
    // WASMER ENV (uses getenv)
    $host = getenv("DB_HOST");
    $dbname = getenv("DB_NAME");
    $username = getenv("DB_USERNAME");
    $password = getenv("DB_PASSWORD");
    $port = getenv("DB_PORT");
}

// Validate environment
if (!$host || !$dbname || !$username || !$port) {
    die("Missing required environment variables.");
}

try {
    $conn = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // echo "Database connected"; // optional

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
    