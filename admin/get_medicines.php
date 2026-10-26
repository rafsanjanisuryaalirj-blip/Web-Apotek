<?php
header('Content-Type: application/json');
require_once dirname(__FILE__) . '/../includes/config.php';

// Search for medicines by name (partial match)
$q = isset($_GET['q']) ? trim($_GET['q']) : '';

if (strlen($q) < 1) {
    echo json_encode([]);
    exit();
}

$safe_q = $conn->real_escape_string($q);
$query = "SELECT medicine_id, medicine_name, manufacturer 
          FROM medicines 
          WHERE medicine_name LIKE '%$safe_q%' 
          ORDER BY medicine_name ASC 
          LIMIT 10";

$result = $conn->query($query);
$medicines = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $medicines[] = [
            'medicine_id' => $row['medicine_id'],
            'medicine_name' => $row['medicine_name'],
            'manufacturer' => $row['manufacturer']
        ];
    }
}

echo json_encode($medicines);
?>
