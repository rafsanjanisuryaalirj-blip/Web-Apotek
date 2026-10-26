<?php
require_once 'includes/config.php';

// Jika sudah login, redirect ke halaman yang sesuai
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
    <title>Informasi Akses - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding-top: 20px;
            padding-bottom: 20px;
        }
        .info-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .info-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 50px 30px;
            text-align: center;
        }
        .info-header h1 {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .info-body {
            padding: 40px;
        }
        .access-card {
            border-left: 4px solid #667eea;
            padding: 25px;
            margin-bottom: 30px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .access-card h3 {
            color: #667eea;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .access-card p {
            margin: 10px 0;
            color: #555;
        }
        .access-card ul {
            margin: 15px 0;
            padding-left: 20px;
        }
        .access-card li {
            margin-bottom: 8px;
            color: #555;
        }
        .btn-group-access {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 30px;
        }
        .btn-access {
            padding: 15px 30px;
            font-weight: bold;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .btn-customer {
            background-color: #10b981;
            color: white;
        }
        .btn-customer:hover {
            background-color: #059669;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
        }
        .btn-admin {
            background-color: #667eea;
            color: white;
        }
        .btn-admin:hover {
            background-color: #5568d3;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        .feature-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        .feature-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .feature-item i {
            font-size: 20px;
            color: #667eea;
        }
        @media (max-width: 768px) {
            .btn-group-access {
                grid-template-columns: 1fr;
            }
            .info-header {
                padding: 30px 20px;
            }
            .info-header h1 {
                font-size: 24px;
            }
            .info-body {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container" style="max-width: 900px;">
        <div class="info-container">
            <div class="info-header">
                <h1><i class="fas fa-pills"></i> <?php echo SITE_NAME; ?></h1>
                <p class="mb-0">Sistem Apotek Online Terpadu</p>
            </div>
            
            <div class="info-body">
                <div class="access-card">
                    <h3><i class="fas fa-user"></i> Pelanggan (Customer)</h3>
                    <p>Akses untuk pelanggan yang ingin berbelanja obat online.</p>
                    
                    <div class="feature-list">
                        <div class="feature-item">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Belanja Obat</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-list"></i>
                            <span>Kelola Pesanan</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-times-circle"></i>
                            <span>Batalkan Pesanan</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-undo"></i>
                            <span>Proses Refund</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-credit-card"></i>
                            <span>Berbagai Metode Bayar</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-user-circle"></i>
                            <span>Kelola Profil</span>
                        </div>
                    </div>
                </div>
                
                <div class="access-card">
                    <h3><i class="fas fa-shield-alt"></i> Admin (Administrator)</h3>
                    <p>Akses untuk administrator yang mengelola sistem apotek.</p>
                    
                    <div class="feature-list">
                        <div class="feature-item">
                            <i class="fas fa-pills"></i>
                            <span>Kelola Obat</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-list-ul"></i>
                            <span>Kelola Kategori</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-truck"></i>
                            <span>Kelola Supplier</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-shopping-bag"></i>
                            <span>Kelola Pesanan</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-users"></i>
                            <span>Kelola Pelanggan</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-credit-card"></i>
                            <span>Monitor Pembayaran</span>
                        </div>
                    </div>
                </div>
                
                <div class="btn-group-access">
                    <a href="<?php echo SITE_URL; ?>pages/login-customer.php" class="btn-access btn-customer">
                        <i class="fas fa-sign-in-alt"></i> Login Pelanggan
                    </a>
                    <a href="<?php echo SITE_URL; ?>pages/login-admin.php" class="btn-access btn-admin">
                        <i class="fas fa-sign-in-alt"></i> Login Admin
                    </a>
                </div>
                
                <div class="mt-5 pt-4 border-top">
                    <h5 class="mb-3"><i class="fas fa-info-circle"></i> Informasi Penting</h5>
                    <ul>
                        <li>Sistem login terpisah antara pelanggan dan admin untuk keamanan maksimal</li>
                        <li>Admin hanya bisa mengakses admin panel setelah login sebagai admin</li>
                        <li>Pelanggan dapat membatalkan pesanan dengan status pending atau processing</li>
                        <li>Refund otomatis diproses untuk metode pembayaran transfer bank, kartu kredit, dan e-wallet</li>
                        <li>Semua data transaksi terenkripsi dan aman</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
