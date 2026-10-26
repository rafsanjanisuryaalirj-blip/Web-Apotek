<?php
$page_title = "Pesanan Berhasil";
require_once dirname(__FILE__) . '/../includes/header.php';

requireLogin();

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
$user_id = $_SESSION['user_id'];

$order_result = $conn->query("SELECT * FROM orders WHERE order_id = $order_id AND user_id = $user_id");

if ($order_result->num_rows === 0) {
    header("Location: " . SITE_URL . "pages/orders.php");
    exit();
}

$order = $order_result->fetch_assoc();
$order_details = $conn->query("SELECT od.*, m.medicine_name FROM order_details od 
                               JOIN medicines m ON od.medicine_id = m.medicine_id 
                               WHERE od.order_id = $order_id");
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center mb-4">
                <div style="font-size: 60px; color: #28a745; margin-bottom: 20px;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2 class="mb-3">Pesanan Anda Berhasil Dibuat!</h2>
                <p class="text-muted">Terima kasih telah berbelanja di <?php echo SITE_NAME; ?></p>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Detail Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>No. Pesanan:</strong><br>
                            <span style="color: #10b981; font-size: 18px;">APO-<?php echo str_pad($order['order_id'], 6, '0', STR_PAD_LEFT); ?></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Tanggal Pesanan:</strong><br>
                            <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h5>Produk yang Dipesan</h5>
                    <div class="table-responsive mb-3">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($detail = $order_details->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($detail['medicine_name']); ?></td>
                                        <td><?php echo $detail['quantity']; ?></td>
                                        <td><?php echo formatCurrency($detail['price']); ?></td>
                                        <td><?php echo formatCurrency($detail['subtotal']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Metode Pembayaran:</strong><br>
                            <?php
                            $payment_methods = [
                                'cash' => 'Bayar di Tempat',
                                'bank_transfer' => 'Transfer Bank',
                                'credit_card' => 'Kartu Kredit',
                                'e_wallet' => 'E-Wallet'
                            ];
                            echo $payment_methods[$order['payment_method']] ?? $order['payment_method'];
                            ?>
                        </div>
                        <div class="col-md-6 text-end">
                            <strong>Total Pembayaran:</strong><br>
                            <span style="color: #10b981; font-size: 20px;">
                                <?php echo formatCurrency($order['total_price']); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Alamat Pengiriman</h5>
                </div>
                <div class="card-body">
                    <p><?php echo htmlspecialchars($order['delivery_address']); ?></p>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="<?php echo SITE_URL; ?>pages/orders.php" class="btn btn-primary btn-lg">
                    Lihat Semua Pesanan
                </a>
                <a href="<?php echo SITE_URL; ?>pages/products.php" class="btn btn-outline-primary btn-lg">
                    Lanjut Belanja
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__FILE__) . '/../includes/footer.php'; ?>
