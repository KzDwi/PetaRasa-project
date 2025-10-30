<?php
include 'koneksi.php';

// Buat tabel users
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    photo VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Tabel users berhasil dibuat<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Buat tabel restaurants (untuk sistem PeRaSa)
$sql_restaurants = "CREATE TABLE IF NOT EXISTS restaurants (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    address TEXT NOT NULL,
    city VARCHAR(100) NOT NULL DEFAULT 'Samarinda',
    category VARCHAR(100) NOT NULL,
    price_range VARCHAR(10) NOT NULL,
    rating DECIMAL(3,2) DEFAULT 0.00,
    phone VARCHAR(20),
    website VARCHAR(255),
    photo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_restaurants) === TRUE) {
    echo "Tabel restaurants berhasil dibuat<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

$conn->close();
?>