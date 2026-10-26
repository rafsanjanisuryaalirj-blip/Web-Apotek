<?php
$page_title = "Detail Pesanan";
require_once dirname(__FILE__) . '/../includes/header.php';

requireCustomer();

$user_id = $_SESSION['user_id'];
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Validate order ownership
if (!validateOrderOwnership($order_id, $user_id)) {
    echo '<div class="container"><p class="alert alert-danger mt-4">Pesanan tidak ditemukan atau Anda tidak memiliki akses ke pesanan ini.</p></div>';
    require_once dirname(__FILE__) . '/../includes/footer.php';
    exit();
}

$order_result = $conn->query("SELECT * FROM orders WHERE order_id = $order_id AND user_id = $user_id");

if ($order_result->num_rows === 0) {
    echo '<div class="container"><p>Pesanan tidak ditemukan</p></div>';
    require_once dirname(__FILE__) . '/../includes/footer.php';
    exit();
}

$order = $order_result->fetch_assoc();
$order_details = $conn->query("SELECT od.*, m.medicine_name FROM order_details od 
                               JOIN medicines m ON od.medicine_id = m.medicine_id 
                               WHERE od.order_id = $order_id");

// Handle cancel order
$cancel_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order'])) {
    // Only allow cancellation for pending and processing orders
    if (in_array($order['status'], ['pending', 'processing'])) {
        $old_status = $order['status'];
        
        // Start transaction for stock management
        $conn->begin_transaction();
        
        try {
            // Restore stock only if order was in 'processing' (stock was already reduced)
            if ($old_status === 'processing') {
                // Get all order details
                $order_items = $conn->query("SELECT * FROM order_details WHERE order_id = $order_id");
                
                while ($item = $order_items->fetch_assoc()) {
                    $medicine_id = $item['medicine_id'];
                    $quantity = $item['quantity'];
                    
                    // Restore stock
                    $conn->query("UPDATE medicines SET stock = stock + $quantity WHERE medicine_id = $medicine_id");
                    
                    // Log stock restoration
                    $stock_result = $conn->query("SELECT stock FROM medicines WHERE medicine_id = $medicine_id");
                    $stock_data = $stock_result->fetch_assoc();
                    $new_stock = $stock_data['stock'];
                    
                    $conn->query("INSERT INTO stock_log (medicine_id, quantity_change, new_stock, action) 
                                 VALUES ($medicine_id, $quantity, $new_stock, 'return')");
                }
            }
            
            // Check if payment method requires refund
            $refund_methods = ['bank_transfer', 'credit_card', 'e_wallet'];
            $new_payment_status = in_array($order['payment_method'], $refund_methods) ? 'refund' : $order['payment_status'];
            
            $conn->query("UPDATE orders SET status = 'cancelled', payment_status = '$new_payment_status' WHERE order_id = $order_id AND user_id = $user_id");
            $conn->commit();
            
            $cancel_message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                Pesanan berhasil dibatalkan!';
            if (in_array($order['payment_method'], $refund_methods)) {
                $cancel_message .= ' Refund akan diproses dalam 3-5 hari kerja.';
            }
            $cancel_message .= '
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>';
            
            // Refresh order data
            $order_result = $conn->query("SELECT * FROM orders WHERE order_id = $order_id AND user_id = $user_id");
            $order = $order_result->fetch_assoc();
        } catch (Exception $e) {
            $conn->rollback();
            $cancel_message = '<div class="alert alert-danger">Error saat membatalkan pesanan: ' . $e->getMessage() . '</div>';
        }
    }
}
?>

<div class="container">
    <?php echo $cancel_message; ?>
    
    <div class="row mb-4">
        <div class="col-md-12">
            <a href="<?php echo SITE_URL; ?>pages/orders.php" class="btn btn-secondary mb-3">
                ← Kembali ke Pesanan
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">No. Pesanan: APO-<?php echo str_pad($order['order_id'], 6, '0', STR_PAD_LEFT); ?></h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Tanggal Pesanan:</strong><br>
                            <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Status:</strong><br>
                            <?php
                            $status_colors = [
                                'pending' => 'warning',
                                'processing' => 'info',
                                'shipped' => 'primary',
                                'delivered' => 'success',
                                'cancelled' => 'danger'
                            ];
                            $status_labels = [
                                'pending' => 'Pending',
                                'processing' => 'Diproses',
                                'shipped' => 'Dikirim',
                                'delivered' => 'Terkirim',
                                'cancelled' => 'Dibatalkan'
                            ];
                            $color = $status_colors[$order['status']] ?? 'secondary';
                            $label = $status_labels[$order['status']] ?? $order['status'];
                            ?>
                            <span class="badge bg-<?php echo $color; ?>"><?php echo $label; ?></span>
                        </div>
                        <div class="col-md-4">
                            <strong>Pembayaran:</strong><br>
                            <?php
                            $payment_colors = [
                                'pending' => 'warning',
                                'completed' => 'success',
                                'refund' => 'info',
                                'failed' => 'danger'
                            ];
                            $payment_labels = [
                                'pending' => 'Pending',
                                'completed' => 'Selesai',
                                'refund' => 'Refund',
                                'failed' => 'Gagal'
                            ];
                            $color = $payment_colors[$order['payment_status']] ?? 'secondary';
                            $label = $payment_labels[$order['payment_status']] ?? $order['payment_status'];
                            ?>
                            <span class="badge bg-<?php echo $color; ?>"><?php echo $label; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Produk yang Dipesan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Harga Satuan</th>
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
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Informasi Pengiriman</h5>
                </div>
                <div class="card-body">
                    <p><strong>Alamat:</strong><br>
                    <?php echo htmlspecialchars($order['delivery_address']); ?></p>
                    
                    <p><strong>Metode Pembayaran:</strong><br>
                    <?php
                    $payment_methods = [
                        'cash' => 'Bayar di Tempat',
                        'bank_transfer' => 'Transfer Bank',
                        'credit_card' => 'Kartu Kredit',
                        'e_wallet' => 'E-Wallet'
                    ];
                    echo $payment_methods[$order['payment_method']] ?? $order['payment_method'];
                    ?></p>
                    
                    <?php if ($order['notes']): ?>
                        <p><strong>Catatan:</strong><br>
                        <?php echo htmlspecialchars($order['notes']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Ringkasan Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <strong>Total Pesanan:</strong>
                        <strong style="color: #10b981; font-size: 20px;">
                            <?php echo formatCurrency($order['total_price']); ?>
                        </strong>
                    </div>
                    
                    <?php if ($order['payment_status'] === 'refund'): ?>
                        <div class="alert alert-info mt-3 mb-3">
                            <i class="fas fa-info-circle"></i> <strong>Status Refund</strong><br>
                            Refund Anda akan diproses dalam 3-5 hari kerja ke rekening yang terdaftar.
                        </div>
                    <?php endif; ?>
                    
                    <?php if (in_array($order['status'], ['pending', 'processing'])): ?>
                        <form method="POST" class="mt-3">
                            <button type="submit" name="cancel_order" class="btn btn-danger w-100" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">
                                <i class="fas fa-times-circle"></i> Batalkan Pesanan
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__FILE__) . '/../includes/footer.php'; ?>
