<!-- <?php
include "koneksi.php";
header('Content-Type: application/json');

$result = $conn->query("SELECT * FROM detections ORDER BY id DESC LIMIT 50");
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
$conn->close();
?> -->