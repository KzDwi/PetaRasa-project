<?php
require_once 'config.php';

$database = new Database();
$db = $database->getConnection();
$restaurant = new Restaurant($db);

$errors = [];

if($_POST){
    // Sanitize input
    $restaurant->name = $_POST['name'] ?? '';
    $restaurant->description = $_POST['description'] ?? '';
    $restaurant->address = $_POST['address'] ?? '';
    $restaurant->city = $_POST['city'] ?? '';
    $restaurant->category = $_POST['category'] ?? '';
    $restaurant->price_range = $_POST['price_range'] ?? '$$';
    $restaurant->rating = $_POST['rating'] ?? 0;
    $restaurant->image = $_POST['image'] ?? '';

    // Validation
    if(empty($restaurant->name)) {
        $errors[] = "Nama restoran harus diisi";
    }
    if(empty($restaurant->city)) {
        $errors[] = "Kota harus diisi";
    }
    if(empty($restaurant->category)) {
        $errors[] = "Kategori harus diisi";
    }
    if($restaurant->rating < 0 || $restaurant->rating > 5) {
        $errors[] = "Rating harus antara 0-5";
    }

    if(empty($errors)) {
        if($restaurant->create()){
            showMessage('success', 'Restoran berhasil ditambahkan!');
            redirect('crud_index.php');
        } else {
            $errors[] = "Terjadi kesalahan saat menambah restoran";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Restoran - PeRaSa</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chela+One&display=swap" rel="stylesheet">
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: var(--text-color);
        }
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1.5px solid var(--accent-color);
            border-radius: 8px;
            font-size: 1rem;
            background: var(--card-bg);
            color: var(--text-color);
            transition: border var(--transition), box-shadow var(--transition);
            box-sizing: border-box;
        }
        .form-control:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 2px 12px rgba(255,127,80,0.12);
        }
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }
        .Form {
            margin-left: 2rem;
        }
        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
        }
        .error-list {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border: 1px solid #f5c6cb;
        }
        .error-list ul {
            margin: 0;
            padding-left: 1.5rem;
        }
        .rating-input {
            width: 100px;
        }
        .dark-mode .form-control {
            border-color: #555;
            background: #3d3d3d;
        }
    </style>
</head>
<body>
    <header class="text-center">
        <div class="header-title-logo">
            <img src="img/_PerasaLogo.png" alt="Logo PeRaSa" class="logo-small">
            <h1>Tambah Restoran - PeRaSa</h1>
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
        <div class="form-container">
            <section class="card">
                <h2 class="text-center">Tambah Restoran Baru</h2>

                <?php if(!empty($errors)): ?>
                    <div class="error-list">
                        <ul>
                            <?php foreach($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="crud_create.php" class="Form">
                    <div class="form-group">
                        <label for="name">Nama Restoran *</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?php echo $_POST['name'] ?? ''; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description"><?php echo $_POST['description'] ?? ''; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="address">Alamat</label>
                        <textarea class="form-control" id="address" name="address"><?php echo $_POST['address'] ?? ''; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="city">Kota *</label>
                        <input type="text" class="form-control" id="city" name="city" 
                               value="<?php echo $_POST['city'] ?? ''; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="category">Kategori *</label>
                        <select class="form-control" id="category" name="category" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Indonesia" <?php echo ($_POST['category'] ?? '') == 'Indonesia' ? 'selected' : ''; ?>>Makanan Indonesia</option>
                            <option value="Jepang" <?php echo ($_POST['category'] ?? '') == 'Jepang' ? 'selected' : ''; ?>>Makanan Jepang</option>
                            <option value="Korea" <?php echo ($_POST['category'] ?? '') == 'Korea' ? 'selected' : ''; ?>>Makanan Korea</option>
                            <option value="Kafe" <?php echo ($_POST['category'] ?? '') == 'Kafe' ? 'selected' : ''; ?>>Kafe</option>
                            <option value="Street Food" <?php echo ($_POST['category'] ?? '') == 'Street Food' ? 'selected' : ''; ?>>Street Food</option>
                            <option value="Western" <?php echo ($_POST['category'] ?? '') == 'Western' ? 'selected' : ''; ?>>Western</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="price_range">Kisaran Harga</label>
                        <select class="form-control" id="price_range" name="price_range">
                            <option value="$" <?php echo ($_POST['price_range'] ?? '$$') == '$' ? 'selected' : ''; ?>>$ - Murah</option>
                            <option value="$$" <?php echo ($_POST['price_range'] ?? '$$') == '$$' ? 'selected' : ''; ?>>$$ - Sedang</option>
                            <option value="$$$" <?php echo ($_POST['price_range'] ?? '$$') == '$$$' ? 'selected' : ''; ?>>$$$ - Mahal</option>
                            <option value="$$$$" <?php echo ($_POST['price_range'] ?? '$$') == '$$$$' ? 'selected' : ''; ?>>$$$$ - Sangat Mahal</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="rating">Rating (0-5)</label>
                        <input type="number" class="form-control rating-input" id="rating" name="rating" 
                               min="0" max="5" step="0.1" value="<?php echo $_POST['rating'] ?? '0'; ?>">
                    </div>

                    <div class="form-group">
                        <label for="image">URL Gambar</label>
                        <input type="url" class="form-control" id="image" name="image" 
                               value="<?php echo $_POST['image'] ?? ''; ?>" 
                               placeholder="https://example.com/image.jpg">
                    </div>

                    <div class="form-actions">
                        <a href="crud_index.php" class="btn btn-edit">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan Restoran</button>
                    </div>
                </form>
            </section>
        </div>
    </main>

    <footer>
        <p class="text-center">&copy; 2025 PeRaSa - Peta Rasa Samarinda</p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>