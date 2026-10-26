<?php
$page_title = "Detail Pesanan";
require_once dirname(__FILE__) . '/../includes/config.php';

requireAdmin();

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$order_result = $conn->query("SELECT o.*, u.full_name, u.email FROM orders o 
                              JOIN users u ON o.user_id = u.user_id 
                              WHERE o.order_id = $order_id");

if ($order_result->num_rows === 0) {
    header("Location: " . SITE_URL . "admin/orders.php");
    exit();
}

$order = $order_result->fetch_assoc();
$order_details = $conn->query("SELECT od.*, m.medicine_name FROM order_details od 
                               JOIN medicines m ON od.medicine_id = m.medicine_id 
                               WHERE od.order_id = $order_id");

// Update order status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = sanitize($_POST['status']);
    $payment_status = sanitize($_POST['payment_status']);
    $old_status = $order['status'];
    
    // Start transaction for stock management
    $conn->begin_transaction();
    
    try {
        // Handle stock reduction when changing to 'processing'
        if ($status === 'processing' && $old_status === 'pending') {
            // Get all order details
            $order_items = $conn->query("SELECT * FROM order_details WHERE order_id = $order_id");
            
            while ($item = $order_items->fetch_assoc()) {
                $medicine_id = $item['medicine_id'];
                $quantity = $item['quantity'];
                
                // Reduce stock
                $conn->query("UPDATE medicines SET stock = stock - $quantity WHERE medicine_id = $medicine_id");
                
                // Log stock change
                $stock_result = $conn->query("SELECT stock FROM medicines WHERE medicine_id = $medicine_id");
                $stock_data = $stock_result->fetch_assoc();
                $new_stock = $stock_data['stock'];
                
                $conn->query("INSERT INTO stock_log (medicine_id, quantity_change, new_stock, action) 
                             VALUES ($medicine_id, -$quantity, $new_stock, 'purchase')");
            }
        }
        
        // Handle stock restoration when cancelling
        if ($status === 'cancelled' && in_array($old_status, ['pending', 'processing'])) {
            // Get all order details
            $order_items = $conn->query("SELECT * FROM order_details WHERE order_id = $order_id");
            
            while ($item = $order_items->fetch_assoc()) {
                $medicine_id = $item['medicine_id'];
                $quantity = $item['quantity'];
                
                // Restore stock only if it was already reduced (processing status)
                if ($old_status === 'processing') {
                    $conn->query("UPDATE medicines SET stock = stock + $quantity WHERE medicine_id = $medicine_id");
                    
                    // Log stock restoration
                    $stock_result = $conn->query("SELECT stock FROM medicines WHERE medicine_id = $medicine_id");
                    $stock_data = $stock_result->fetch_assoc();
                    $new_stock = $stock_data['stock'];
                    
                    $conn->query("INSERT INTO stock_log (medicine_id, quantity_change, new_stock, action) 
                                 VALUES ($medicine_id, $quantity, $new_stock, 'return')");
                }
            }
        }
        
        // If status is being set to cancelled and payment method requires refund
        if ($status === 'cancelled') {
            $refund_methods = ['bank_transfer', 'credit_card', 'e_wallet'];
            if (in_array($order['payment_method'], $refund_methods)) {
                $payment_status = 'refund';
            }
        }
        
        $conn->query("UPDATE orders SET status = '$status', payment_status = '$payment_status' WHERE order_id = $order_id");
        $conn->commit();
        
        // Refresh data
        $order_result = $conn->query("SELECT o.*, u.full_name, u.email FROM orders o 
                                      JOIN users u ON o.user_id = u.user_id 
                                      WHERE o.order_id = $order_id");
        $order = $order_result->fetch_assoc();
    } catch (Exception $e) {
        $conn->rollback();
        echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Detail Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo SITE_URL; ?>admin/">
                <i class="fas fa-pills"></i> <?php echo SITE_NAME; ?> - Admin
            </a>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar p-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="text-white p-3">
                    <h6 class="mb-4">Menu Admin</h6>
                </div>
                <ul class="list-unstyled">
                    <li><a href="<?php echo SITE_URL; ?>admin/">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a></li>
                    <li><a href="<?php echo SITE_URL; ?>admin/orders.php">
                        <i class="fas fa-shopping-bag"></i> Pesanan
                    </a></li>
                </ul>
            </div>

            <div class="col-md-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Detail Pesanan APO-<?php echo str_pad($order['order_id'], 6, '0', STR_PAD_LEFT); ?></h2>
                    <a href="<?php echo SITE_URL; ?>admin/orders.php" class="btn btn-secondary">Kembali</a>
                </div>

                <?php if ($order['status'] === 'cancelled'): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i> <strong>Pesanan Dibatalkan!</strong> Pesanan ini telah dibatalkan oleh pelanggan.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-8">
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
                                <div class="text-end">
                                    <h5>Total: <span style="color: #10b981;"><?php echo formatCurrency($order['total_price']); ?></span></h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <?php if ($order['payment_status'] === 'refund'): ?>
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle"></i> <strong>Status Refund</strong><br>
                                <small>Pesanan dibatalkan. Refund akan diproses untuk metode pembayaran: <?php 
                                    $payment_methods = [
                                        'bank_transfer' => 'Transfer Bank',
                                        'credit_card' => 'Kartu Kredit',
                                        'e_wallet' => 'E-Wallet'
                                    ];
                                    echo $payment_methods[$order['payment_method']] ?? $order['payment_method'];
                                ?>
                                </small>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Data Pelanggan</h5>
                            </div>
                            <div class="card-body">
                                <p>
                                    <strong>Nama:</strong> <?php echo htmlspecialchars($order['full_name']); ?><br>
                                    <strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?><br>
                                    <strong>Alamat:</strong><br>
                                    <?php echo htmlspecialchars($order['delivery_address']); ?>
                                </p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Update Status</h5>
                            </div>
                            <div class="card-body">
                                <?php if ($order['status'] === 'cancelled'): ?>
                                    <div class="alert alert-info mb-3">
                                        <i class="fas fa-info-circle"></i> Pesanan sudah dibatalkan, tidak dapat diubah.
                                    </div>
                                <?php endif; ?>
                                <form method="POST" <?php echo $order['status'] === 'cancelled' ? 'style="opacity: 0.6; pointer-events: none;"' : ''; ?>>
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status Pesanan</label>
                                        <select class="form-select" id="status" name="status" <?php echo $order['status'] === 'cancelled' ? 'disabled' : ''; ?>>
                                            <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>Diproses</option>
                                            <option value="shipped" <?php echo $order['status'] === 'shipped' ? 'selected' : ''; ?>>Dikirim</option>
                                            <option value="delivered" <?php echo $order['status'] === 'delivered' ? 'selected' : ''; ?>>Terkirim</option>
                                            <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Dibatalkan</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="payment_status" class="form-label">Status Pembayaran</label>
                                        <select class="form-select" id="payment_status" name="payment_status" <?php echo $order['status'] === 'cancelled' ? 'disabled' : ''; ?>>
                                            <option value="pending" <?php echo $order['payment_status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="completed" <?php echo $order['payment_status'] === 'completed' ? 'selected' : ''; ?>>Selesai</option>
                                            <option value="refund" <?php echo $order['payment_status'] === 'refund' ? 'selected' : ''; ?>>Refund</option>
                                            <option value="failed" <?php echo $order['payment_status'] === 'failed' ? 'selected' : ''; ?>>Gagal</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100" <?php echo $order['status'] === 'cancelled' ? 'disabled' : ''; ?>>Update Status</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
