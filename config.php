<?php
// session_start();

// Konfigurasi base URL
define('BASE_URL', 'http://perasa-project.test/');

// Include koneksi database
require_once 'koneksi.php';

// Include model Restaurant
require_once 'models/Restaurant.php';

// Helper functions
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    header("Location: " . $url);
    exit();
}

function showMessage($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
}

// Tambahkan fungsi ini di config.php
function formatDate($dateString, $format = 'd F Y') {
    return date($format, strtotime($dateString));
}

function formatDateTime($dateString, $format = 'd F Y H:i') {
    return date($format, strtotime($dateString));
}

function displayMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        $alertClass = $message['type'] === 'success' ? 'alert-success' : 'alert-danger';
        echo "<div class='alert $alertClass'>" . $message['message'] . "</div>";
        unset($_SESSION['flash_message']);
    }
}
?>