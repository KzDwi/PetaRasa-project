<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    
    // Handle file upload
    $fileName = $_FILES['file_upload']['name'];
    $fileTmpName = $_FILES['file_upload']['tmp_name'];
    $uploadDir = "uploads/";

    // Buat folder uploads jika belum ada
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (move_uploaded_file($fileTmpName, $uploadDir . $fileName)) {
        $sql = "INSERT INTO users (nama, email, photo) VALUES ('$nama', '$email', '" . $uploadDir . $fileName . "')";
        $result = $conn->query($sql);
        if ($result) {
            echo "<div style='color: green; padding: 10px;'>Data berhasil ditambahkan</div>";
        } else {
            echo "<div style='color: red; padding: 10px;'>Error: " . $sql . "<br>" . $conn->error . "</div>";
        }
    } else {
        echo "<div style='color: red; padding: 10px;'>Gagal mengunggah file.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User - PeRaSa</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }
        .btn {
            padding: 10px 20px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
        }
        .btn:hover {
            background: var(--accent-color);
        }
    </style>
</head>
<body>
    <header class="text-center">
        <div class="header-title-logo">
            <img src="img/_PerasaLogo.png" alt="Logo PeRaSa" class="logo-small">
            <h1>Tambah User Baru - PeRaSa</h1>
        </div>
        <nav>
            <ul>
                <li id="nav-menu"><a href="index.php">Home</a></li>
                <li id="nav-menu"><a href="index_users.php">Data Users</a></li>
                <li id="nav-menu"><a href="create.php">Tambah User</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="form-container card">
            <h2>Tambah User Baru</h2>
            
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nama">Nama:</label>
                    <input type="text" id="nama" name="nama" placeholder="Masukkan nama" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan email" required>
                </div>
                
                <div class="form-group">
                    <label for="file_upload">Foto:</label>
                    <input type="file" id="file_upload" name="file_upload" required>
                </div>
                
                <button type="submit" class="btn">Submit</button>
                <a href="index_users.php" style="margin-left: 10px;">Kembali ke Daftar</a>
            </form>
        </div>
    </main>

    <footer>
        <p class="text-center">&copy; 2025 PeRaSa - Peta Rasa Samarinda</p>
    </footer>
</body>
</html>