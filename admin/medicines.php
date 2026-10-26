<?php
$page_title = "Kelola Obat";
require_once dirname(__FILE__) . '/../includes/config.php';

requireAdmin();

// Delete medicine
if (isset($_GET['delete'])) {
    $medicine_id = (int)$_GET['delete'];
    $conn->query("DELETE FROM medicines WHERE medicine_id = $medicine_id");
    header("Location: " . SITE_URL . "admin/medicines.php");
    exit();
}

// Get all medicines
$medicines = $conn->query("SELECT m.*, c.category_name FROM medicines m 
                         JOIN categories c ON m.category_id = c.category_id 
                         ORDER BY m.created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Kelola Obat</title>
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
                    <li><a href="<?php echo SITE_URL; ?>admin/">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a></li>
                    <li><a href="<?php echo SITE_URL; ?>admin/medicines.php" class="active">
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
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Kelola Obat</h2>
                    <a href="<?php echo SITE_URL; ?>admin/add-medicine.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Obat
                    </a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Obat</th>
                                        <th>Kategori</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th>Manufaktur</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($medicine = $medicines->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($medicine['medicine_name']); ?></td>
                                            <td><span class="badge bg-info"><?php echo htmlspecialchars($medicine['category_name']); ?></span></td>
                                            <td><?php echo formatCurrency($medicine['price']); ?></td>
                                            <td><?php echo $medicine['stock']; ?></td>
                                            <td><?php echo htmlspecialchars($medicine['manufacturer'] ?? '-'); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $medicine['status'] === 'available' ? 'success' : 'danger'; ?>">
                                                    <?php echo ucfirst($medicine['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?php echo SITE_URL; ?>admin/edit-medicine.php?id=<?php echo $medicine['medicine_id']; ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="?delete=<?php echo $medicine['medicine_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin?')">
                                                    <i class="fas fa-trash"></i>
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
</body>
</html>
