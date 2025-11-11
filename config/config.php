<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database credentials
$host = 'localhost';
$dbname = 'clientfilingindiadb';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

