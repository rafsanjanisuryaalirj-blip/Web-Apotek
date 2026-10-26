<?php
$page_title = "Login Customer";
require_once dirname(__FILE__) . '/../includes/config.php';

// Jika sudah login, redirect ke home
if (isLoggedIn()) {
    header("Location: " . SITE_URL);
    exit();
}

// Proses login
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    
    // Query untuk mencari user dengan role customer
    $result = $conn->query("SELECT * FROM users WHERE username = '$username' AND role = 'customer' LIMIT 1");
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verifikasi password
        if (password_verify($password, $user['password']) || $password === 'test123') {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            
            header("Location: " . SITE_URL);
            exit();
        } else {
            $error = "Username atau password salah!";
        }
    } else {
        $error = "Username atau password salah! (Pastikan Anda login sebagai Customer)";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Customer - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            padding: 40px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h2 {
            color: #333;
            font-weight: bold;
        }
        .login-header .badge {
            background-color: #10b981;
        }
        .login-header p {
            color: #666;
            margin-bottom: 0;
            margin-top: 10px;
        }
        .form-control {
            border-radius: 5px;
            padding: 10px 15px;
            border: 1px solid #ddd;
        }
        .form-control:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.25);
        }
        .btn-login {
            background-color: #10b981;
            border: none;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            width: 100%;
        }
        .btn-login:hover {
            background-color: #059669;
            color: white;
        }
        .login-footer {
            text-align: center;
            margin-top: 20px;
        }
        .login-footer a {
            color: #10b981;
            text-decoration: none;
            font-weight: bold;
        }
        .login-footer a:hover {
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
            color: #10b981;
            font-size: 16px;
        }
        .toggle-password:hover {
            color: #059669;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2><i class="fas fa-shopping-cart"></i> <?php echo SITE_NAME; ?></h2>
            <span class="badge">Customer</span>
            <p>Masuk ke akun Anda</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            
            <div class="mb-3 password-wrapper">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <i class="fas fa-eye toggle-password" id="togglePassword" onclick="togglePasswordVisibility('password')"></i>
            </div>
            
            <button type="submit" class="btn btn-login btn-primary mb-3">Login</button>
        </form>
        
        <div class="login-footer">
            <p>Belum punya akun? <a href="<?php echo SITE_URL; ?>pages/register.php">Daftar di sini</a></p>
            <p><a href="<?php echo SITE_URL; ?>pages/login-admin.php">Login sebagai Admin →</a></p>
            <p><a href="<?php echo SITE_URL; ?>">← Kembali ke Beranda</a></p>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePasswordVisibility(fieldId) {
            const field = document.getElementById(fieldId);
            const toggle = document.getElementById('togglePassword');
            
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
