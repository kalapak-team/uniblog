<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'uniblog');
define('DB_USER', 'root');
define('DB_PASS', '');

// Create database connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Website configuration - UPDATE THIS LINE TO INCLUDE PORT 8080
define('SITE_NAME', 'UniBlog');
define('SITE_URL', 'http://localhost:8080/uniblog');
define('ADMIN_URL', SITE_URL . '/admin');

// Start session only if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>