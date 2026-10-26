<?php
$page_title = "Tambah Obat";
require_once dirname(__FILE__) . '/../includes/config.php';

requireAdmin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $medicine_id = (int)$_POST['medicine_id'];
    $category_id = (int)$_POST['category_id'];
    $supplier_id = (int)$_POST['supplier_id'];
    $description = sanitize($_POST['description']);
    $composition = sanitize($_POST['composition']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $unit = sanitize($_POST['unit']);
    $manufacturer = sanitize($_POST['manufacturer']);
    $status = sanitize($_POST['status']);
    
    // Get medicine data to validate
    $medicine = $conn->query("SELECT * FROM medicines WHERE medicine_id = $medicine_id")->fetch_assoc();
    
    if (!$medicine) {
        $error = "Obat tidak ditemukan!";
    } else {
        // Handle file upload
        $image = '';
        if ($_FILES['medicine_image']['name'] != '') {
            $file = $_FILES['medicine_image'];
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $file['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed) && $file['size'] <= 2000000) { // Max 2MB
                $new_filename = 'medicine_' . time() . '.' . $ext;
                $upload_path = dirname(__FILE__) . '/../assets/img/medicines/' . $new_filename;
                
                if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                    $image = $new_filename;
                } else {
                    $error = "Gagal mengupload file!";
                }
            } else {
                $error = "Format file tidak valid! Gunakan JPG, PNG atau GIF (max 2MB)";
            }
        }
        
        if (!$error) {
            // Update medicine with stock, unit, price, status, and image
            $update = "UPDATE medicines SET 
                      category_id = $category_id,
                      supplier_id = $supplier_id,
                      description = '$description',
                      composition = '$composition',
                      price = $price,
                      stock = $stock,
                      unit = '$unit',
                      status = '$status'
                      " . ($image ? ", image = '$image'" : "") . "
                      WHERE medicine_id = $medicine_id";
            
            if ($conn->query($update)) {
                $success = "Obat berhasil ditambahkan ke stok!";
            } else {
                $error = "Error: " . $conn->error;
            }
        }
    }
}

