<?php
$page_title = "Beranda";
require_once dirname(__FILE__) . '/../includes/header.php';
?>

<div class="container-fluid">
    <!-- Hero Section -->
    <div class="hero-section-premium mb-5">
        <div class="container">
            <div class="row align-items-center min-vh-75">
                <div class="col-lg-6 pe-lg-5">
                    <span class="badge badge-success-soft mb-3">
                        <i class="fas fa-leaf"></i> Go Green & Healthy
                    </span>
                    <h1 class="hero-title mb-3">Kesehatan Anda adalah Prioritas Kami</h1>
                    <p class="hero-subtitle mb-4">Belanja obat dan suplemen berkualitas dari apotek terpercaya. Pengiriman cepat & aman ke seluruh Indonesia.</p>
                    <div class="hero-features mb-5">
                        <div class="feature-item d-flex align-items-center mb-3">
                            <i class="fas fa-check-circle" style="color: #10b981; font-size: 24px; margin-right: 15px;"></i>
                            <span>Produk Resmi & Berkualitas</span>
                        </div>
                        <div class="feature-item d-flex align-items-center mb-3">
                            <i class="fas fa-check-circle" style="color: #10b981; font-size: 24px; margin-right: 15px;"></i>
                            <span>Pengiriman 1-2 Hari ke Seluruh Nusantara</span>
                        </div>
                        <div class="feature-item d-flex align-items-center">
                            <i class="fas fa-check-circle" style="color: #10b981; font-size: 24px; margin-right: 15px;"></i>
                            <span>Harga Terjangkau dengan Promo Setiap Hari</span>
                        </div>
                    </div>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="<?php echo SITE_URL; ?>pages/products.php" class="btn btn-primary btn-lg shadow-sm">
                            <i class="fas fa-shopping-cart me-2"></i> Belanja Sekarang
                        </a>
                        <a href="<?php echo SITE_URL; ?>pages/contact.php" class="btn btn-outline-success btn-lg">
                            <i class="fas fa-phone me-2"></i> Hubungi Kami
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="hero-image-container">
                        <img src="<?php echo SITE_URL; ?>assets/img/hero-medicine.png" alt="Medicine" class="img-fluid hero-image" style="max-width: 100%; height: auto; filter: drop-shadow(0 20px 50px rgba(16, 185, 129, 0.15));">
                        <div class="floating-badge promo-badge">
                            <div class="promo-text">Diskon<br><strong>10%</strong></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">

    <!-- Features Section -->
    <div class="row mb-5">
        <div class="col-md-3 mb-4">
            <div class="card border-0 text-center shadow-sm">
                <div class="card-body py-4">
                    <i class="fas fa-truck" style="font-size: 40px; color: #10b981; margin-bottom: 15px;"></i>
                    <h5 class="card-title">Pengiriman Cepat</h5>
                    <p class="card-text text-muted small">Pengiriman ke seluruh Indonesia dalam waktu 1-2 hari</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-0 text-center shadow-sm">
                <div class="card-body py-4">
                    <i class="fas fa-shield-alt" style="font-size: 40px; color: #10b981; margin-bottom: 15px;"></i>
                    <h5 class="card-title">Aman & Terpercaya</h5>
                    <p class="card-text text-muted small">Produk resmi dan terjamin keasliannya</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-0 text-center shadow-sm">
                <div class="card-body py-4">
                    <i class="fas fa-headset" style="font-size: 40px; color: #10b981; margin-bottom: 15px;"></i>
                    <h5 class="card-title">Customer Service</h5>
                    <p class="card-text text-muted small">Tim customer service siap membantu 24/7</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-0 text-center shadow-sm">
                <div class="card-body py-4">
                    <i class="fas fa-tag" style="font-size: 40px; color: #10b981; margin-bottom: 15px;"></i>
                    <h5 class="card-title">Harga Terbaik</h5>
                    <p class="card-text text-muted small">Harga kompetitif dengan promo menarik setiap hari</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Products Section -->
    <div class="mb-5">
        <div class="section-header mb-5">
            <h2 class="section-title">Produk Unggulan</h2>
            <p class="section-subtitle">Pilihan terbaik untuk kesehatan Anda</p>
        </div>
        <div class="row g-4">
            <?php
            $result = $conn->query("SELECT * FROM medicines WHERE status = 'available' LIMIT 6");
            
            if ($result->num_rows > 0) {
                while ($medicine = $result->fetch_assoc()) {
                    ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="product-card-premium h-100">
                            <div class="product-image-wrapper">
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
                                <p class="product-description"><?php echo htmlspecialchars(substr($medicine['description'], 0, 80)); ?>...</p>
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
                echo '<p class="text-center">Produk tidak tersedia</p>';
            }
            ?>
        </div>
    </div>

    <!-- Call to Action Section -->
    <div class="bg-green text-white rounded p-5 text-center mb-5" style="background-color: #10b981;">
        <h2 class="mb-3">Daftar Sekarang dan Dapatkan Diskon 10%</h2>
        <p class="lead mb-4">Bergabunglah dengan ribuan pelanggan kami dan nikmati keuntungan eksklusif</p>
        <?php if (!isLoggedIn()): ?>
            <a href="<?php echo SITE_URL; ?>pages/register.php" class="btn btn-light btn-lg">Daftar Sekarang</a>
        <?php else: ?>
            <p>Anda sudah terdaftar. Selamat berbelanja!</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once dirname(__FILE__) . '/../includes/footer.php'; ?>
