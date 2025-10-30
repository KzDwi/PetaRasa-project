<?php
require_once 'config.php';

$database = new Database();
$db = $database->getConnection();
$restaurant = new Restaurant($db);

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if($page < 1) $page = 1;

$records_per_page = 5;
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';

// Read restaurants
try {
    $stmt = $restaurant->readAll($page, $records_per_page, $search);
    $total_records = $restaurant->countAll($search);
    $total_pages = ceil($total_records / $records_per_page);
} catch(Exception $e) {
    $error = "Terjadi kesalahan: " . $e->getMessage();
    $stmt = null;
    $total_records = 0;
    $total_pages = 0;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Restoran - PeRaSa</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chela+One&display=swap" rel="stylesheet">
    <style>
        .crud-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .search-form {
            display: flex;
            gap: 10px;
            flex: 1;
            min-width: 300px;
        }
        .search-form input {
            flex: 1;
        }
        .table-container {
            overflow-x: auto;
            margin-bottom: 2rem;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            background: var(--card-bg);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow);
        }
        .data-table th,
        .data-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--secondary-color);
        }
        .data-table th {
            background: var(--primary-color);
            color: white;
            font-weight: 600;
        }
        .data-table tr:hover {
            background: rgba(255, 209, 102, 0.1);
        }
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all var(--transition);
            font-family: "Chela One", system-ui;
            font-size: 0.9rem;
        }
        .btn-primary {
            background: var(--primary-color);
            color: white;
        }
        .btn-primary:hover {
            background: var(--accent-color);
            transform: translateY(-2px);
        }
        .btn-edit {
            background: var(--secondary-color);
            color: var(--text-color);
        }
        .btn-edit:hover {
            background: #ffc107;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        .btn-view {
            background: var(--accent-color);
            color: white;
        }
        .btn-view:hover {
            background: #388e3c;
        }
        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 2rem;
            flex-wrap: wrap;
        }
        .page-item {
            list-style: none;
        }
        .page-link {
            padding: 8px 16px;
            border: 1px solid var(--accent-color);
            border-radius: 6px;
            text-decoration: none;
            color: var(--text-color);
            transition: all var(--transition);
        }
        .page-link:hover,
        .page-item.active .page-link {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        .alert {
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 2rem;
            font-weight: 600;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .no-data {
            text-align: center;
            padding: 3rem;
            color: var(--muted-color);
        }
        .rating-stars {
            color: #ffc107;
            font-weight: bold;
        }
        .price-badge {
            background: var(--accent-color);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .database-error {
            text-align: center;
            padding: 2rem;
            background: #f8d7da;
            border-radius: 8px;
            margin: 2rem 0;
        }
        .database-error a {
            color: #721c24;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header class="text-center">
        <div class="header-title-logo">
            <img src="img/_PerasaLogo.png" alt="Logo PeRaSa" class="logo-small">
            <h1>Manage Restoran - PeRaSa</h1>
        </div>
        <nav>
            <ul>
                <li id="nav-menu"><a href="index.php">Home</a></li>
                <li id="nav-menu"><a href="crud_index.php">Manage Restoran</a></li>
                <li id="nav-menu"><a href="crud_create.php">Tambah Restoran</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="crud-container">
            <?php displayMessage(); ?>
            
            <?php if(isset($error)): ?>
                <div class="database-error">
                    <h3>⚠️ Database Error</h3>
                    <p><?php echo $error; ?></p>
                    <p>Silakan setup database terlebih dahulu: 
                       <a href="setup_database.php">Setup Database</a>
                    </p>
                </div>
            <?php else: ?>
            
            <div class="header-actions">
                <h2>Daftar Restoran</h2>
                <div class="search-form">
                    <form method="GET" action="crud_index.php" style="display: flex; gap: 10px; flex: 1;">
                        <input type="text" name="search" placeholder="Cari restoran..." 
                               value="<?php echo $search; ?>" style="flex: 1; padding: 10px;">
                        <button type="submit" class="btn btn-primary">Cari</button>
                        <?php if($search): ?>
                            <a href="crud_index.php" class="btn btn-edit">Reset</a>
                        <?php endif; ?>
                    </form>
                </div>
                <a href="crud_create.php" class="btn btn-primary">+ Tambah Restoran</a>
            </div>

            <div class="table-container">
                <?php if($stmt && $stmt->rowCount() > 0): ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Lokasi</th>
                                <th>Harga</th>
                                <th>Rating</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr>
                                    <td><strong><?php echo $row['name']; ?></strong></td>
                                    <td><?php echo $row['category']; ?></td>
                                    <td><?php echo $row['city']; ?></td>
                                    <td><span class="price-badge"><?php echo $row['price_range']; ?></span></td>
                                    <td><span class="rating-stars">⭐ <?php echo $row['rating']; ?>/5</span></td>
                                    <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="crud_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-view">Detail</a>
                                            <a href="crud_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">Edit</a>
                                            <a href="crud_delete.php?id=<?php echo $row['id']; ?>" 
                                               class="btn btn-danger" 
                                               onclick="return confirm('Yakin ingin menghapus <?php echo addslashes($row['name']); ?>?')">Hapus</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-data card">
                        <h3>😔 Tidak ada data restoran</h3>
                        <p><?php echo $search ? 'Coba gunakan kata kunci lain' : 'Silakan tambah restoran pertama Anda'; ?></p>
                        <a href="crud_create.php" class="btn btn-primary">Tambah Restoran Pertama</a>
                    </div>
                <?php endif; ?>
            </div>

            <?php if($total_pages > 1): ?>
                <nav class="pagination">
                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="crud_index.php?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </nav>
            <?php endif; ?>

            <div style="text-align: center; margin-top: 2rem; color: var(--muted-color);">
                Menampilkan <?php echo $stmt ? $stmt->rowCount() : 0; ?> dari <?php echo $total_records; ?> restoran
            </div>
            
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p class="text-center">&copy; 2025 PeRaSa - Peta Rasa Samarinda</p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>