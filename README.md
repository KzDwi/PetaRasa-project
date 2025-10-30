# 🍽️ PeRaSa - Peta Rasa Samarinda

<div align="center">

![PeRaSa Banner](https://github.com/KzDwi/PetaRasa-project/blob/main/img/PerasaLogo.png)

**Platform Review Restoran Modern untuk Kota Samarinda**

[![PHP Version](https://img.shields.io/badge/PHP-8.0+-purple.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-blue.svg)](https://mysql.com)
[![Laragon](https://img.shields.io/badge/Local-Server-green.svg)](https://laragon.org)

</div>

## 📖 Tentang Proyek

PeRaSa (Peta Rasa Samarinda) adalah website review restoran modern yang dikhususkan untuk kota Samarinda. Aplikasi ini memungkinkan pengguna untuk menemukan, mengeksplorasi, dan memberikan ulasan terhadap berbagai restoran di Samarinda dengan antarmuka yang menarik dan user-friendly.

### ✨ Fitur Utama

| Fitur | Deskripsi | Status |
|-------|-----------|---------|
| 🏠 **Homepage Interaktif** | Tampilan utama dengan rekomendasi restoran terbaru | ✅ |
| 👥 **Sistem Autentikasi** | Login/Logout dengan session management | ✅ |
| 📊 **Dashboard Admin** | Panel kontrol untuk mengelola restoran | ✅ |
| 🍽️ **CRUD Restoran** | Create, Read, Update, Delete data restoran | ✅ |
| 🔍 **Pencarian & Filter** | Cari restoran berdasarkan nama, kategori, lokasi | ✅ |
| 📱 **Responsive Design** | Tampilan optimal di semua device | ✅ |
| 🌙 **Dark/Light Mode** | Toggle tema gelap/terang | ✅ |
| 🎨 **Modern UI/UX** | Desain menarik dengan animasi smooth | ✅ |

## 🛠️ Kebutuhan Sistem

### Server Requirements
- **PHP** ≥ 8.0
- **MySQL** ≥ 8.0 atau **MariaDB** ≥ 10.4
- **Web Server** Apache/Nginx (disarankan Laragon)
- **Ekstensi PHP**: PDO, MySQLi, Session, MBString

### Client Requirements
- Browser modern (Chrome, Firefox, Safari, Edge)
- JavaScript enabled
- Responsive screen (mobile/tablet/desktop)

## 🚀 Instalasi & Konfigurasi

### 1. Persiapan Environment

```bash
# Clone atau download project
git https://github.com/KzDwi/PetaRasa-project.git
cd PetaRasa-project
```

### 2. Setup dengan Laragon (Recommended)

1. **Download dan Install Laragon**
   - Download dari [laragon.org](https://laragon.org/download/)
   - Install dengan pilihan full package

2. **Setup Project**
   ```bash
   # Copy folder project ke C:\laragon\www\
   C:\laragon\www\perasa-project\
   ```

3. **Jalankan Laragon**
   - Buka Laragon
   - Klik "Start All" (Apache & MySQL)
   - Icon system tray akan berwarna hijau

### 3. Setup Database

1. **Akses phpMyAdmin**
   - Buka: `http://localhost/phpmyadmin`
   - atau melalui Laragon: Right-click → "Database" → "phpMyAdmin"

2. **Import Database Manual**
   ```sql
   CREATE DATABASE perasa_db;
   USE perasa_db;
   
   CREATE TABLE restaurants (
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
   ```

3. **Atau Gunakan Setup Otomatis**
   - Buka: `http://perasa-project.test/setup_database.php`
   - Database dan sample data akan dibuat otomatis

### 4. Konfigurasi Aplikasi

Edit file `koneksi.php` jika diperlukan:

```php
<?php
class Database {
    private $host = 'localhost';      // Server database
    private $db_name = 'perasa_db';   // Nama database
    private $username = 'root';       // Username database
    private $password = '';           // Password database
    // ... 
}
?>
```

## 📁 Struktur Folder

```
perasa-project/
├── 📂 models/                 # Model data
│   └── Restaurant.php         # Model restoran
├── 📂 css/                    # Stylesheet
│   └── style.css              # Main stylesheet
├── 📂 js/                     # JavaScript
│   └── script.js              # Main JavaScript
├── 📂 img/                    # Gambar assets
│   └── _PerasaLogo.png        # Logo aplikasi
├── 📄 index.php               # Homepage
├── 📄 login.php               # Halaman login
├── 📄 dashboard.php           # Dashboard admin
├── 📄 logout.php              # Logout handler
├── 📄 config.php              # Konfigurasi aplikasi
├── 📄 koneksi.php             # Koneksi database
├── 📄 setup_database.php      # Database setup
├── 📄 crud_index.php          # Kelola restoran
├── 📄 crud_create.php         # Tambah restoran
├── 📄 crud_edit.php           # Edit restoran
├── 📄 crud_detail.php         # Detail restoran
├── 📄 crud_delete.php         # Hapus restoran
└── 📄 README.md               # Dokumentasi ini
```

## 🌐 Akses Aplikasi

Setelah setup selesai, akses aplikasi melalui:

### URL Utama
- **Homepage**: `http://localhost/PetaRasa-project`
- **Admin Panel**: `http://PetaRasa-project/dashboard.php`

### Default Login
```
Username: admin
Password: admin123
```

## 🎯 Cara Penggunaan

### Untuk Pengunjung (Guest)
1. **Browse Restoran**: Jelajahi restoran di homepage
2. **Cari Restoran**: Gunakan fitur pencarian di header
3. **Lihat Detail**: Klik restoran untuk melihat detail lengkap
4. **Filter Kategori**: Gunakan kategori populer untuk filtering

### Untuk Admin
1. **Login**: Masuk dengan kredensial admin
2. **Dashboard**: Lihat statistik dan quick actions
3. **Kelola Restoran**: 
   - Tambah restoran baru
   - Edit informasi restoran
   - Hapus restoran
   - Lihat detail restoran
4. **Logout**: Keluar dari sistem

## 🎨 Fitur Khusus

### 🌙 Dark Mode
- Toggle button di pojok kanan bawah
- Preferences disimpan di localStorage
- Konsisten di semua halaman

### 📱 Responsive Design
- Mobile-first approach
- Optimal di semua screen size
- Touch-friendly interface

### ⚡ Performance
- PDO untuk keamanan database
- Prepared statements anti SQL injection
- Sanitasi input data
- Session management yang aman

## 🔧 Troubleshooting

### Common Issues

1. **Database Connection Error**
   ```bash
   # Pastikan MySQL running
   # Cek kredensial di koneksi.php
   # Pastikan database 'perasa_db' exists
   ```

2. **Session Start Error**
   ```php
   // Pastikan hanya satu session_start() per file
   // Hapus session_start() dari config.php
   ```

3. **Page Not Found (404)**
   ```bash
   # Pastikan mod_rewrite enabled
   # Cek virtual host Laragon
   # Akses via http://perasa-project.test
   ```

### Debug Mode

Untuk development, tambahkan di `config.php`:

```php
// Debug mode
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## 🤝 Kontribusi

Ingin berkontribusi? Silakan:

1. Fork project ini
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 🙏 Acknowledgments

- Inspirasi design: [PergiKuliner](https://pergikuliner.com)
- Icons: [Emoji](https://emojipedia.org)
- Font: [Google Fonts - Chela One](https://fonts.google.com)
- Local Server: [Laragon](https://laragon.org)

---

<div align="center">

**⭐ Jangan lupa beri bintang jika project ini membantu!**

Dibuat dengan ❤️ untuk Samarinda

</div>