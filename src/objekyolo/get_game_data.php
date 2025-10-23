<?php
include "koneksi.php"; // Koneksi ke 'mydb'
header('Content-Type: application/json');

// --- PERUBAHAN DI SINI ---
// Kita SELECT dari tabel 'game_detections'
$result = $conn->query("SELECT * FROM game_detections ORDER BY id DESC LIMIT 50");
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
$conn->close();
?>