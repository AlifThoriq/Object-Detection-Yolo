<?php
include "koneksi.php"; // Koneksi ke 'mydb'
header('Content-Type: application/json');

/*
 * Ini adalah query yang dimodifikasi.
 * Kita ambil data 24 JAM terakhir.
 * Kita GROUP BY per JAM (bukan per menit).
 */
$query = "
    SELECT 
        DATE_FORMAT(timestamp, '%Y-%m-%d %H:00:00') as hour_label,
        COUNT(*) as detection_count
    FROM 
        game_detections
    WHERE 
        timestamp >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
    GROUP BY 
        hour_label
    ORDER BY 
        hour_label ASC
";

$result = $conn->query($query);

$data = [
    'labels' => [],
    'values' => []
];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Kita format labelnya biar cakep (Contoh: "22 Okt 15:00")
        $data['labels'][] = date("d M H:i", strtotime($row['hour_label']));
        $data['values'][] = (int)$row['detection_count'];
    }
} 
// Biarin kosong kalo gaada data, Chart.js bisa handle

echo json_encode($data);
$conn->close();
?>