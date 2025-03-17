<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ดำเนินการ Checkout
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['checkout'])) {
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, order_date) VALUES (:user_id, :total_price, NOW())");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':total_price', $_POST['total_price']);
    $stmt->execute();
    $order_id = $conn->lastInsertId();
    
    // ย้ายสินค้าจากตะกร้าไปยังรายละเอียดคำสั่งซื้อ
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) 
                            SELECT :order_id, product_id, quantity, price FROM cart WHERE user_id = :user_id");
    $stmt->bindParam(':order_id', $order_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    
    // ลบสินค้าจากตะกร้า
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    
    header("Location: order_success.php");
    exit();
}

// ดึงรายการสินค้าในตะกร้า
$stmt = $conn->prepare("SELECT cart.cart_id, products.name, products.price, cart.quantity 
                        FROM cart 
                        JOIN products ON cart.product_id = products.product_id 
                        WHERE cart.user_id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_price = array_reduce($cart_items, function($sum, $item) {
    return $sum + ($item['price'] * $item['quantity']);
}, 0);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-4">Shopping Cart</h2>
        <div class="bg-white p-4 rounded-lg shadow-md">
            <?php if (empty($cart_items)): ?>
                <p class="text-gray-500">Your cart is empty.</p>
            <?php else: ?>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="border-b p-2">Product</th>
                            <th class="border-b p-2">Price</th>
                            <th class="border-b p-2">Quantity</th>
                            <th class="border-b p-2">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td class="p-2 border-b"> <?= htmlspecialchars($item['name']) ?> </td>
                                <td class="p-2 border-b">$<?= number_format($item['price'], 2) ?></td>
                                <td class="p-2 border-b"> <?= $item['quantity'] ?> </td>
                                <td class="p-2 border-b">$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p class="text-right font-semibold mt-4">Total: $<?= number_format($total_price, 2) ?></p>
                <form method="POST">
                    <input type="hidden" name="total_price" value="<?= $total_price ?>">
                    <button type="submit" name="checkout" class="w-full bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 mt-4">Checkout</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
