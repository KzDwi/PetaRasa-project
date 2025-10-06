<?php
session_start();

// Redirect ke login jika belum login
if(!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PeRaSa</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chela+One&display=swap" rel="stylesheet">
    <style>
        .dashboard-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .welcome-section {
            text-align: center;
            margin-bottom: 2rem;
            padding: 2rem;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            border-radius: var(--border-radius);
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: var(--border-radius);
            text-align: center;
            box-shadow: var(--shadow);
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
        }
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
    <header class="text-center">
        <div class="header-title-logo">
            <img src="img/_PerasaLogo.png" alt="Logo PeRaSa" class="logo-small">
            <h1>Dashboard - PeRaSa</h1>
        </div>
        <nav>
            <ul>
                <li id="nav-menu"><a href="index.php">Home</a></li>
                <li id="nav-menu"><a href="#profile">Profile</a></li>
                <li id="nav-menu"><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="dashboard-container">
            <section class="welcome-section">
                <h2>Selamat Datang, <?php echo htmlspecialchars($username); ?>! 👋</h2>
                <p>Ini adalah halaman dashboard Anda di PeRaSa</p>
            </section>

            <section class="card">
                <h2>Statistik Anda</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number">12</div>
                        <div>Ulasan Ditulis</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">8</div>
                        <div>Restoran Disukai</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">24</div>
                        <div>Foto Diunggah</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">3</div>
                        <div>Artikel Ditulis</div>
                    </div>
                </div>
            </section>

            <section class="card">
                <h2>Aksi Cepat</h2>
                <div class="action-buttons">
                    <button onclick="location.href='index.php?search='" class="detail-btn">
                        Tulis Ulasan Baru
                    </button>
                    <button onclick="location.href='index.php'" class="detail-btn">
                        Jelajahi Restoran
                    </button>
                    <button onclick="location.href='#profile'" class="detail-btn">
                        Edit Profile
                    </button>
                    <button onclick="location.href='logout.php'" class="detail-btn" 
                            style="background: #ff6b6b;">
                        Logout
                    </button>
                </div>
            </section>

            <section class="card">
                <h2>Aktivitas Terbaru</h2>
                <div class="grid">
                    <article class="card">
                        <h3>Ulasan di Mie Ayam Kacang</h3>
                        <p>Rating: 4/5</p>
                        <p>"Porsinya besar dan harganya terjangkau..."</p>
                        <time datetime="2025-08-25">25 Agustus 2025</time>
                    </article>
                    <article class="card">
                        <h3>Like Restoran Kikitaru</h3>
                        <p>Anda menyukai restoran ini</p>
                        <time datetime="2025-08-24">24 Agustus 2025</time>
                    </article>
                </div>
            </section>
        </div>
    </main>

    <footer>
        <p class="text-center">&copy; 2025 PeRaSa - Peta Rasa Samarinda | 
           Login sebagai: <strong><?php echo htmlspecialchars($username); ?></strong>
        </p>
    </footer>
</body>
</html>