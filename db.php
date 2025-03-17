<?php
$host = "localhost"; // หรือ "localhost"
$dbname = "dress_shop"; // ชื่อฐานข้อมูล
$username = "root"; // ชื่อผู้ใช้ MySQL (ปกติ root)
$password = ""; // รหัสผ่าน (ปกติเป็นค่าว่างสำหรับ XAMPP)

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
