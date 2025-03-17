<?php
session_start();
include 'db.php'; // ใช้ไฟล์เชื่อมต่อฐานข้อมูล

// เพิ่มสินค้า
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // ตรวจสอบและอัปโหลดรูปภาพ
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image);

        // ตรวจสอบว่ามีโฟลเดอร์ uploads หรือไม่ ถ้าไม่มีให้สร้าง
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // ย้ายไฟล์รูปภาพไปยังโฟลเดอร์
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            try {
                // บันทึกสินค้าเข้า Database โดยใช้ PDO
                $sql = "INSERT INTO products (name, description, price, stock, image) VALUES (:name, :description, :price, :stock, :image)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':name' => $name,
                    ':description' => $description,
                    ':price' => $price,
                    ':stock' => $stock,
                    ':image' => $image
                ]);
                echo "✅ เพิ่มสินค้าเรียบร้อย!";
            } catch (PDOException $e) {
                echo "❌ เกิดข้อผิดพลาด: " . $e->getMessage();
            }
        } else {
            echo "❌ อัปโหลดรูปภาพไม่สำเร็จ";
        }
    } else {
        echo "❌ กรุณาเลือกไฟล์ภาพ";
    }
}

// ลบสินค้า
if (isset($_GET['delete'])) {
    $product_id = $_GET['delete'];

    try {
        // ดึงข้อมูลสินค้าเพื่อลบไฟล์รูปภาพ
        $stmt = $pdo->prepare("SELECT image FROM products WHERE product_id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            // ลบไฟล์รูปภาพถ้ามี
            $image_path = "uploads/" . $product['image'];
            if (!empty($product['image']) && file_exists($image_path)) {
                unlink($image_path);
            }

            // ลบสินค้าจากฐานข้อมูล
            $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
            $stmt->execute([$product_id]);

            $_SESSION['success'] = "✅ ลบสินค้าสำเร็จ!";
        } else {
            $_SESSION['error'] = "❌ ไม่พบสินค้า!";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "❌ เกิดข้อผิดพลาด: " . $e->getMessage();
    }

    // รีเฟรชหน้า
    header("Location: product.php");
    exit();
}


// ดึงข้อมูลสินค้า
try {
    $stmt = $pdo->query("SELECT * FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("เกิดข้อผิดพลาดในการดึงข้อมูลสินค้า: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>จัดการสินค้า</title>
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

      <a href="cart.php" class="block hover:bg-yellow-500 p-2 mobile-link"
        >Cart (<span id="cart-count-mobile">0</span>)</a
      >
    </div>
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg mt-5">
        <h2 class="text-xl font-bold mb-4">เพิ่มสินค้า</h2>
        <form method="POST" enctype="multipart/form-data" class="mb-6">
            <input type="text" name="name" placeholder="ชื่อสินค้า" class="border p-2 w-full mb-2" required>
            <textarea name="description" placeholder="รายละเอียด" class="border p-2 w-full mb-2"></textarea>
            <input type="number" name="price" placeholder="ราคา" class="border p-2 w-full mb-2" required>
            <input type="number" name="stock" placeholder="สต็อก" class="border p-2 w-full mb-2" required>
            <input type="file" name="image" class="border p-2 w-full mb-2" required>
            <button type="submit" name="add_product" class="bg-yellow-950 text-white hover:bg-yellow-500 px-4 py-2">เพิ่มสินค้า</button>
        </form>

        <h2 class="text-xl font-bold mb-4">รายการสินค้า</h2>
        <table class="w-full border-collapse border">
            <tr class="bg-gray-200">
                <th class="border p-2">ID</th>
                <th class="border p-2">รูปภาพ</th>
                <th class="border p-2">ชื่อสินค้า</th>
                <th class="border p-2">ราคา</th>
                <th class="border p-2">สต็อก</th>
                <th class="border p-2">จัดการ</th>
            </tr>
            <?php foreach ($products as $row) { ?>
            <tr>
                <td class="border p-2"><?php echo $row['product_id']; ?></td>
                <td class="border p-2">
                    <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="w-16 h-16 object-cover">
                </td>
                <td class="border p-2"><?php echo htmlspecialchars($row['name']); ?></td>
                <td class="border p-2"><?php echo htmlspecialchars($row['price']); ?></td>
                <td class="border p-2"><?php echo htmlspecialchars($row['stock']); ?></td>
                <td class="border p-2 flex space-x-2">
    <a href="edit_product.php?id=<?php echo $row['product_id']; ?>" 
       class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-1 rounded-lg shadow-md transition" onclick="return confirm('⚠️ คุณแน่ใจหรือไม่ว่าต้องการแก้ไขสินค้านี้?');">
       ✏️ แก้ไข
    </a>
    <a href="product.php?delete=<?php echo $row['product_id']; ?>" 
       class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded-lg shadow-md transition"
       onclick="return confirm('⚠️ คุณแน่ใจหรือไม่ว่าต้องการลบสินค้านี้?');">
       🗑️ ลบ
    </a>
</td>

            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
