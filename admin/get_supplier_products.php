<?php
require_once dirname(__FILE__) . '/../includes/config.php';

header('Content-Type: application/json');

if (!isset($_GET['supplier_id'])) {
    echo json_encode(['error' => 'Supplier ID is required']);
    exit;
}

$supplier_id = (int)$_GET['supplier_id'];

// Get all products from supplier with their details
$query = "SELECT sp.supplier_product_id, m.medicine_id, m.medicine_name, m.manufacturer, m.price
          FROM supplier_products sp
          JOIN medicines m ON sp.medicine_id = m.medicine_id
          WHERE sp.supplier_id = $supplier_id
          ORDER BY m.medicine_name ASC";

$result = $conn->query($query);
$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = [
        'supplier_product_id' => $row['supplier_product_id'],
        'medicine_id' => $row['medicine_id'],
        'medicine_name' => htmlspecialchars($row['medicine_name']),
        'manufacturer' => htmlspecialchars($row['manufacturer']),
        'price' => $row['price']
    ];
}

echo json_encode($products);
