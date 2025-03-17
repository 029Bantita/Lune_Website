<?php
include 'db.php'; // เชื่อมต่อฐานข้อมูล
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์มหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) && is_numeric($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    try {
        // ตรวจสอบว่าสินค้านี้มีอยู่ในตะกร้าหรือยัง
        $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute([':user_id' => $user_id, ':product_id' => $product_id]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            // อัปเดตจำนวนสินค้า
            $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + :quantity WHERE user_id = :user_id AND product_id = :product_id");
        } else {
            // เพิ่มสินค้าใหม่ลงในตะกร้า
            $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)");
        }
        $stmt->execute([':user_id' => $user_id, ':product_id' => $product_id, ':quantity' => $quantity]);
        
        header("Location: cart.php");
        exit();
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// ดึงรายการสินค้าในตะกร้า
$stmt = $pdo->prepare("SELECT cart.cart_id, products.name, products.price, cart.quantity FROM cart JOIN products ON cart.product_id = products.product_id WHERE cart.user_id = :user_id");
$stmt->execute([':user_id' => $user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// คำนวณราคารวม
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
    <!-- Navbar -->
    <nav class="bg-yellow-950 p-4 flex justify-between items-center text-white">
      <!-- Logo + Title -->
      <div class="flex gap-3 items-center">
        <h1 class="font-bold text-5xl">Luné</h1>
        <p class="hidden md:block mt-6">Luné Official Website</p>
      </div>

      <!-- Menu Items (Hidden on small screens) -->
      <div class="hidden md:flex text-xl gap-4 space-x-4">
        <a href="index.php" class="hover:text-yellow-500">Home</a>
        <a href="items.php" class="hover:text-yellow-500">Items</a>
        <a href="login.php" class="hover:text-yellow-500"
          ><svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="currentColor"
            class="size-8"
          >
            <path
              fill-rule="evenodd"
              d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"
              clip-rule="evenodd"
            />
          </svg>
        </a>
        <a href="cart.php" class="hover:text-yellow-500 flex"
          ><svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="currentColor"
            class="size-8"
          >
            <path
              fill-rule="evenodd"
              d="M7.5 6v.75H5.513c-.96 0-1.764.724-1.865 1.679l-1.263 12A1.875 1.875 0 0 0 4.25 22.5h15.5a1.875 1.875 0 0 0 1.865-2.071l-1.263-12a1.875 1.875 0 0 0-1.865-1.679H16.5V6a4.5 4.5 0 1 0-9 0ZM12 3a3 3 0 0 0-3 3v.75h6V6a3 3 0 0 0-3-3Zm-3 8.25a3 3 0 1 0 6 0v-.75a.75.75 0 0 1 1.5 0v.75a4.5 4.5 0 1 1-9 0v-.75a.75.75 0 0 1 1.5 0v.75Z"
              clip-rule="evenodd"
            />
          </svg>
          (<span id="cart-count">0</span>)</a
        >
      </div>

      <!-- Hamburger Icon (Visible on small screens) -->
      <button id="menu-btn" class="md:hidden focus:outline-none">
        <svg
          class="w-8 h-8"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M4 6h16M4 12h16m-7 6h7"
          ></path>
        </svg>
      </button>
    </nav>

    <!-- Mobile Menu (Hidden by default) -->
    <div
      id="mobile-menu"
      class="hidden flex-col bg-yellow-950 text-white text-xl p-4 space-y-2 md:hidden"
    >
      <a href="index.php" class="block hover:bg-yellow-500 p-2 mobile-link"
        >Home</a
      >
      <a href="items.php" class="block hover:bg-yellow-500 p-2 mobile-link"
        >Items</a
      >
      <a href="login.php" class="block hover:bg-yellow-500 p-2 mobile-link"
        >Login</a
      >

      <a href="cart.html" class="block hover:bg-yellow-500 p-2 mobile-link"
        >Cart (<span id="cart-count"><?= count($cart_items) ?></span>)</a>
    </div>
    <!-- Shopping Cart -->
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
                            <th class="border-b p-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td class="p-2 border-b"><?= htmlspecialchars($item['name']) ?></td>
                                <td class="p-2 border-b">$<?= number_format($item['price'], 2) ?></td>
                                <td class="p-2 border-b"> <?= $item['quantity'] ?></td>
                                <td class="p-2 border-b">$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                                <td class="p-2 border-b">
                                    <form action="remove_from_cart.php" method="POST">
                                        <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                                        <button type="submit" class="text-red-500 hover:text-red-700">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p class="text-right font-semibold mt-4">Total: $<?= number_format($total_price, 2) ?></p>
                <button class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 mt-4">Checkout</button>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>

