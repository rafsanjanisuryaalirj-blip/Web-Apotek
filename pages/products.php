<?php
$page_title = "Produk";
require_once dirname(__FILE__) . '/../includes/header.php';
?>

<div class="container">
    <div class="page-header mb-5 mt-4">
        <h1 class="page-title">Katalog Produk</h1>
        <p class="page-subtitle">Temukan produk kesehatan berkualitas dengan harga terbaik</p>
    </div>
    
    <!-- Search and Filter -->
    <div class="filter-section mb-5">
        <div class="filter-card">
            <form method="GET" class="row g-3">
                <div class="col-md-5">
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="form-control form-control-lg" name="search" placeholder="Cari produk..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-select form-select-lg" name="category">
                        <option value="">Semua Kategori</option>
                        <?php
                        $categories = $conn->query("SELECT * FROM categories");
                        while ($cat = $categories->fetch_assoc()) {
                            $selected = isset($_GET['category']) && $_GET['category'] == $cat['category_id'] ? 'selected' : '';
                            echo '<option value="' . $cat['category_id'] . '" ' . $selected . '>' . htmlspecialchars($cat['category_name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-search me-2"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Products Grid -->
    <div class="row g-4 mb-5">
        <?php
        $query = "SELECT m.*, c.category_name FROM medicines m 
                  JOIN categories c ON m.category_id = c.category_id 
                  WHERE m.status = 'available'";
        
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = sanitize($_GET['search']);
            $query .= " AND m.medicine_name LIKE '%$search%'";
        }
        
        if (isset($_GET['category']) && !empty($_GET['category'])) {
            $category = (int)$_GET['category'];
            $query .= " AND m.category_id = $category";
        }
        
        $query .= " ORDER BY m.created_at DESC";
        
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            while ($medicine = $result->fetch_assoc()) {
                ?>
                <div class="col-lg-4 col-md-6">
                    <div class="product-card-premium h-100">
                        <div class="product-image-wrapper">
                            <span class="category-badge"><?php echo htmlspecialchars($medicine['category_name']); ?></span>
                            <?php
                            if (!empty($medicine['image']) && file_exists(SITE_PATH . 'assets/img/medicines/' . $medicine['image'])) {
                                ?>
                                <img src="<?php echo SITE_URL; ?>assets/img/medicines/<?php echo htmlspecialchars($medicine['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($medicine['medicine_name']); ?>"
                                     class="product-image">
                                <?php
                            } else {
                                ?>
                                <div class="product-image-placeholder">
                                    <i class="fas fa-pills"></i>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="product-overlay">
                                <a href="<?php echo SITE_URL; ?>pages/product-detail.php?id=<?php echo $medicine['medicine_id']; ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye me-2"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                        <div class="product-body">
                            <h5 class="product-title"><?php echo htmlspecialchars($medicine['medicine_name']); ?></h5>
                            <p class="product-description"><?php echo htmlspecialchars(substr($medicine['composition'], 0, 70)); ?>...</p>
                            <div class="stock-info mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-box me-1" style="color: #10b981;"></i>
                                    Stok: <strong><?php echo $medicine['stock']; ?> <?php echo htmlspecialchars($medicine['unit']); ?></strong>
                                </small>
                            </div>
                            <div class="product-footer">
                                <div class="price-rating">
                                    <span class="product-price"><?php echo formatCurrency($medicine['price']); ?></span>
                                    <div class="rating">
                                        <i class="fas fa-star" style="color: #fbbf24;"></i>
                                        <span class="rating-text">4.8</span>
                                    </div>
                                </div>
                                <button class="btn-wishlist" title="Tambah ke Wishlist">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                            <a href="<?php echo SITE_URL; ?>pages/product-detail.php?id=<?php echo $medicine['medicine_id']; ?>" class="btn btn-add-cart w-100">
                                <i class="fas fa-shopping-cart me-2"></i> Tambah Keranjang
                            </a>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<div class="col-12"><div class="text-center py-5"><i class="fas fa-search" style="font-size: 48px; color: #d1d5db; margin-bottom: 15px;"></i><p class="text-muted mt-3">Produk tidak ditemukan</p></div></div>';
        }
        ?>
    </div>
</div>

<?php require_once dirname(__FILE__) . '/../includes/footer.php'; ?>
