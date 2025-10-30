<?php
include 'koneksi.php';

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Hapus file foto jika ada
    $sqlSelect = "SELECT photo FROM users WHERE id=$id";
    $resultSelect = $conn->query($sqlSelect);
    if ($resultSelect->num_rows > 0) {
        $row = $resultSelect->fetch_assoc();
        if (!empty($row['photo']) && file_exists($row['photo'])) {
            unlink($row['photo']);
        }
    }
    
    // Hapus data dari database
    $sql = "DELETE FROM users WHERE id=$id";
    $result = $conn->query($sql);
    
    if ($result) {
        $message = "Data berhasil dihapus";
        $type = "success";
    } else {
        $message = "Error hapus data: " . $conn->error;
        $type = "error";
    }
} else {
    $message = "ID tidak valid";
    $type = "error";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus User - PeRaSa</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .message-container {
            max-width: 600px;
            margin: 100px auto;
            padding: 40px;
            text-align: center;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .btn {
            padding: 10px 20px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header class="text-center">
        <div class="header-title-logo">
            <img src="img/_PerasaLogo.png" alt="Logo PeRaSa" class="logo-small">
            <h1>Hapus User - PeRaSa</h1>
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
        <div class="message-container card <?php echo $type; ?>">
            <h2><?php echo $type == 'success' ? 'Berhasil!' : 'Error!'; ?></h2>
            <p><?php echo $message; ?></p>
            <a href="index_users.php" class="btn">Kembali ke Daftar Users</a>
        </div>
    </main>

    <footer>
        <p class="text-center">&copy; 2025 PeRaSa - Peta Rasa Samarinda</p>
    </footer>
</body>
</html>