<?php
session_start();

// Define constants for path
define('BASE_PATH', dirname(__DIR__) . '/');
define('BASE_URL', 'http://localhost/web-perpus-lobar'); // Ubah jika nama folder berbeda

// Require database if needed globally or just rely on autoloader/Core
require_once BASE_PATH . 'config/database.php';

// Simple autoloader for Core, Controllers, Models
spl_autoload_register(function ($class) {
    $classPath = str_replace('\\', '/', $class);
    $file = BASE_PATH . 'app/' . $classPath . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Jalankan App
$app = new \Core\App();
