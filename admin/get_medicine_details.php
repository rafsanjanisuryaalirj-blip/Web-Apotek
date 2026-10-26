<?php
require_once dirname(__FILE__) . '/../includes/config.php';

header('Content-Type: application/json');

if (!isset($_GET['medicine_id'])) {
    echo json_encode(['error' => 'Medicine ID is required']);
    exit;
}

$medicine_id = (int)$_GET['medicine_id'];

$query = "SELECT m.*, c.category_name 
          FROM medicines m
          LEFT JOIN categories c ON m.category_id = c.category_id
          WHERE m.medicine_id = $medicine_id";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode([
        'medicine_id' => $row['medicine_id'],
        'medicine_name' => htmlspecialchars($row['medicine_name']),
        'category_id' => $row['category_id'],
        'category_name' => htmlspecialchars($row['category_name']),
        'description' => htmlspecialchars($row['description']),
        'composition' => htmlspecialchars($row['composition']),
        'manufacturer' => htmlspecialchars($row['manufacturer']),
        'price' => $row['price']
    ]);
} else {
    echo json_encode(['error' => 'Medicine not found']);
}
