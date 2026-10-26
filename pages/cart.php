<?php
$page_title = "Keranjang Belanja";
require_once dirname(__FILE__) . '/../includes/header.php';

requireCustomer();

$user_id = $_SESSION['user_id'];

// Process remove item
if (isset($_GET['remove'])) {
    $cart_id = (int)$_GET['remove'];
    $conn->query("DELETE FROM cart WHERE cart_id = $cart_id AND user_id = $user_id");
    header("Location: " . SITE_URL . "pages/cart.php");
    exit();
}

// Process update quantity
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $cart_id => $quantity) {
        $cart_id = (int)$cart_id;
        $quantity = (int)$quantity;
        
        if ($quantity > 0) {
            $conn->query("UPDATE cart SET quantity = $quantity WHERE cart_id = $cart_id AND user_id = $user_id");
        } else {
            $conn->query("DELETE FROM cart WHERE cart_id = $cart_id AND user_id = $user_id");
        }
    }
    header("Location: " . SITE_URL . "pages/cart.php");
    exit();
}

// Get cart items
$result = $conn->query("SELECT c.*, m.medicine_name, m.stock FROM cart c 
                        JOIN medicines m ON c.medicine_id = m.medicine_id 
                        WHERE c.user_id = $user_id");
?>

<div class="container">
    <h2 class="mb-4">Keranjang Belanja</h2>
    
    <?php if ($result->num_rows > 0): ?>
        <form method="POST">
            <div class="table-responsive mb-4">
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        while ($item = $result->fetch_assoc()) {
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['medicine_name']); ?></td>
                                <td><?php echo formatCurrency($item['price']); ?></td>
                                <td>
                                    <input type="number" class="form-control" style="width: 80px;" name="quantity[<?php echo $item['cart_id']; ?>]" value="<?php echo $item['quantity']; ?>" min="0" max="<?php echo $item['stock']; ?>">
                                </td>
                                <td><?php echo formatCurrency($subtotal); ?></td>
                                <td>
                                    <a href="?remove=<?php echo $item['cart_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <div class="row">
                <div class="col-md-6"></div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Ringkasan Pesanan</h5>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Total Harga:</strong>
                                <strong style="color: #10b981; font-size: 20px;">
                                    <?php echo formatCurrency($total); ?>
                                </strong>
                            </div>
                            <button type="submit" name="update_cart" class="btn btn-secondary w-100 mb-2">
                                Update Keranjang
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <a href="<?php echo SITE_URL; ?>pages/checkout.php" class="btn btn-primary btn-lg w-100">
                            Lanjut ke Checkout
                        </a>
                    </div>
                </div>
            </div>
        </form>
    <?php else: ?>
        <div class="alert alert-info" role="alert">
            <p>Keranjang Anda kosong</p>
            <a href="<?php echo SITE_URL; ?>pages/products.php" class="btn btn-primary">Lanjut Belanja</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once dirname(__FILE__) . '/../includes/footer.php'; ?>
