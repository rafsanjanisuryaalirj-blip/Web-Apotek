<?php
/**
 * Database Configuration File
 * Database: db_apotek
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_apotek');

// Website Configuration
define('SITE_NAME', 'Go Healty');
define('SITE_URL', 'http://localhost/Apotek_Kelompok_3/');
define('SITE_PATH', dirname(dirname(__FILE__)) . '/');

// Create Database Connection
try {
   $conn = new mysqli("localhost", "root", "", "db_apotek");
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to UTF-8
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}

// Session Configuration
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to check if user is admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Function to redirect to login if not authenticated
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: " . SITE_URL . "pages/login.php");
        exit();
    }
}

// Function to redirect if not admin
function requireAdmin() {
    if (!isAdmin()) {
        header("Location: " . SITE_URL);
        exit();
    }
}

// Function to require customer role (not admin)
function requireCustomer() {
    if (!isLoggedIn()) {
        header("Location: " . SITE_URL . "pages/login-gateway.php");
        exit();
    }
    if (isAdmin()) {
        header("Location: " . SITE_URL . "admin/");
        exit();
    }
}

// Function to validate order ownership (customer only)
function validateOrderOwnership($order_id, $user_id) {
    global $conn;
    $result = $conn->query("SELECT user_id FROM orders WHERE order_id = $order_id");
    if ($result->num_rows === 0) {
        return false;
    }
    $order = $result->fetch_assoc();
    return $order['user_id'] == $user_id;
}

// Function to sanitize input
function sanitize($data) {
    global $conn;
    return $conn->real_escape_string(htmlspecialchars(trim($data)));
}

// Function to format currency
function formatCurrency($amount) {
    return "Rp " . number_format($amount, 0, ',', '.');
}

// Function to get error message
function showError($message) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
    echo $message;
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
}

// Function to get success message
function showSuccess($message) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
    echo $message;
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
}
?>