$suppliers = $conn->query("SELECT * FROM suppliers ORDER BY supplier_name ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Tambah Obat</title>
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
                    <li><a href="<?php echo SITE_URL; ?>admin/medicines.php" class="active">
                        <i class="fas fa-pills"></i> Kelola Obat
                    </a></li>
                </ul>
            </div>

            <div class="col-md-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Tambah Obat Baru</h2>
                    <a href="<?php echo SITE_URL; ?>admin/medicines.php" class="btn btn-secondary">Kembali</a>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Pilih Supplier terlebih dahulu, kemudian pilih Obat yang dikirim supplier tersebut.
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body text-center">
                                            <h6 class="card-title mb-3">Foto Obat</h6>
                                            <div class="bg-secondary rounded p-5 mb-3 text-white">
                                                <i class="fas fa-image" style="font-size: 80px;"></i>
                                            </div>
                                            <input type="file" class="form-control" id="medicine_image" name="medicine_image" accept="image/*">
                                            <small class="text-muted d-block mt-2">JPG, PNG, GIF (max 2MB)</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="row">
                                        <!-- Step 1: Select Supplier -->
                                        <div class="col-md-6 mb-3">
                                            <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                                            <select class="form-select" id="supplier_id" name="supplier_id" required onchange="loadSupplierProducts()">
                                                <option value="">Pilih Supplier</option>
                                                <?php while ($supp = $suppliers->fetch_assoc()): ?>
                                                    <option value="<?php echo $supp['supplier_id']; ?>">
                                                        <?php echo htmlspecialchars($supp['supplier_name']); ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>

                                        <!-- Step 2: Select Medicine from Supplier -->
                                        <div class="col-md-6 mb-3">
                                            <label for="medicine_id" class="form-label">Nama Obat <span class="text-danger">*</span></label>
                                            <select class="form-select" id="medicine_id" name="medicine_id" required onchange="updateMedicineDetails()">
                                                <option value="">Pilih Obat dari Supplier</option>
                                            </select>
                                        </div>

                                        <!-- Category (Auto-filled from medicine) -->
                                        <div class="col-md-6 mb-3">
                                            <label for="category_id" class="form-label">Kategori</label>
                                            <input type="text" class="form-control" id="category_id" name="category_id" readonly placeholder="Akan terisi otomatis">
                                        </div>

                                        <!-- Manufacturer (Read-only) -->
                                        <div class="col-md-6 mb-3">
                                            <label for="manufacturer" class="form-label">Manufaktur (Tidak Dapat Diubah)</label>
                                            <input type="text" class="form-control" id="manufacturer" name="manufacturer" readonly placeholder="Akan terisi otomatis" style="background-color: #e9ecef;">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Deskripsi</label>
                                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="composition" class="form-label">Komposisi</label>
                                        <input type="text" class="form-control" id="composition" name="composition" required>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label for="price" class="form-label">Harga (Rp)</label>
                                            <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="stock" class="form-label">Stok</label>
                                            <input type="number" class="form-control" id="stock" name="stock" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="unit" class="form-label">Satuan</label>
                                            <input type="text" class="form-control" id="unit" name="unit" placeholder="Mis: Strip x 10" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-select" id="status" name="status" required>
                                                <option value="available">Available</option>
                                                <option value="unavailable">Unavailable</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="manufacturer" class="form-label">Manufaktur</label>
                                        <select class="form-select" id="manufacturer" name="manufacturer" required>
                                            <option value="">Pilih Manufaktur</option>
                                            <option value="Pharma Indonesia">Pharma Indonesia</option>
                                            <option value="Pharmaceutical">Pharmaceutical</option>
                                            <option value="Herbal Pharma">Herbal Pharma</option>
                                            <option value="German Pharma">German Pharma</option>
                                            <option value="Medical Plus">Medical Plus</option>
                                            <option value="Skin Care">Skin Care</option>
                                            <option value="PT Kimia Farma">PT Kimia Farma</option>
                                            <option value="Tien Pharmaceuticals">Tien Pharmaceuticals</option>
                                            <option value="Fahrenheit Group">Fahrenheit Group</option>
                                            <option value="Soho Global Health">Soho Global Health</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan Obat</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Store medicines data globally
        let medicinesData = {};

        // Load supplier products when supplier is selected
        function loadSupplierProducts() {
            const supplierId = document.getElementById('supplier_id').value;
            const medicineSelect = document.getElementById('medicine_id');
            
            if (!supplierId) {
                medicineSelect.innerHTML = '<option value="">Pilih Obat dari Supplier</option>';
                clearMedicineDetails();
                return;
            }

            // Fetch products from supplier
            fetch('<?php echo SITE_URL; ?>admin/get_supplier_products.php?supplier_id=' + supplierId)
                .then(response => response.json())
                .then(data => {
                    medicineSelect.innerHTML = '<option value="">Pilih Obat dari Supplier</option>';
                    medicinesData = {};
                    
                    data.forEach(product => {
                        const option = document.createElement('option');
                        option.value = product.medicine_id;
                        option.textContent = product.medicine_name;
                        medicineSelect.appendChild(option);
                        
                        // Store data untuk auto-fill
                        medicinesData[product.medicine_id] = {
                            name: product.medicine_name,
                            manufacturer: product.manufacturer,
                            price: product.price
                        };
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    medicineSelect.innerHTML = '<option value="">Error loading medicines</option>';
                });
        }

        // Update medicine details (kategori & manufaktur)
        function updateMedicineDetails() {
            const medicineId = document.getElementById('medicine_id').value;
            
            if (!medicineId) {
                clearMedicineDetails();
                return;
            }

            const medicine = medicinesData[medicineId];
            if (medicine) {
                document.getElementById('manufacturer').value = medicine.manufacturer;
                // Fetch category from database
                fetch('<?php echo SITE_URL; ?>admin/get_medicine_details.php?medicine_id=' + medicineId)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('category_id').value = data.category_name || '';
                        document.getElementById('description').value = data.description || '';
                        document.getElementById('composition').value = data.composition || '';
                        document.getElementById('price').value = data.price || '';
                    })
                    .catch(error => console.error('Error:', error));
            }
        }

        // Clear medicine details
        function clearMedicineDetails() {
            document.getElementById('category_id').value = '';
            document.getElementById('manufacturer').value = '';
            document.getElementById('description').value = '';
            document.getElementById('composition').value = '';
            document.getElementById('price').value = '';
        }
    </script>
</body>
</html>
