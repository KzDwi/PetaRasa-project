<?php
session_start();
require_once 'config.php';

// Redirect ke login jika belum login
if(!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();
$restaurant = new Restaurant($db);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($id > 0) {
    $restaurant->id = $id;
    
    // Get restaurant name for message
    if($restaurant->readOne()) {
        $restaurant_name = $restaurant->name;
        
        // Delete the restaurant
        if($restaurant->delete()) {
            showMessage('success', "✅ Restoran '{$restaurant_name}' berhasil dihapus!");
        } else {
            showMessage('error', "❌ Gagal menghapus restoran '{$restaurant_name}'!");
        }
    } else {
        showMessage('error', '❌ Restoran tidak ditemukan!');
    }
} else {
    showMessage('error', '❌ ID restoran tidak valid!');
}

redirect('crud_index.php');
?>