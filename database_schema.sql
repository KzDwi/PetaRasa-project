CREATE DATABASE IF NOT EXISTS perasa_db;
USE perasa_db;

CREATE TABLE IF NOT EXISTS restaurants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    address TEXT,
    city VARCHAR(100) NOT NULL,
    category VARCHAR(100) NOT NULL,
    price_range ENUM('$', '$$', '$$$', '$$$$') DEFAULT '$$',
    rating DECIMAL(2,1) DEFAULT 0.0,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample data
INSERT INTO restaurants (name, description, address, city, category, price_range, rating, image) VALUES
('Kikitaru (Ex. Taberu)', 'Restoran Jepang dengan suasana autentik', 'SCP Mall Lt. 2, Samarinda', 'Samarinda', 'Jepang', '$$$', 4.5, 'kikitaru.jpg'),
('Nasi Kuning Mbah', 'Nasi kuning legendaris dengan resep turun temurun', 'Jl. KS Tubun Gg.7, Samarinda', 'Samarinda', 'Indonesia', '$', 4.7, 'nasi_kuning.jpg'),
('Mie Ayam Kacang', 'Mie ayam dengan topping kacang khas', 'Jl. Kecapi, Dadi Mulya, Samarinda', 'Samarinda', 'Street Food', '$', 4.6, 'mie_ayam.jpg'),
('Warung Padang Sederhana', 'Masakan Padang asli dengan rasa otentik', 'Jl. Awang Long No. 45, Samarinda', 'Samarinda', 'Indonesia', '$$', 4.3, 'padang.jpg'),
('Kopi Teman Sejati', 'Kafe cozy dengan kopi specialty lokal', 'Jl. Bhayangkara No. 12, Samarinda', 'Samarinda', 'Kafe', '$$', 4.4, 'kopi_teman.jpg'),
('Seoul Garden', 'Restoran Korea dengan BBQ meja', 'Samarinda Central Plaza', 'Samarinda', 'Korea', '$$$', 4.2, 'seoul_garden.jpg');