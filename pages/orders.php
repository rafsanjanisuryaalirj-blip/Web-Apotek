<?php
$page_title = "Pesanan Saya";
require_once dirname(__FILE__) . '/../includes/header.php';

requireCustomer();

$user_id = $_SESSION['user_id'];

$orders = $conn->query("SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC");
?>

<div class="container">
    <h2 class="mb-4">Pesanan Saya</h2>
    
    <?php if ($orders->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $orders->fetch_assoc()): ?>
                        <tr>
                            <td><strong>APO-<?php echo str_pad($order['order_id'], 6, '0', STR_PAD_LEFT); ?></strong></td>
                            <td><?php echo date('d/m/Y', strtotime($order['created_at'])); ?></td>
                            <td><?php echo formatCurrency($order['total_price']); ?></td>
                            <td>
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
                            </td>
                            <td>
                                <?php
                                $payment_colors = [
                                    'pending' => 'warning',
                                    'completed' => 'success',
                                    'failed' => 'danger'
                                ];
                                $payment_labels = [
                                    'pending' => 'Pending',
                                    'completed' => 'Selesai',
                                    'failed' => 'Gagal'
                                ];
                                $color = $payment_colors[$order['payment_status']] ?? 'secondary';
                                $label = $payment_labels[$order['payment_status']] ?? $order['payment_status'];
                                ?>
                                <span class="badge bg-<?php echo $color; ?>"><?php echo $label; ?></span>
                            </td>
                            <td>
                                <a href="<?php echo SITE_URL; ?>pages/order-detail.php?id=<?php echo $order['order_id']; ?>" class="btn btn-sm btn-info">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info" role="alert">
            <p>Anda belum memiliki pesanan</p>
            <a href="<?php echo SITE_URL; ?>pages/products.php" class="btn btn-primary">Mulai Belanja</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once dirname(__FILE__) . '/../includes/footer.php'; ?>
