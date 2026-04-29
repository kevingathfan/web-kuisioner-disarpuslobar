<?php
// config/database.php (Konfigurasi Localhost)

$host = 'localhost';
$port = '3306'; 
$dbname = 'monitoring_perpus_db'; 
$username = 'root'; 
$password = ''; 

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    error_log("DB Connection Failed: " . $e->getMessage());
    die("Koneksi Database Gagal. Silakan hubungi administrator.");
}
