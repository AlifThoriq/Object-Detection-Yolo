<?php
include "koneksi.php"; // Koneksi ke 'mydb'
header('Content-Type: application/json');

/*
 * Ini adalah query BARU (sesuai idemu).
 * Kita ambil data 1 JAM terakhir.
 * Kita GROUP BY per MENIT (%H:%i).
 */
$query = "
    SELECT 
        DATE_FORMAT(timestamp, '%Y-%m-%d %H:%i:00') as minute_label,
        COUNT(*) as detection_count
    FROM 
        game_detections
    WHERE 
        timestamp >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
    GROUP BY 
        minute_label
    ORDER BY 
        minute_label ASC
";

$result = $conn->query($query);

$data = [
    'labels' => [],
    'values' => []
];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Kita format labelnya biar pendek (Contoh: "15:01")
        $data['labels'][] = date("H:i", strtotime($row['minute_label']));
        $data['values'][] = (int)$row['detection_count'];
    }
} 
// Biarin kosong kalo gaada data, Chart.js bisa handle

echo json_encode($data);
$conn->close();
?>