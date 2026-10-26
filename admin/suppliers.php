<?php
$page_title = "Kelola Supplier";
require_once dirname(__FILE__) . '/../includes/config.php';

requireAdmin();

// Delete supplier
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $supplier_id = (int)$_GET['id'];
    
    // Check if supplier is being used by medicines
    $check = $conn->query("SELECT COUNT(*) as count FROM medicines WHERE supplier_id = $supplier_id");
    $result = $check->fetch_assoc();
    
    if ($result['count'] > 0) {
        $error = "Tidak bisa menghapus supplier! Masih ada " . $result['count'] . " obat yang menggunakan supplier ini.";
    } else {
        $delete = $conn->query("DELETE FROM suppliers WHERE supplier_id = $supplier_id");
        if ($delete) {
            header("Location: " . SITE_URL . "admin/suppliers.php?success=deleted");
            exit();
        }
    }
}

// Get all suppliers
$suppliers = $conn->query("SELECT * FROM suppliers ORDER BY supplier_name ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Kelola Supplier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/style.css">
    <style>
        .sidebar {
            min-height: 100vh;
            position: sticky;
            top: 0;
        }
        .sidebar ul li {
            list-style: none;
            margin-bottom: 10px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            border-radius: 5px;
            transition: all 0.3s;
        }
        .sidebar a:hover {
            background: rgba(255,255,255,0.2);
        }
        .sidebar a.active {
            background: rgba(255,255,255,0.3);
            border-left: 4px solid white;
        }
    </style>
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
                <ul class="list-unstyled ps-0">
                    <li><a href="<?php echo SITE_URL; ?>admin/">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a></li>
                    <li><a href="<?php echo SITE_URL; ?>admin/medicines.php">
                        <i class="fas fa-pills"></i> Kelola Obat
                    </a></li>
                    <li><a href="<?php echo SITE_URL; ?>admin/suppliers.php" class="active">
                        <i class="fas fa-truck"></i> Kelola Supplier
                    </a></li>
                    <li><a href="<?php echo SITE_URL; ?>admin/categories.php">
                        <i class="fas fa-list"></i> Kategori
                    </a></li>
                </ul>
            </div>

            <div class="col-md-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-truck"></i> Kelola Supplier</h2>
                    <a href="<?php echo SITE_URL; ?>admin/add-supplier.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Supplier
                    </a>
                </div>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> Supplier berhasil dihapus!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <?php if ($suppliers->num_rows > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Supplier</th>
                                            <th>Kontak</th>
                                            <th>Telepon</th>
                                            <th>Email</th>
                                            <th>Kota</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; while ($supplier = $suppliers->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $no++; ?></td>
                                                <td><strong><?php echo htmlspecialchars($supplier['supplier_name']); ?></strong></td>
                                                <td><?php echo htmlspecialchars($supplier['contact_person'] ?? '-'); ?></td>
                                                <td><?php echo htmlspecialchars($supplier['phone'] ?? '-'); ?></td>
                                                <td><?php echo htmlspecialchars($supplier['email'] ?? '-'); ?></td>
                                                <td><?php echo htmlspecialchars($supplier['city'] ?? '-'); ?></td>
                                                <td>
                                                    <a href="<?php echo SITE_URL; ?>admin/edit-supplier.php?id=<?php echo $supplier['supplier_id']; ?>" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="?action=delete&id=<?php echo $supplier['supplier_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus supplier ini?');">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle"></i> Belum ada supplier. <a href="<?php echo SITE_URL; ?>admin/add-supplier.php">Tambah supplier sekarang</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
