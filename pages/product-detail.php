<?php
$page_title = "Detail Produk";
require_once dirname(__FILE__) . '/../includes/header.php';

// Get product detail
$medicine_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$result = $conn->query("SELECT m.*, c.category_name FROM medicines m 
                        JOIN categories c ON m.category_id = c.category_id 
                        WHERE m.medicine_id = $medicine_id");

if ($result->num_rows === 0) {
    echo '<div class="container"><p>Produk tidak ditemukan</p></div>';
    require_once dirname(__FILE__) . '/../includes/footer.php';
    exit();
}

$medicine = $result->fetch_assoc();

// Process add to cart
$cart_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!isLoggedIn()) {
        header("Location: " . SITE_URL . "pages/login.php");
        exit();
    }
    
    $user_id = $_SESSION['user_id'];
    $quantity = (int)$_POST['quantity'];
    
    if ($quantity > 0 && $quantity <= $medicine['stock']) {
        // Check if already in cart
        $check = $conn->query("SELECT * FROM cart WHERE user_id = $user_id AND medicine_id = $medicine_id");
        
        if ($check->num_rows > 0) {
            // Update quantity
            $old_qty = $check->fetch_assoc()['quantity'];
            $conn->query("UPDATE cart SET quantity = quantity + $quantity WHERE user_id = $user_id AND medicine_id = $medicine_id");
        } else {
            // Add to cart
            $conn->query("INSERT INTO cart (user_id, medicine_id, quantity, price) VALUES ($user_id, $medicine_id, $quantity, " . $medicine['price'] . ")");
        }
        
        $cart_message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            Produk berhasil ditambahkan ke keranjang!
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>';
    }
}
?>

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Beranda</a></li>
            <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>pages/products.php">Produk</a></li>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($medicine['medicine_name']); ?></li>
        </ol>
    </nav>
    
    <?php echo $cart_message; ?>
    
    <div class="row">
        <div class="col-md-4">
            <div class="bg-light rounded p-3 text-center mb-4" style="height: 400px; display: flex; align-items: center; justify-content: center; cursor: pointer;" id="imageContainer" data-bs-toggle="modal" data-bs-target="#imageModal">
                <?php if ($medicine['image']): ?>
                    <img src="<?php echo SITE_URL; ?>assets/img/medicines/<?php echo htmlspecialchars($medicine['image']); ?>" class="img-fluid rounded" style="max-height: 100%; max-width: 100%; object-fit: contain;" id="productImage">
                <?php else: ?>
                    <i class="fas fa-pills" style="font-size: 150px; color: #10b981; opacity: 0.3;"></i>
                <?php endif; ?>
            </div>
            <?php if ($medicine['image']): ?>
                <p class="text-center text-muted small"><i class="fas fa-search-plus"></i> Klik gambar untuk melihat detail</p>
            <?php endif; ?>
        </div>
        
        <div class="col-md-8">
            <span class="badge bg-info mb-2"><?php echo htmlspecialchars($medicine['category_name']); ?></span>
            <h1 class="mb-3"><?php echo htmlspecialchars($medicine['medicine_name']); ?></h1>
            
            <div class="mb-4">
                <h3 style="color: #10b981;"><?php echo formatCurrency($medicine['price']); ?></h3>
            </div>
            
            <div class="table-responsive mb-4">
                <table class="table table-bordered">
                    <tr>
                        <td width="30%"><strong>Kategori</strong></td>
                        <td><?php echo htmlspecialchars($medicine['category_name']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Komposisi</strong></td>
                        <td><?php echo htmlspecialchars($medicine['composition']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Kemasan</strong></td>
                        <td><?php echo htmlspecialchars($medicine['unit']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Manufaktur</strong></td>
                        <td><?php echo htmlspecialchars($medicine['manufacturer'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Stok Tersedia</strong></td>
                        <td>
                            <?php if ($medicine['stock'] > 0): ?>
                                <span class="badge bg-success"><?php echo $medicine['stock']; ?> <?php echo htmlspecialchars($medicine['unit']); ?></span>
                            <?php else: ?>
                                <span class="badge bg-danger">Habis</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="mb-4">
                <h5>Deskripsi Produk</h5>
                <p><?php echo htmlspecialchars($medicine['description']); ?></p>
            </div>
            
            <?php if ($medicine['stock'] > 0): ?>
                <form method="POST">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="quantity" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" min="1" max="<?php echo $medicine['stock']; ?>" value="1" required>
                        </div>
                    </div>
                    <?php if (isLoggedIn()): ?>
                        <button type="submit" name="add_to_cart" class="btn btn-primary btn-lg">
                            <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                        </button>
                    <?php else: ?>
                        <a href="<?php echo SITE_URL; ?>pages/login.php" class="btn btn-primary btn-lg">
                            Login untuk Membeli
                        </a>
                    <?php endif; ?>
                </form>
            <?php else: ?>
                <button class="btn btn-secondary btn-lg" disabled>Stok Habis</button>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Reviews Section -->
    <div class="row mt-5">
        <div class="col-md-12">
            <h3>Ulasan Pelanggan</h3>
            <?php
            $reviews = $conn->query("SELECT r.*, u.full_name FROM reviews r 
                                    JOIN users u ON r.user_id = u.user_id 
                                    WHERE r.medicine_id = $medicine_id 
                                    ORDER BY r.created_at DESC");
            
            if ($reviews->num_rows > 0) {
                while ($review = $reviews->fetch_assoc()) {
                    ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-title"><?php echo htmlspecialchars($review['full_name']); ?></h6>
                                    <div class="mb-2">
                                        <?php for ($i = 0; $i < $review['rating']; $i++): ?>
                                            <i class="fas fa-star" style="color: #ffc107;"></i>
                                        <?php endfor; ?>
                                        <?php for ($i = $review['rating']; $i < 5; $i++): ?>
                                            <i class="far fa-star" style="color: #ffc107;"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <small class="text-muted"><?php echo date('d/m/Y', strtotime($review['created_at'])); ?></small>
                            </div>
                            <p class="card-text"><?php echo htmlspecialchars($review['comment']); ?></p>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<p class="text-muted">Belum ada ulasan untuk produk ini</p>';
            }
            ?>
        </div>
    </div>
</div>

<!-- Image Detail Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark border-0">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title text-white"><?php echo htmlspecialchars($medicine['medicine_name']); ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <?php if ($medicine['image']): ?>
                    <img src="<?php echo SITE_URL; ?>assets/img/medicines/<?php echo htmlspecialchars($medicine['image']); ?>" 
                         class="img-fluid rounded" 
                         style="max-height: 500px; max-width: 100%; object-fit: contain;"
                         alt="<?php echo htmlspecialchars($medicine['medicine_name']); ?>">
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__FILE__) . '/../includes/footer.php'; ?>
