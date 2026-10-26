<?php
$page_title = "Kelola Pembayaran";
require_once dirname(__FILE__) . '/../includes/config.php';

requireAdmin();

$payments = $conn->query("SELECT p.*, o.order_id FROM payments p 
                         JOIN orders o ON p.order_id = o.order_id 
                         ORDER BY p.payment_date DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Kelola Pembayaran</title>
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
                    <li><a href="<?php echo SITE_URL; ?>admin/payments.php" class="active">
                        <i class="fas fa-credit-card"></i> Pembayaran
                    </a></li>
                </ul>
            </div>

            <div class="col-md-10 p-4">
                <h2 class="mb-4">Kelola Pembayaran</h2>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID Pembayaran</th>
                                        <th>No. Pesanan</th>
                                        <th>Jumlah</th>
                                        <th>Metode</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($payment = $payments->fetch_assoc()): ?>
                                        <tr>
                                            <td><strong><?php echo $payment['payment_id']; ?></strong></td>
                                            <td>APO-<?php echo str_pad($payment['order_id'], 6, '0', STR_PAD_LEFT); ?></td>
                                            <td><?php echo formatCurrency($payment['amount']); ?></td>
                                            <td><?php echo ucfirst(str_replace('_', ' ', $payment['payment_method'])); ?></td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $payment['status'] === 'success' ? 'success' : 
                                                        ($payment['status'] === 'pending' ? 'warning' : 'danger');
                                                ?>">
                                                    <?php echo ucfirst($payment['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($payment['payment_date'])); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
