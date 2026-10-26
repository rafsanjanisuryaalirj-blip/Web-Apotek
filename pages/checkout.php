<?php
$page_title = "Checkout";
require_once dirname(__FILE__) . '/../includes/header.php';

requireCustomer();

$user_id = $_SESSION['user_id'];

// Get user data
$user_result = $conn->query("SELECT * FROM users WHERE user_id = $user_id");
$user = $user_result->fetch_assoc();

// Get cart items
$cart_result = $conn->query("SELECT c.*, m.medicine_name FROM cart c 
                             JOIN medicines m ON c.medicine_id = m.medicine_id 
                             WHERE c.user_id = $user_id");

if ($cart_result->num_rows === 0) {
    header("Location: " . SITE_URL . "pages/cart.php");
    exit();
}

// Calculate total
$total = 0;
$cart_items = [];
while ($item = $cart_result->fetch_assoc()) {
    $subtotal = $item['price'] * $item['quantity'];
    $total += $subtotal;
    $cart_items[] = $item;
}

// Process checkout
$success_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $delivery_address = sanitize($_POST['delivery_address']);
    $payment_method = sanitize($_POST['payment_method']);
    $notes = sanitize($_POST['notes']);
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Create order
        $insert_order = "INSERT INTO orders (user_id, total_price, payment_method, delivery_address, notes, status) 
                        VALUES ($user_id, $total, '$payment_method', '$delivery_address', '$notes', 'pending')";
        $conn->query($insert_order);
        $order_id = $conn->insert_id;
        
        // Add order details (DONT reduce stock yet, will be done when status changed to 'processing')
        foreach ($cart_items as $item) {
            $medicine_id = $item['medicine_id'];
            $quantity = $item['quantity'];
            $price = $item['price'];
            $subtotal = $quantity * $price;
            
            // Insert order detail
            $conn->query("INSERT INTO order_details (order_id, medicine_id, quantity, price, subtotal) 
                         VALUES ($order_id, $medicine_id, $quantity, $price, $subtotal)");
            
            // IMPORTANT: Stock reduction will happen when admin changes status to 'processing'
            // This ensures stock is only reduced after confirmation
        }
        
        // Create payment record
        $conn->query("INSERT INTO payments (order_id, amount, payment_method, status) 
                     VALUES ($order_id, $total, '$payment_method', 'pending')");
        
        // Clear cart
        $conn->query("DELETE FROM cart WHERE user_id = $user_id");
        
        $conn->commit();
        
        // Redirect to success page
        header("Location: " . SITE_URL . "pages/order-success.php?order_id=$order_id");
        exit();
        
    } catch (Exception $e) {
        $conn->rollback();
        $error_message = "Terjadi kesalahan: " . $e->getMessage();
    }
}
?>

<div class="container">
    <h2 class="mb-4">Checkout</h2>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Data Pengiriman</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" disabled>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">No. Telepon</label>
                            <input type="tel" class="form-control" id="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" disabled>
                        </div>
                        
                        <div class="mb-3">
                            <label for="delivery_address" class="form-label">Alamat Pengiriman</label>
                            <textarea class="form-control" id="delivery_address" name="delivery_address" rows="3" required><?php echo htmlspecialchars($user['address']); ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Metode Pembayaran</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="cash">COD</option>
                                <option value="bank_transfer">Transfer Bank</option>
                                <option value="credit_card">Kartu Kredit</option>
                                <option value="e_wallet">E-Wallet</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            Proses Pesanan
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Ringkasan Pesanan</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($cart_items as $item): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <small><?php echo htmlspecialchars($item['medicine_name']); ?> x<?php echo $item['quantity']; ?></small>
                            <small><?php echo formatCurrency($item['price'] * $item['quantity']); ?></small>
                        </div>
                    <?php endforeach; ?>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong style="color: #10b981; font-size: 18px;">
                            <?php echo formatCurrency($total); ?>
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__FILE__) . '/../includes/footer.php'; ?>
