<?php
$page_title = "Admin Dashboard";
require_once dirname(__FILE__) . '/../includes/config.php';

requireAdmin();

// Get statistics
$users_count = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'customer'")->fetch_assoc()['total'];
$medicines_count = $conn->query("SELECT COUNT(*) as total FROM medicines")->fetch_assoc()['total'];
$orders_count = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'];
$total_revenue = $conn->query("SELECT SUM(total_price) as total FROM orders WHERE payment_status = 'completed'")->fetch_assoc()['total'];
$total_revenue = $total_revenue ?? 0;

// Get recent orders
$recent_orders = $conn->query("SELECT o.*, u.full_name FROM orders o 
                              JOIN users u ON o.user_id = u.user_id 
                              ORDER BY o.created_at DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo SITE_URL; ?>admin/">
                <i class="fas fa-pills"></i> <?php echo SITE_NAME; ?> - Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarAdmin">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> Admin
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>pages/profile.php">Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>pages/logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="text-white p-3">
                    <h6 class="mb-4">Menu Admin</h6>
                </div>
                <ul class="list-unstyled">
                    <li><a href="<?php echo SITE_URL; ?>admin/" class="active">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a></li>
                    <li><a href="<?php echo SITE_URL; ?>admin/medicines.php">
                        <i class="fas fa-pills"></i> Kelola Obat
                    </a></li>
                    <li><a href="<?php echo SITE_URL; ?>admin/suppliers.php">
                        <i class="fas fa-truck"></i> Kelola Supplier
                    </a></li>
                    <li><a href="<?php echo SITE_URL; ?>admin/manage-supplier-products.php">
                        <i class="fas fa-boxes"></i> Produk Supplier
                    </a></li>
                    <li><a href="<?php echo SITE_URL; ?>admin/categories.php">
                        <i class="fas fa-folder"></i> Kategori
                    </a></li>
                    <li><a href="<?php echo SITE_URL; ?>admin/orders.php">
                        <i class="fas fa-shopping-bag"></i> Pesanan
                    </a></li>
                    <li><a href="<?php echo SITE_URL; ?>admin/users.php">
                        <i class="fas fa-users"></i> Pengguna
                    </a></li>
                    <li><a href="<?php echo SITE_URL; ?>admin/payments.php">
                        <i class="fas fa-credit-card"></i> Pembayaran
                    </a></li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <h2 class="mb-4">Dashboard Admin</h2>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="dashboard-card">
                            <h3><?php echo $users_count; ?></h3>
                            <p>Pengguna Total</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="dashboard-card">
                            <h3><?php echo $medicines_count; ?></h3>
                            <p>Produk</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="dashboard-card">
                            <h3><?php echo $orders_count; ?></h3>
                            <p>Pesanan</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="dashboard-card">
                            <h3><?php echo formatCurrency($total_revenue); ?></h3>
                            <p>Revenue Total</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Pesanan Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>No. Pesanan</th>
                                        <th>Pelanggan</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($order = $recent_orders->fetch_assoc()): ?>
                                        <tr>
                                            <td><strong>APO-<?php echo str_pad($order['order_id'], 6, '0', STR_PAD_LEFT); ?></strong></td>
                                            <td><?php echo htmlspecialchars($order['full_name']); ?></td>
                                            <td><?php echo formatCurrency($order['total_price']); ?></td>
                                            <td>
                                                <span class="badge bg-info"><?php echo $order['status']; ?></span>
                                            </td>
                                            <td><?php echo date('d/m/Y', strtotime($order['created_at'])); ?></td>
                                            <td>
                                                <a href="<?php echo SITE_URL; ?>admin/order-detail.php?id=<?php echo $order['order_id']; ?>" class="btn btn-sm btn-primary">
                                                    Detail
                                                </a>
                                            </td>
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
    <script src="<?php echo SITE_URL; ?>assets/js/script.js"></script>
</body>
</html>
