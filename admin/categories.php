<?php
$page_title = "Kelola Kategori";
require_once dirname(__FILE__) . '/../includes/config.php';

requireAdmin();

// Add category
if (isset($_POST['add_category'])) {
    $category_name = sanitize($_POST['category_name']);
    $description = sanitize($_POST['description']);
    
    $conn->query("INSERT INTO categories (category_name, description) VALUES ('$category_name', '$description')");
}

// Delete category
if (isset($_GET['delete'])) {
    $category_id = (int)$_GET['delete'];
    $conn->query("DELETE FROM categories WHERE category_id = $category_id");
    header("Location: " . SITE_URL . "admin/categories.php");
    exit();
}

$categories = $conn->query("SELECT * FROM categories ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Kelola Kategori</title>
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
                    <li><a href="<?php echo SITE_URL; ?>admin/categories.php" class="active">
                        <i class="fas fa-folder"></i> Kategori
                    </a></li>
                </ul>
            </div>

            <div class="col-md-10 p-4">
                <h2 class="mb-4">Kelola Kategori</h2>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Tambah Kategori Baru</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="mb-3">
                                        <label for="category_name" class="form-label">Nama Kategori</label>
                                        <input type="text" class="form-control" id="category_name" name="category_name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Deskripsi</label>
                                        <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                                    </div>
                                    <button type="submit" name="add_category" class="btn btn-primary w-100">Tambah</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Daftar Kategori</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Kategori</th>
                                                <th>Deskripsi</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($cat = $categories->fetch_assoc()): ?>
                                                <tr>
                                                    <td><strong><?php echo htmlspecialchars($cat['category_name']); ?></strong></td>
                                                    <td><?php echo htmlspecialchars(substr($cat['description'] ?? '', 0, 50)); ?></td>
                                                    <td>
                                                        <a href="?delete=<?php echo $cat['category_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin?')">
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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
