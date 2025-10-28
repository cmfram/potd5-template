<?php
// connect-db.php — local XAMPP
$host     = 'localhost';
$dbname   = 'mvn5yd_d';
$username = '';
$password = '';

$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

try {
  $db = new PDO($dsn, $username, $password, [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
  ]);
  //echo "Connected"; 
} catch (PDOException $e) {
  echo "Connection failed: " . htmlspecialchars($e->getMessage());
  exit;
}
