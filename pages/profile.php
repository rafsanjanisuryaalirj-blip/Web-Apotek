<?php
$page_title = "Profil";
require_once dirname(__FILE__) . '/../includes/header.php';

requireCustomer();

$user_id = $_SESSION['user_id'];
$user_result = $conn->query("SELECT * FROM users WHERE user_id = $user_id");
$user = $user_result->fetch_assoc();

// Process update profile
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitize($_POST['full_name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $address = sanitize($_POST['address']);
    
    $update = "UPDATE users SET full_name = '$full_name', email = '$email', phone = '$phone', address = '$address' WHERE user_id = $user_id";
    
    if ($conn->query($update)) {
        $_SESSION['email'] = $email;
        $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Profil berhasil diperbarui!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>';
        // Refresh user data
        $user_result = $conn->query("SELECT * FROM users WHERE user_id = $user_id");
        $user = $user_result->fetch_assoc();
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h2 class="mb-4">Profil Saya</h2>
            
            <?php echo $message; ?>
            
            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                        </div>
                        
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">No. Telepon</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($user['address']); ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__FILE__) . '/../includes/footer.php'; ?>
