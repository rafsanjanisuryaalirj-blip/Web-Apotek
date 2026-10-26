<?php
$page_title = "Edit Supplier";
require_once dirname(__FILE__) . '/../includes/config.php';

requireAdmin();

$supplier_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$supplier = $conn->query("SELECT * FROM suppliers WHERE supplier_id = $supplier_id")->fetch_assoc();

if (!$supplier) {
    header("Location: " . SITE_URL . "admin/suppliers.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplier_name = sanitize($_POST['supplier_name']);
    $contact_person = sanitize($_POST['contact_person']);
    $phone = sanitize($_POST['phone']);
    $email = sanitize($_POST['email']);
    $address = sanitize($_POST['address']);
    $city = sanitize($_POST['city']);
    
    if (empty($supplier_name) || empty($contact_person)) {
        $error = "Nama supplier dan kontak harus diisi!";
    } else {
        $update = "UPDATE suppliers SET supplier_name = '$supplier_name', contact_person = '$contact_person', 
                  phone = '$phone', email = '$email', address = '$address', city = '$city' 
                  WHERE supplier_id = $supplier_id";
        
        if ($conn->query($update)) {
            $success = "Supplier berhasil diperbarui!";
            $supplier = $conn->query("SELECT * FROM suppliers WHERE supplier_id = $supplier_id")->fetch_assoc();
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Edit Supplier</title>
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
                    <h2>Edit Supplier</h2>
                    <a href="<?php echo SITE_URL; ?>admin/suppliers.php" class="btn btn-secondary">Kembali</a>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="supplier_name" class="form-label fw-bold">Nama Supplier *</label>
                                    <input type="text" class="form-control" id="supplier_name" name="supplier_name" value="<?php echo htmlspecialchars($supplier['supplier_name']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="contact_person" class="form-label fw-bold">Nama Kontak *</label>
                                    <input type="text" class="form-control" id="contact_person" name="contact_person" value="<?php echo htmlspecialchars($supplier['contact_person'] ?? ''); ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label fw-bold">Nomor Telepon</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($supplier['phone'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fw-bold">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($supplier['email'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label fw-bold">Alamat</label>
                                <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($supplier['address'] ?? ''); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="city" class="form-label fw-bold">Kota</label>
                                <input type="text" class="form-control" id="city" name="city" value="<?php echo htmlspecialchars($supplier['city'] ?? ''); ?>">
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Supplier
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
