<?php
session_start();

// Redirect ke login jika belum login
if(!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

require_once 'config.php';

$database = new Database();
$db = $database->getConnection();
$restaurant = new Restaurant($db);

// Hitung statistik
$total_restaurants = $restaurant->countAll();
$recent_restaurants = $restaurant->readAll(1, 3, '');
$recent_count = $recent_restaurants->rowCount();

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
            max-width: 1000px;
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
            border: 1.5px solid var(--secondary-color);
            transition: transform var(--transition), box-shadow var(--transition);
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(255,127,80,0.15);
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
        }
        .stat-label {
            color: var(--muted-color);
            font-size: 0.9rem;
        }
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin: 2rem 0;
        }
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }
        .quick-action-card {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: var(--border-radius);
            text-align: center;
            border: 2px dashed var(--accent-color);
            transition: all var(--transition);
            cursor: pointer;
        }
        .quick-action-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-3px);
        }
        .quick-action-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        
        /* Dark mode specific styles untuk dashboard */
        .dark-mode .welcome-section {
            background: linear-gradient(135deg, #2d2d2d, #43aa8b);
        }
        .dark-mode .stat-card {
            border-color: #555;
        }
        .dark-mode .stat-number {
            color: var(--secondary-color);
        }
        .dark-mode .quick-action-card {
            border-color: #555;
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
                <li id="nav-menu"><a href="crud_index.php">Manage Restoran</a></li>
                <li id="nav-menu"><a href="crud_create.php">Tambah Restoran</a></li>
                <li id="nav-menu"><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="dashboard-container">   
            <section class="welcome-section">
                <h2 class="text-outline">Selamat Datang, <?php echo htmlspecialchars($username); ?>! 👋</h2>
                <p style="margin-left: -2.1rem;">Kelola data restoran dan ulasan di PeRaSa</p>
            </section>

            <section class="card">
                <h2>Statistik Anda</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $total_restaurants; ?></div>
                        <div class="stat-label">Total Restoran</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $recent_count; ?></div>
                        <div class="stat-label">Restoran Baru</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">24</div>
                        <div class="stat-label">Foto Diunggah</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">3</div>
                        <div class="stat-label">Artikel Ditulis</div>
                    </div>
                </div>
            </section>

            <section class="card">
                <h2>Quick Actions</h2>
                <div class="quick-actions">
                    <div class="quick-action-card" onclick="location.href='crud_create.php'">
                        <div class="quick-action-icon">🍽️</div>
                        <h3>Tambah Restoran</h3>
                        <p>Tambah restoran baru ke database</p>
                    </div>
                    <div class="quick-action-card" onclick="location.href='crud_index.php'">
                        <div class="quick-action-icon">📋</div>
                        <h3>Kelola Restoran</h3>
                        <p>Lihat dan edit semua restoran</p>
                    </div>
                    <div class="quick-action-card" onclick="location.href='index.php'">
                        <div class="quick-action-icon">🏠</div>
                        <h3>Lihat Homepage</h3>
                        <p>Kunjungi halaman utama</p>
                    </div>
                </div>
            </section>

            <section class="card">
                <h2>Aksi Cepat</h2>
                <div class="action-buttons">
                    <button onclick="location.href='crud_create.php'" class="detail-btn">
                        🍽️ Tambah Restoran
                    </button>
                    <button onclick="location.href='crud_index.php'" class="detail-btn">
                        📋 Kelola Restoran
                    </button>
                    <button onclick="location.href='index.php'" class="detail-btn">
                        🏠 Ke Homepage
                    </button>
                    <button onclick="location.href='logout.php'" class="detail-btn" 
                            style="background: #ff6b6b;">
                        🚪 Logout
                    </button>
                </div>
            </section>

            <?php if($recent_count > 0): ?>
            <section class="card">
                <h2>Restoran Terbaru</h2>
                <div class="grid">
                    <?php while ($row = $recent_restaurants->fetch(PDO::FETCH_ASSOC)): ?>
                        <article class="card">
                            <h3><?php echo $row['name']; ?></h3>
                            <p><?php echo $row['category']; ?> • <?php echo $row['price_range']; ?></p>
                            <p>Rating: <?php echo $row['rating']; ?>/5</p>
                            <div style="display: flex; gap: 10px; margin-top: 15px;">
                                <a href="crud_detail.php?id=<?php echo $row['id']; ?>" class="detail-btn">Lihat</a>
                                <a href="crud_edit.php?id=<?php echo $row['id']; ?>" class="detail-btn" style="background: var(--secondary-color); color: var(--text-color);">Edit</a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
                <div style="text-align: center; margin-top: 20px;">
                    <a href="crud_index.php" class="detail-btn">Lihat Semua Restoran</a>
                </div>
            </section>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p class="text-center">&copy; 2025 PeRaSa - Peta Rasa Samarinda | 
           Login sebagai: <strong><?php echo htmlspecialchars($username); ?></strong>
        </p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>