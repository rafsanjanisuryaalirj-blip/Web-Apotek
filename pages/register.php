<?php
$page_title = "Register";
require_once dirname(__FILE__) . '/../includes/config.php';

// Jika sudah login, redirect ke home
if (isLoggedIn()) {
    header("Location: " . SITE_URL);
    exit();
}

// Proses register
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = sanitize($_POST['full_name']);
    $phone = sanitize($_POST['phone']);
    
    // Validasi
    if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
        $error = "Semua field harus diisi!";
    } elseif ($password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak cocok!";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter!";
    } else {
        // Cek apakah username atau email sudah terdaftar
        $check = $conn->query("SELECT * FROM users WHERE username = '$username' OR email = '$email' LIMIT 1");
        
        if ($check->num_rows > 0) {
            $error = "Username atau email sudah terdaftar!";
        } else {
            // Hash password dengan bcrypt
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            
            // Insert user baru
            $insert = "INSERT INTO users (username, email, password, full_name, phone, role, status) 
                      VALUES ('$username', '$email', '$hashed_password', '$full_name', '$phone', 'customer', 'active')";
            
            if ($conn->query($insert)) {
                $success = "Pendaftaran berhasil! Silahkan login dengan akun Anda.";
            } else {
                $error = "Terjadi kesalahan saat mendaftar: " . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .register-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 450px;
            padding: 40px;
        }
        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .register-header h2 {
            color: #333;
            font-weight: bold;
        }
        .register-header p {
            color: #666;
            margin-bottom: 0;
        }
        .form-control {
            border-radius: 5px;
            padding: 10px 15px;
            border: 1px solid #ddd;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            width: 100%;
        }
        .btn-register:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
        }
        .register-footer {
            text-align: center;
            margin-top: 20px;
        }
        .register-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }
        .register-footer a:hover {
            text-decoration: underline;
        }
        .password-wrapper {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 12px;
            top: 38px;
            cursor: pointer;
            color: #667eea;
            font-size: 16px;
        }
        .toggle-password:hover {
            color: #764ba2;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h2><i class="fas fa-pills"></i> <?php echo SITE_NAME; ?></h2>
            <p>Buat akun baru</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $success; ?>
                <a href="<?php echo SITE_URL; ?>pages/login.php" class="btn btn-sm btn-success mt-2">Login Sekarang</a>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="mb-3">
                <label for="full_name" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="full_name" name="full_name" required>
            </div>
            
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            
            <div class="mb-3">
                <label for="phone" class="form-label">No. Telepon</label>
                <input type="tel" class="form-control" id="phone" name="phone">
            </div>
            
            <div class="mb-3 password-wrapper">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <i class="fas fa-eye toggle-password" id="togglePassword" onclick="togglePasswordVisibility('password')"></i>
            </div>
            
            <div class="mb-3 password-wrapper">
                <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                <i class="fas fa-eye toggle-password" id="toggleConfirmPassword" onclick="togglePasswordVisibility('confirm_password')"></i>
            </div>
            
            <button type="submit" class="btn btn-register btn-primary mb-3">Daftar</button>
        </form>
        
        <div class="register-footer">
            <p>Sudah punya akun? <a href="<?php echo SITE_URL; ?>pages/login-customer.php">Login di sini</a></p>
            <p><a href="<?php echo SITE_URL; ?>">← Kembali ke Beranda</a></p>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePasswordVisibility(fieldId) {
            const field = document.getElementById(fieldId);
            const toggleId = fieldId === 'password' ? 'togglePassword' : 'toggleConfirmPassword';
            const toggle = document.getElementById(toggleId);
            
            if (field.type === 'password') {
                field.type = 'text';
                toggle.classList.remove('fa-eye');
                toggle.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                toggle.classList.remove('fa-eye-slash');
                toggle.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
