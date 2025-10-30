<?php
session_start();
require_once 'config.php';

$database = new Database();
$db = $database->getConnection();
$restaurant = new Restaurant($db);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get restaurant data
$restaurant->id = $id;
if(!$restaurant->readOne()) {
    showMessage('error', 'Restoran tidak ditemukan!');
    redirect('crud_index.php');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $restaurant->name; ?> - Detail Restoran - PeRaSa</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chela+One&display=swap" rel="stylesheet">
    <style>
        .restaurant-detail {
            max-width: 900px;
            margin: 0 auto;
        }
        .restaurant-header {
            text-align: center;
            margin-bottom: 2rem;
            padding: 2rem;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            border-radius: var(--border-radius);
        }
        .restaurant-header h1 {
            margin: 0 0 1rem 0;
            font-size: 2.5rem;
        }
        .category-price {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            opacity: 0.9;
        }
        .restaurant-info {
            display: grid;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .info-group {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: var(--border-radius);
            border-left: 4px solid var(--primary-color);
            box-shadow: var(--shadow);
        }
        .info-group h3 {
            margin-top: 0;
            color: var(--primary-color);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }
        .detail-card {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: var(--border-radius);
            text-align: center;
            border: 2px solid var(--secondary-color);
        }
        .detail-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        .detail-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-color);
            display: block;
        }
        .detail-label {
            color: var(--muted-color);
            font-size: 0.9rem;
        }
        .restaurant-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: var(--border-radius);
            margin: 1rem 0;
        }
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
            flex-wrap: wrap;
        }
        .metadata {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid var(--secondary-color);
            color: var(--muted-color);
            font-size: 0.9rem;
        }
        
        /* Dark mode adjustments */
        .dark-mode .restaurant-header {
            background: linear-gradient(135deg, #2d2d2d, #43aa8b);
        }
        .dark-mode .info-group {
            border-left-color: var(--accent-color);
        }
    </style>
</head>
<body>
    <header class="text-center">
        <div class="header-title-logo">
            <img src="img/_PerasaLogo.png" alt="Logo PeRaSa" class="logo-small">
            <h1>Detail Restoran - PeRaSa</h1>
        </div>
        <nav>
            <ul>
                <li id="nav-menu"><a href="index.php">Home</a></li>
                <li id="nav-menu"><a href="crud_index.php">Manage Restoran</a></li>
                <?php if(isset($_SESSION['username'])): ?>
                    <li id="nav-menu"><a href="crud_create.php">Tambah Restoran</a></li>
                    <li id="nav-menu"><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li id="nav-menu"><a href="login.php">Masuk</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <div class="restaurant-detail">
            <?php displayMessage(); ?>
            
            <section class="restaurant-header">
                <h1><?php echo $restaurant->name; ?></h1>
                <p class="category-price"><?php echo $restaurant->category; ?> • <?php echo $restaurant->price_range; ?></p>
                <div class="rating-stars" style="font-size: 1.5rem; margin: 10px 0;">
                    ⭐ <?php echo $restaurant->rating; ?>/5
                </div>
            </section>

            <section class="card">
                <?php if($restaurant->image): ?>
                <div style="text-align: center; margin-bottom: 2rem;">
                    <img src="<?php echo $restaurant->image; ?>" 
                         alt="<?php echo $restaurant->name; ?>" 
                         class="restaurant-image"
                         onerror="this.style.display='none'">
                </div>
                <?php endif; ?>

                <div class="detail-grid">
                    <div class="detail-card">
                        <div class="detail-icon">🍽️</div>
                        <span class="detail-value"><?php echo $restaurant->category; ?></span>
                        <span class="detail-label">Kategori</span>
                    </div>
                    
                    <div class="detail-card">
                        <div class="detail-icon">💰</div>
                        <span class="detail-value"><?php echo $restaurant->price_range; ?></span>
                        <span class="detail-label">Kisaran Harga</span>
                    </div>
                    
                    <div class="detail-card">
                        <div class="detail-icon">⭐</div>
                        <span class="detail-value"><?php echo $restaurant->rating; ?>/5</span>
                        <span class="detail-label">Rating</span>
                    </div>
                    
                    <div class="detail-card">
                        <div class="detail-icon">🏙️</div>
                        <span class="detail-value"><?php echo $restaurant->city; ?></span>
                        <span class="detail-label">Kota</span>
                    </div>
                </div>

                <div class="restaurant-info">
                    <div class="info-group">
                        <h3>📍 Informasi Lokasi</h3>
                        <p><strong>Alamat Lengkap:</strong><br>
                           <?php echo $restaurant->address ? nl2br($restaurant->address) : '<em>Alamat belum tersedia</em>'; ?>
                        </p>
                        <p><strong>Kota:</strong> <?php echo $restaurant->city; ?></p>
                    </div>

                    <div class="info-group">
                        <h3>📝 Deskripsi Restoran</h3>
                        <p><?php echo $restaurant->description ? nl2br($restaurant->description) : '<em>Deskripsi belum tersedia</em>'; ?></p>
                    </div>

                    <div class="info-group">
                        <h3>💰 Detail Harga</h3>
                        <p><strong>Kisaran Harga:</strong> 
                            <span class="price-badge" style="font-size: 1rem;"><?php echo $restaurant->price_range; ?></span>
                        </p>
                        <?php
                        $price_ranges = [
                            '$' => '💵 Murah (di bawah Rp 50.000)',
                            '$$' => '💵💵 Sedang (Rp 50.000 - Rp 150.000)',
                            '$$$' => '💵💵💵 Mahal (Rp 150.000 - Rp 300.000)',
                            '$$$$' => '💵💵💵💵 Sangat Mahal (di atas Rp 300.000)'
                        ];
                        ?>
                        <p><?php echo $price_ranges[$restaurant->price_range] ?? 'Tidak tersedia'; ?></p>
                    </div>
                </div>

                <div class="metadata">
                    <div>
                        <strong>Dibuat:</strong> <?php echo date('d F Y H:i', strtotime($restaurant->created_at)); ?>
                    </div>
                    <div>
                        <strong>Terakhir Update:</strong> <?php echo date('d F Y H:i', strtotime($restaurant->updated_at)); ?>
                    </div>
                </div>

                <div class="action-buttons">
                    <a href="crud_index.php" class="btn btn-secondary">📋 Kembali ke Daftar</a>
                    <?php if(isset($_SESSION['username'])): ?>
                        <a href="crud_edit.php?id=<?php echo $id; ?>" class="btn btn-primary">✏️ Edit Restoran</a>
                        <a href="crud_delete.php?id=<?php echo $id; ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('Yakin ingin menghapus <?php echo addslashes($restaurant->name); ?>?')">🗑️ Hapus Restoran</a>
                    <?php endif; ?>
                    <a href="index.php" class="btn btn-success">🏠 Ke Homepage</a>
                </div>
            </section>
        </div>
    </main>

    <footer>
        <p class="text-center">&copy; 2025 PeRaSa - Peta Rasa Samarinda</p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>