<?php
$page_title = "Login";
require_once dirname(__FILE__) . '/../includes/config.php';

// Jika sudah login, redirect sesuai role
if (isLoggedIn()) {
    if (isAdmin()) {
        header("Location: " . SITE_URL . "admin/");
    } else {
        header("Location: " . SITE_URL);
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo SITE_NAME; ?></title>
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
        .login-gateway {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .gateway-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        .gateway-header h1 {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .gateway-header p {
            font-size: 14px;
            opacity: 0.9;
        }
        .login-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            padding: 40px;
        }
        .login-option {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 250px;
        }
        .login-option:hover {
            border-color: #10b981;
            box-shadow: 0 5px 20px rgba(16, 185, 129, 0.2);
            transform: translateY(-5px);
            text-decoration: none;
            color: #10b981;
        }
        .login-option i {
            font-size: 50px;
            margin-bottom: 15px;
            color: #10b981;
        }
        .login-option h3 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .login-option p {
            font-size: 14px;
            color: #666;
            margin: 0;
        }
        @media (max-width: 768px) {
            .login-options {
                grid-template-columns: 1fr;
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="login-gateway" style="width: 100%; max-width: 700px;">
        <div class="gateway-header">
            <h1><i class="fas fa-pills"></i> <?php echo SITE_NAME; ?></h1>
            <p>Pilih tipe akun untuk melanjutkan</p>
        </div>
        
        <div class="login-options">
            <a href="<?php echo SITE_URL; ?>pages/login-customer.php" class="login-option">
                <i class="fas fa-user"></i>
                <h3>Pelanggan</h3>
                <p>Login sebagai pelanggan untuk berbelanja obat</p>
            </a>
            
            <a href="<?php echo SITE_URL; ?>pages/login-admin.php" class="login-option">
                <i class="fas fa-shield-alt"></i>
                <h3>Admin</h3>
                <p>Login sebagai administrator sistem</p>
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
