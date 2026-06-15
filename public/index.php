<?php
session_start();

// Define constants for path
define('BASE_PATH', dirname(__DIR__) . '/');
// Dynamic BASE_URL detection
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptName = dirname($_SERVER['SCRIPT_NAME'] ?? '');
$scriptName = str_replace('\\', '/', $scriptName);
$projectUrl = $protocol . $host . str_replace('/public', '', $scriptName);
define('BASE_URL', rtrim($projectUrl, '/'));

// Detect if rewrite is active (no index.php in REQUEST_URI)
if (strpos($_SERVER['REQUEST_URI'] ?? '', 'index.php') !== false) {
    define('USE_REWRITE', false);
} else {
    define('USE_REWRITE', true);
}

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
