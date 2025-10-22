<?php
$host = "db";         // Nama service 'db' di docker-compose.yml
$user = "user";     // Ini dari MYSQL_USER kamu
$pass = "password"; // Ini dari MYSQL_PASSWORD kamu
$db = "mydb"; // Database yang kita buat

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>