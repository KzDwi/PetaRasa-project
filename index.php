<?php
session_start();
require_once 'config.php';

// Ambil data restoran untuk ditampilkan di homepage
$database = new Database();
$db = $database->getConnection();
$restaurant = new Restaurant($db);

// Ambil 6 restoran terbaru untuk rekomendasi
$stmt = $restaurant->readAll(1, 6, '');
$recent_restaurants = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $recent_restaurants[] = $row;
}

// Ambil data untuk ulasan terbaru (dummy data untuk sekarang)
$recent_reviews = [
    [
        'restaurant' => "Mie Ayam Kacang",
        'reviewer' => "Budi Santoso",
        'rating' => 4,
        'comment' => "Porsinya sangat besar, ayamnya juga banyak, harganya juga sangat murah. Cuma sedikit belum terbiasa buat makan mie ayam tanpa keripiknya yang diganti kacang.",
        'date' => "2025-08-25"
    ],
    [
        'restaurant' => "Kikitaru (Ex. Taberu)",
        'reviewer' => "Sari Wijaya", 
        'rating' => 5,
        'comment' => "Tempatnya sangat mirip seperti di jepang, ramen nya juga ga kalah enak sama resto sebelah.",
        'date' => "2025-08-24"
    ]
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PeRaSa - Peta Rasa Samarinda</title>
    <meta name="description" content="Temukan restoran terbaik, ulasan, dan promo kuliner di Samarinda">
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chela+One&display=swap" rel="stylesheet">
</head>
<body>
    <header class="text-center">
        <div class="header-title-logo">
            <img src="img/_PerasaLogo.png" alt="Logo PeRaSa" class="logo-small">
            <h1>PeRaSa - Peta Rasa Samarinda</h1>
        </div>
        <nav>
            <ul>
                <li id="nav-menu"><a href="index.php">Home</a></li>
                <li id="nav-menu"><a href="#restoran">Restoran</a></li>
                <li id="nav-menu"><a href="#kategori">Kategori</a></li>
                <li id="nav-menu"><a href="#ulasan">Ulasan</a></li>
                <li id="nav-menu"><a href="#tentang">Tentang Kami</a></li>
                <li id="nav-menu"><a href="#randomize">Terserah Aja Deh</a></li>
                <?php if(isset($_SESSION['username'])): ?>
                    <li id="nav-menu"><a href="dashboard.php">Dashboard</a></li>
                    <li id="nav-menu"><a href="crud_index.php">Manage Restoran</a></li>
                    <li id="nav-menu"><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li id="nav-menu"><a href="login.php">Masuk</a></li>
                    <li id="nav-menu"><a href="#register">Daftar</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <?php
        // Handle search query from URL
        $searchQuery = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
        $locationQuery = isset($_GET['location']) ? htmlspecialchars($_GET['location']) : '';
        ?>
        
        <section id="pencarian" class="card">
            <h2>Temukan Restoran Favoritmu</h2>
            <form class="flex" method="GET" action="index.php">
                <input type="text" name="search" placeholder="Cari restoran atau masakan..." 
                       aria-label="Cari restoran atau masakan" value="<?php echo $searchQuery; ?>">
                <input type="text" name="location" placeholder="Lokasi (kota atau area)" 
                       aria-label="Lokasi" value="<?php echo $locationQuery; ?>">
                <button type="submit">Cari</button>
            </form>
            
            <?php if($searchQuery || $locationQuery): ?>
                <div style="margin-top: 10px;">
                    <p>Hasil pencarian untuk: 
                        <?php 
                        if($searchQuery) echo "<strong>'$searchQuery'</strong> ";
                        if($locationQuery) echo "di <strong>'$locationQuery'</strong>";
                        ?>
                    </p>
                </div>
            <?php endif; ?>
        </section>

        <section id="kategori-populer" class="card">
            <h2>Kategori Populer</h2>
            <ul class="flex">
                <?php
                $categories = [
                    'makanan-indonesia' => 'Makanan Indonesia',
                    'camilan' => 'Camilan',
                    'makanan-jepang' => 'Makanan Jepang',
                    'makanan-korea' => 'Makanan Korea',
                    'kafe' => 'Kafe',
                    'street-food' => 'Street Food'
                ];
                
                $currentCategory = isset($_GET['category']) ? $_GET['category'] : '';
                
                foreach($categories as $key => $name): 
                    $isActive = $currentCategory === $key;
                ?>
                    <li<?php echo $isActive ? ' class="active"' : ''; ?>>
                        <a href="index.php?category=<?php echo $key; ?>"><?php echo $name; ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
            
            <?php if($currentCategory && isset($categories[$currentCategory])): ?>
                <div style="margin-top: 10px;">
                    <p>Menampilkan kategori: <strong><?php echo $categories[$currentCategory]; ?></strong></p>
                </div>
            <?php endif; ?>
        </section>

        <section id="rekomendasi-restoran" class="card">
            <h2>Rekomendasi Restoran Terbaru</h2>
            <div class="grid">
                <?php if(!empty($recent_restaurants)): ?>
                    <?php foreach($recent_restaurants as $restaurant): ?>
                        <article class="card">
                            <h3><?php echo $restaurant['name']; ?></h3>
                            <p class="address"><?php echo $restaurant['address'] ?: $restaurant['city']; ?></p>
                            <p class="category"><?php echo $restaurant['category']; ?> • <?php echo $restaurant['price_range']; ?></p>
                            <p class="rating">Rating: <?php echo $restaurant['rating']; ?>/5</p>
                            <div style="display: flex; gap: 10px; margin-top: 15px;">
                                <a href="crud_detail.php?id=<?php echo $restaurant['id']; ?>" class="detail-btn">Lihat Detail</a>
                                <?php if(isset($_SESSION['username'])): ?>
                                    <a href="crud_edit.php?id=<?php echo $restaurant['id']; ?>" class="detail-btn" style="background: var(--secondary-color); color: var(--text-color);">Edit</a>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-data" style="grid-column: 1 / -1; text-align: center; padding: 2rem;">
                        <p>Belum ada restoran yang terdaftar.</p>
                        <?php if(isset($_SESSION['username'])): ?>
                            <a href="crud_create.php" class="detail-btn">Tambah Restoran Pertama</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if(isset($_SESSION['username'])): ?>
                <div style="text-align: center; margin-top: 20px;">
                    <a href="crud_index.php" class="detail-btn">Lihat Semua Restoran</a>
                </div>
            <?php endif; ?>
        </section>

        <section id="ulasan-terbaru" class="card">
            <h2>Ulasan Terbaru</h2>
            <div class="grid">
                <?php foreach($recent_reviews as $review): ?>
                    <article class="card">
                        <h3><?php echo $review['restaurant']; ?></h3>
                        <p class="reviewer">Oleh: <?php echo $review['reviewer']; ?></p>
                        <p class="rating">Rating: <?php echo str_repeat('⭐', $review['rating']) . str_repeat('☆', 5 - $review['rating']); ?></p>
                        <p class="comment"><?php echo $review['comment']; ?></p>
                        <time datetime="<?php echo $review['date']; ?>"><?php echo date('d F Y', strtotime($review['date'])); ?></time>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section id="artikel-terbaru" class="card">
            <h2>Artikel Terbaru</h2>
            <div class="grid">
                <article class="card">
                    <h3>10 Tempat Makan yang Menjadi Penyelamat Anak kos di Samarinda</h3>
                    <p>Selain harga yang murah, rasa yang enak dan porsi yang banyak adalah tiga hal yang...</p>
                    <a href="#baca-artikel">Baca Selengkapnya</a>
                </article>
                <article class="card">
                    <h3>Menu Baru yang Wajib Dicoba Oktober Nanti</h3>
                    <p>Deretan menu dari tempat makan yang mungkin belum kamu tahu di Samarinda, apalagi....</p>
                    <a href="#baca-artikel">Baca Selengkapnya</a>
                </article>
            </div>
        </section>
    </main>

    <footer>
        <div class="flex" style="flex-wrap: wrap; justify-content: center; gap: 32px;">
            <section>
                <h2>Tentang PeRaSa</h2>
                <ul>
                    <li id="nav-menu"><a href="#tentang-kami">Tentang Kami</a></li>
                    <li id="nav-menu"><a href="#karir">Karir</a></li>
                    <li id="nav-menu"><a href="#blog">Blog</a></li>
                    <li id="nav-menu"><a href="#partner">Partner Kami</a></li>
                </ul>
            </section>
            <section>
                <h2>Bantuan</h2>
                <ul>
                    <li id="nav-menu"><a href="#faq">FAQ</a></li>
                    <li id="nav-menu"><a href="#syarat-ketentuan">Syarat & Ketentuan</a></li>
                    <li id="nav-menu"><a href="#kebijakan-privasi">Kebijakan Privasi</a></li>
                    <li id="nav-menu"><a href="#kontak">Kontak Kami</a></li>
                </ul>
            </section>
            <section class="Socials">
                <h2>Ikuti Kami</h2>
                <ul>
                    <li id="nav-menu"><a href="#facebook">Facebook</a></li>
                    <li id="nav-menu"><a href="#instagram">Instagram</a></li>
                    <li id="nav-menu"><a href="#twitter">Twitter</a></li>
                </ul>
            </section>
            <br>
            <section>
                <h2>Referensi & Inspirasi Desain</h2>
                <ul>
                    <li id="nav-menu"><a href="https://www.pergikuliner.com" target="_blank">PergiKuliner.com</a> - Situs asli yang menjadi inspirasi</li>
                    <br>
                    <li id="nav-menu"><a href="" target="_blank">TripAdvisor Restaurants</a> - Situs ulasan Internasional</li>
                </ul>
            </section>
        </div>
        <p class="text-center">&copy; 2025 PeRaSa - Peta Rasa Samarinda</p>
    </footer>
    <script src="js/script.js"></script>
</body>
</html>