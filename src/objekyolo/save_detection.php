<!-- <?php
// Konfigurasi Header: CORS & JSON
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle request OPTIONS (preflight CORS)
if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    http_response_code(200);
    exit();
}

include "koneksi.php";

$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

// Validasi JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400); // Bad Request
    echo json_encode([
        "status" => "error",
        "message" => "Invalid JSON: " . json_last_error_msg()
    ], JSON_PRETTY_PRINT);
    exit;
}

// Validasi format data
if (!isset($data["detections"]) || !is_array($data["detections"])) {
    http_response_code(400); // Bad Request
    echo json_encode([
        "status" => "error",
        "message" => "Invalid format. Expected { \"detections\": [...] }"
    ], JSON_PRETTY_PRINT);
    exit;
}

// Prepare statement
$stmt = $conn->prepare("INSERT INTO detections (class_name, confidence, timestamp) VALUES (?, ?, ?)");
if (!$stmt) {
    http_response_code(500); // Server Error
    echo json_encode([
        "status" => "error",
        "message" => "Prepare statement failed: " . $conn->error
    ], JSON_PRETTY_PRINT);
    exit;
}

$inserted = 0;
$errors = [];

foreach ($data["detections"] as $det) {
    if (isset($det["class_name"], $det["confidence"], $det["timestamp"])) {
        
        $class_name = $det["class_name"];
        $confidence = floatval($det["confidence"]);
        $timestamp = $det["timestamp"];
        
        // --- INI BAGIAN YANG DIBENERIN ---
        // Kode di modul  salah. Ini yang bener:
        $stmt->bind_param("sds", $class_name, $confidence, $timestamp);
        
        if ($stmt->execute()) {
            $inserted++;
        } else {
            $errors[] = $stmt->error;
        }
    } else {
        $errors[] = "Incomplete detection data skipped";
    }
}

$stmt->close();
$conn->close();

// Kirim respon
$response = [
    "status" => $inserted > 0 ? "success" : "warning",
    "inserted" => $inserted,
    "errors" => $errors
];

echo json_encode($response, JSON_PRETTY_PRINT);
?> -->