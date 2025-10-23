<?php
include "koneksi.php"; // Koneksi ke 'mydb'
header('Content-Type: application/json');

// Query ini akan menghitung total deteksi, dikelompokkan berdasarkan nama kelas
$result = $conn->query("
    SELECT 
        class_name, 
        COUNT(*) as total_deteksi
    FROM 
        game_detections 
    GROUP BY 
        class_name
    ORDER BY 
        total_deteksi DESC
");

$data = [
    'labels' => [], // Untuk nama kelas (e.g., 'mouse', 'headphone')
    'values' => []  // Untuk jumlah (e.g., 150, 90)
];

while ($row = $result->fetch_assoc()) {
    $data['labels'][] = $row['class_name'];
    $data['values'][] = (int)$row['total_deteksi'];
}

echo json_encode($data);
$conn->close();
?>