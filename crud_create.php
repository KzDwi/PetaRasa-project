<?php
session_start();
require_once 'config.php';

// Redirect ke login jika belum login
if(!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();
$restaurant = new Restaurant($db);

$errors = [];
$success_message = '';

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
            // Set success message dan reset form
            $success_message = "✅ Restoran <strong>'{$restaurant->name}'</strong> berhasil ditambahkan!";
            
            // Reset form values
            $_POST = array();
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
            max-width: 700px;
            margin: 0 auto;
        }
        .form-header {
            text-align: center;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            border-radius: var(--border-radius);
        }
        .form-header h2 {
            margin: 0;
            font-size: 2rem;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group.full-width {
            grid-column: 1 / -1;
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
            min-height: 120px;
            resize: vertical;
        }
        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: space-between;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--secondary-color);
        }
        .error-list {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border: 1px solid #f5c6cb;
        }
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border: 1px solid #c3e6cb;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideInDown 0.3s ease;
        }
        .success-message .icon {
            font-size: 1.5rem;
        }
        .error-list ul {
            margin: 0;
            padding-left: 1.5rem;
        }
        .rating-input {
            width: 120px;
        }
        .preview-image {
            max-width: 200px;
            max-height: 150px;
            border-radius: 8px;
            margin-top: 10px;
            display: none;
            border: 2px solid var(--accent-color);
        }
        .form-help {
            font-size: 0.85rem;
            color: var(--muted-color);
            margin-top: 5px;
        }
        .required-field::after {
            content: " *";
            color: #dc3545;
        }
        
        /* Success actions */
        .success-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #c3e6cb;
        }
        
        @keyframes slideInDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .dark-mode .form-control {
            border-color: #555;
            background: #3d3d3d;
        }
        .dark-mode .form-header {
            background: linear-gradient(135deg, #2d2d2d, #43aa8b);
        }
        .dark-mode .success-message {
            background: #1e3a2e;
            color: #d4edda;
            border-color: #155724;
        }
        
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .form-actions {
                flex-direction: column;
            }
            .success-actions {
                flex-direction: column;
            }
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
                <li id="nav-menu"><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="form-container">
            <section class="card">
                <div class="form-header">
                    <h2>Tambah Restoran Baru</h2>
                    <p>Isi form berikut untuk menambahkan restoran baru ke database</p>
                </div>

                <?php 
                // Display success message
                if(!empty($success_message)): 
                ?>
                    <div class="success-message">
                        <div class="icon">✅</div>
                        <div class="message">
                            <strong>Berhasil!</strong> <?php echo $success_message; ?>
                        </div>
                    </div>
                    
                    <div class="success-actions">
                        <a href="crud_create.php" class="btn btn-primary">➕ Tambah Restoran Lain</a>
                        <a href="crud_index.php" class="btn btn-success">📋 Lihat Semua Restoran</a>
                        <a href="index.php" class="btn btn-secondary">🏠 Kembali ke Home</a>
                    </div>
                <?php 
                endif;
                ?>
                
                <?php if(!empty($errors)): ?>
                    <div class="error-list">
                        <h4>⚠️ Perbaiki error berikut:</h4>
                        <ul>
                            <?php foreach($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if(empty($success_message)): ?>
                <form method="POST" action="crud_create.php" id="createForm">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name" class="required-field">Nama Restoran</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" 
                                   required placeholder="Masukkan nama restoran">
                            <div class="form-help">Contoh: Kikitaru Restaurant</div>
                        </div>

                        <div class="form-group">
                            <label for="city" class="required-field">Kota</label>
                            <input type="text" class="form-control" id="city" name="city" 
                                   value="<?php echo htmlspecialchars($_POST['city'] ?? ''); ?>" 
                                   required placeholder="Contoh: Samarinda">
                            <div class="form-help">Kota lokasi restoran</div>
                        </div>

                        <div class="form-group">
                            <label for="category" class="required-field">Kategori</label>
                            <select class="form-control" id="category" name="category" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Indonesia" <?php echo ($_POST['category'] ?? '') == 'Indonesia' ? 'selected' : ''; ?>>Makanan Indonesia</option>
                                <option value="Jepang" <?php echo ($_POST['category'] ?? '') == 'Jepang' ? 'selected' : ''; ?>>Makanan Jepang</option>
                                <option value="Korea" <?php echo ($_POST['category'] ?? '') == 'Korea' ? 'selected' : ''; ?>>Makanan Korea</option>
                                <option value="Kafe" <?php echo ($_POST['category'] ?? '') == 'Kafe' ? 'selected' : ''; ?>>Kafe</option>
                                <option value="Street Food" <?php echo ($_POST['category'] ?? '') == 'Street Food' ? 'selected' : ''; ?>>Street Food</option>
                                <option value="Western" <?php echo ($_POST['category'] ?? '') == 'Western' ? 'selected' : ''; ?>>Western</option>
                                <option value="Chinese" <?php echo ($_POST['category'] ?? '') == 'Chinese' ? 'selected' : ''; ?>>Chinese</option>
                                <option value="Thailand" <?php echo ($_POST['category'] ?? '') == 'Thailand' ? 'selected' : ''; ?>>Thailand</option>
                            </select>
                            <div class="form-help">Pilih kategori makanan</div>
                        </div>

                        <div class="form-group">
                            <label for="price_range" class="required-field">Kisaran Harga</label>
                            <select class="form-control" id="price_range" name="price_range" required>
                                <option value="$" <?php echo ($_POST['price_range'] ?? '$$') == '$' ? 'selected' : ''; ?>>$ - Murah (di bawah Rp 50.000)</option>
                                <option value="$$" <?php echo ($_POST['price_range'] ?? '$$') == '$$' ? 'selected' : ''; ?>>$$ - Sedang (Rp 50.000 - 150.000)</option>
                                <option value="$$$" <?php echo ($_POST['price_range'] ?? '$$') == '$$$' ? 'selected' : ''; ?>>$$$ - Mahal (Rp 150.000 - 300.000)</option>
                                <option value="$$$$" <?php echo ($_POST['price_range'] ?? '$$') == '$$$$' ? 'selected' : ''; ?>>$$$$ - Sangat Mahal (di atas Rp 300.000)</option>
                            </select>
                            <div class="form-help">Perkiraan harga per orang</div>
                        </div>

                        <div class="form-group">
                            <label for="rating" class="required-field">Rating</label>
                            <input type="number" class="form-control rating-input" id="rating" name="rating" 
                                   min="0" max="5" step="0.1" value="<?php echo $_POST['rating'] ?? '0'; ?>" required>
                            <div class="form-help">Masukkan angka antara 0.0 sampai 5.0</div>
                        </div>

                        <div class="form-group">
                            <label for="image">URL Gambar</label>
                            <input type="url" class="form-control" id="image" name="image" 
                                   value="<?php echo htmlspecialchars($_POST['image'] ?? ''); ?>" 
                                   placeholder="https://example.com/image.jpg"
                                   oninput="previewImage(this.value)">
                            <img id="imagePreview" class="preview-image" alt="Preview gambar">
                            <div class="form-help">Opsional: Masukkan URL gambar yang valid</div>
                        </div>

                        <div class="form-group full-width">
                            <label for="address">Alamat Lengkap</label>
                            <textarea class="form-control" id="address" name="address" 
                                      placeholder="Masukkan alamat lengkap restoran"><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
                            <div class="form-help">Contoh: Jl. Contoh No. 123, Kec. Samarinda Seberang</div>
                        </div>

                        <div class="form-group full-width">
                            <label for="description">Deskripsi Restoran</label>
                            <textarea class="form-control" id="description" name="description" 
                                      placeholder="Deskripsikan restoran, menu spesial, suasana, dll."><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                            <div class="form-help">Jelaskan secara detail tentang restoran ini</div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <div>
                            <a href="crud_index.php" class="btn btn-secondary">📋 Kembali ke Daftar</a>
                        </div>
                        <div>
                            <button type="reset" class="btn btn-secondary">🔄 Reset Form</button>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                💾 Simpan Restoran
                            </button>
                        </div>
                    </div>
                </form>
                <?php endif; ?>
            </section>
        </div>
    </main>

    <footer>
        <p class="text-center">&copy; 2025 PeRaSa - Peta Rasa Samarinda</p>
    </footer>

    <script>
        function previewImage(url) {
            const preview = document.getElementById('imagePreview');
            if (url) {
                preview.src = url;
                preview.style.display = 'block';
                preview.onerror = function() {
                    preview.style.display = 'none';
                    alert('Gambar tidak dapat dimuat. Pastikan URL valid.');
                }
            } else {
                preview.style.display = 'none';
            }
        }

        // Initialize preview if image exists
        document.addEventListener('DOMContentLoaded', function() {
            const imageUrl = document.getElementById('image')?.value;
            if (imageUrl) {
                previewImage(imageUrl);
            }

            // Rating validation
            const ratingInput = document.getElementById('rating');
            if (ratingInput) {
                ratingInput.addEventListener('input', function() {
                    const value = parseFloat(this.value);
                    if (value < 0 || value > 5) {
                        this.setCustomValidity('Rating harus antara 0-5');
                    } else {
                        this.setCustomValidity('');
                    }
                });
            }

            // Form submission with loading state
            const form = document.getElementById('createForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const rating = parseFloat(document.getElementById('rating').value);
                    if (rating < 0 || rating > 5) {
                        e.preventDefault();
                        alert('Rating harus antara 0-5');
                        return false;
                    }

                    // Show loading state
                    const submitBtn = document.getElementById('submitBtn');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '⏳ Menyimpan...';
                    submitBtn.disabled = true;

                    // Re-enable button after 3 seconds (in case of error)
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }, 3000);
                });
            }

            // Form auto-save feature
            let formData = {};
            if (form) {
                form.addEventListener('input', function() {
                    const formElements = form.elements;
                    formData = {};
                    for (let element of formElements) {
                        if (element.name) {
                            formData[element.name] = element.value;
                        }
                    }
                    localStorage.setItem('restaurantFormDraft', JSON.stringify(formData));
                });

                // Load draft on page load
                const draft = localStorage.getItem('restaurantFormDraft');
                if (draft) {
                    const formData = JSON.parse(draft);
                    for (let [name, value] of Object.entries(formData)) {
                        const element = form.querySelector(`[name="${name}"]`);
                        if (element) {
                            element.value = value;
                            
                            // Trigger preview for image
                            if (name === 'image' && value) {
                                previewImage(value);
                            }
                        }
                    }
                }

                // Clear draft on successful form submission
                form.addEventListener('submit', function() {
                    localStorage.removeItem('restaurantFormDraft');
                });
            }

            // Clear draft when going back to list
            const backLink = document.querySelector('a[href="crud_index.php"]');
            if (backLink) {
                backLink.addEventListener('click', function() {
                    localStorage.removeItem('restaurantFormDraft');
                });
            }
        });
    </script>
</body>
</html>