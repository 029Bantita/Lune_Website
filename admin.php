<?php
session_start();
require 'db.php';

// เพิ่มสินค้า
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, image) VALUES (:name, :description, :price, :stock, :image)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':stock', $stock);
    $stmt->bindParam(':image', $image);
    $stmt->execute();
}

// ลบสินค้า
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header('Location: admin.php');
}

// ดึงข้อมูลสินค้า
$stmt = $conn->prepare("SELECT * FROM products");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>