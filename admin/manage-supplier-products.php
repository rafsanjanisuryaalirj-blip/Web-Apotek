<?php
$page_title = "Kelola Produk Supplier";
require_once dirname(__FILE__) . '/../includes/config.php';

requireAdmin();

$supplier_id = isset($_GET['supplier_id']) ? (int)$_GET['supplier_id'] : 0;
$supplier = $conn->query("SELECT * FROM suppliers WHERE supplier_id = $supplier_id")->fetch_assoc();

if (!$supplier && $supplier_id > 0) {
    header("Location: " . SITE_URL . "admin/suppliers.php");
    exit();
}

$error = '';
$success = '';

// Add product to supplier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $medicine_name = isset($_POST['medicine_name']) ? trim($_POST['medicine_name']) : '';
    $manufacturer = isset($_POST['manufacturer']) ? trim($_POST['manufacturer']) : '';
    $category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;
    $harga_supplier = $_POST['harga_supplier'] ? (float)$_POST['harga_supplier'] : null;

    if (empty($medicine_name) || empty($manufacturer) || $category_id <= 0) {
        $error = "Lengkapi semua field yang wajib.";
    } else {
        // Insert new medicine directly
        $safe_name = $conn->real_escape_string($medicine_name);
        $safe_manufacturer = $conn->real_escape_string($manufacturer);

        $insert_medicine = "INSERT INTO medicines (medicine_name, manufacturer, category_id, price, stock, status) 
                           VALUES ('$safe_name', '$safe_manufacturer', $category_id, 0, 0, 'Available')";

        if ($conn->query($insert_medicine)) {
            $medicine_id = $conn->insert_id;

            // Insert into supplier_products
            $insert_supplier_product = "INSERT INTO supplier_products (supplier_id, medicine_id, harga_supplier) 
                                       VALUES ($supplier_id, $medicine_id, " . ($harga_supplier ? $harga_supplier : "NULL") . ")";

            if ($conn->query($insert_supplier_product)) {
                $success = "Produk berhasil ditambahkan!";
            } else {
                // Delete the medicine if supplier_product insert fails
                $conn->query("DELETE FROM medicines WHERE medicine_id = $medicine_id");
                $error = "Error: " . $conn->error;
            }
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}

// Delete product from supplier
if (isset($_GET['delete'])) {
    $product_id = (int)$_GET['delete'];
    $delete = "DELETE FROM supplier_products WHERE supplier_product_id = $product_id AND supplier_id = $supplier_id";
    
    if ($conn->query($delete)) {
        $success = "Produk berhasil dihapus!";
    } else {
        $error = "Error: " . $conn->error;
    }
}

// Get supplier's products
$products = $conn->query("SELECT sp.*, m.medicine_name, m.manufacturer, c.category_name 
                          FROM supplier_products sp 
                          JOIN medicines m ON sp.medicine_id = m.medicine_id 
                          JOIN categories c ON m.category_id = c.category_id
                          WHERE sp.supplier_id = $supplier_id
                          ORDER BY m.medicine_name ASC");

// Get available medicines (not already assigned)
$assigned_medicines = [];
$temp = $conn->query("SELECT medicine_id FROM supplier_products WHERE supplier_id = $supplier_id");
while ($row = $temp->fetch_assoc()) {
    $assigned_medicines[] = $row['medicine_id'];
}

$medicines = $conn->query("SELECT * FROM medicines ORDER BY medicine_name ASC");

$suppliers = $conn->query("SELECT * FROM suppliers ORDER BY supplier_name ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Kelola Produk Supplier</title>
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
                    <li><a href="<?php echo SITE_URL; ?>admin/medicines.php">
                        <i class="fas fa-pills"></i> Kelola Obat
                    </a></li>
                    <li><a href="<?php echo SITE_URL; ?>admin/suppliers.php">
                        <i class="fas fa-dolly"></i> Kelola Supplier
                    </a></li>
                </ul>
            </div>

            <div class="col-md-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Kelola Produk Supplier</h2>
                    <a href="<?php echo SITE_URL; ?>admin/suppliers.php" class="btn btn-secondary">Kembali</a>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <!-- Supplier Selection -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Pilih Supplier</h5>
                        <select class="form-select" id="supplier_selector" onchange="location.href='?supplier_id=' + this.value">
                            <option value="">-- Pilih Supplier --</option>
                            <?php while ($s = $suppliers->fetch_assoc()): ?>
                                <option value="<?php echo $s['supplier_id']; ?>" <?php echo $s['supplier_id'] == $supplier_id ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($s['supplier_name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <?php if ($supplier): ?>
                    <!-- Add Product Form -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Tambah Produk ke Supplier</h5>
                            <p class="text-muted"><strong><?php echo htmlspecialchars($supplier['supplier_name']); ?></strong> dari <?php echo htmlspecialchars($supplier['city']); ?></p>
                            
                            <form method="POST">
                                <input type="hidden" name="add_product" value="1">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="medicine_id" class="form-label">Nama Obat</label>
                                        <input type="text" class="form-control" id="medicine_name" name="medicine_name" placeholder="Masukkan nama obat baru" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="manufacturer" class="form-label">Manufaktur</label>
                                        <input type="text" class="form-control" id="manufacturer" name="manufacturer" placeholder="Nama manufaktur" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="category_id" class="form-label">Kategori</label>
                                        <select class="form-select" id="category_id" name="category_id" required>
                                            <option value="">-- Pilih Kategori --</option>
                                            <?php 
                                            $categories = $conn->query("SELECT * FROM categories ORDER BY category_name ASC");
                                            while ($cat = $categories->fetch_assoc()): 
                                            ?>
                                                <option value="<?php echo $cat['category_id']; ?>">
                                                    <?php echo htmlspecialchars($cat['category_name']); ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="harga_supplier" class="form-label">Harga Supplier (Optional)</label>
                                        <input type="number" class="form-control" id="harga_supplier" name="harga_supplier" step="0.01" placeholder="Harga per unit dari supplier">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Tambah Produk</button>
                            </form>
                        </div>
                    </div>

                    <!-- Products List -->
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Daftar Produk dari <?php echo htmlspecialchars($supplier['supplier_name']); ?></h5>
                            
                            <?php if ($products->num_rows > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Nama Obat</th>
                                                <th>Kategori</th>
                                                <th>Manufaktur</th>
                                                <th>Harga Supplier</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($prod = $products->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($prod['medicine_name']); ?></td>
                                                    <td><span class="badge bg-info"><?php echo htmlspecialchars($prod['category_name']); ?></span></td>
                                                    <td><?php echo htmlspecialchars($prod['manufacturer']); ?></td>
                                                    <td>
                                                        <?php if ($prod['harga_supplier']): ?>
                                                            Rp <?php echo number_format($prod['harga_supplier'], 0, ',', '.'); ?>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="?supplier_id=<?php echo $supplier_id; ?>&delete=<?php echo $prod['supplier_product_id']; ?>" 
                                                           class="btn btn-sm btn-danger" 
                                                           onclick="return confirm('Hapus produk ini?')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">Supplier ini belum memiliki produk. Tambahkan produk di atas.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Pilih supplier di atas untuk melihat dan mengelola produk mereka.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
