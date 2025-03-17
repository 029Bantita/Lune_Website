
<?php
include 'db.php';

$products = [];

try {
    $sql = "SELECT name, description, price, stock, image FROM products";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Luné Items</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="js/main.js"></script>
  </head>
  </style>
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

    <!-- Hero Section -->
    <section class="text-center p-8">
      <h2 class="text-4xl font-extrabold text-brown-700">Items</h2>
      <h3 class="text-2xl font-bold">รายการ</h3>
    </section>

    <div class="mx-auto flex">
      <!-- Sidebar -->
      <div class="w-1/4 p-5">
        <h2 class="text-xl font-bold mb-4">ตัวกรองสินค้า</h2>

        <!-- ประเภท -->
        <div class="mb-4">
          <h3 class="font-semibold">ประเภท</h3>
          <div class="mt-2 space-y-1">
            <label class="flex items-center">
              <input type="checkbox" class="mr-2 accent-yellow-500" /> เสื้อ
            </label>
            <label class="flex items-center">
              <input type="checkbox" class="mr-2 accent-yellow-500" /> กระโปรง
            </label>
            <label class="flex items-center">
              <input type="checkbox" class="mr-2 accent-yellow-500" /> ชุดเดรส
            </label>
            <label class="flex items-center">
              <input type="checkbox" class="mr-2 accent-yellow-500" /> ชุดกางเกง
            </label>
            <label class="flex items-center">
              <input type="checkbox" class="mr-2 accent-yellow-500" /> ชุดรัดรูป
            </label>
            <label class="flex items-center">
              <input type="checkbox" class="mr-2 accent-yellow-500" /> เสื้อคลุม
            </label>
            <label class="flex items-center">
              <input type="checkbox" class="mr-2 accent-yellow-500" /> เอี๊ยม
            </label>
          </div>
        </div>

        <!-- ไซต์ -->
        <div class="mb-4">
          <h3 class="font-semibold">ไซส์</h3>
          <div class="mt-2 space-y-1">
            <label class="flex items-center">
              <input type="checkbox" class="mr-2" /> ไซต์เดียว
            </label>
            <label class="flex items-center">
              <input type="checkbox" class="mr-2 accent-yellow-500" /> XXS
            </label>
            <label class="flex items-center">
              <input type="checkbox" class="mr-2 accent-yellow-500" /> XS
            </label>
            <label class="flex items-center">
              <input type="checkbox" class="mr-2 accent-yellow-500" /> S
            </label>
            <label class="flex items-center">
              <input type="checkbox" class="mr-2 accent-yellow-500" /> M
            </label>
            <label class="flex items-center">
              <input type="checkbox" class="mr-2 accent-yellow-500" /> L
            </label>
          </div>
        </div>

        <!-- ความยาวแขนเสื้อ -->
        <div class="mb-4">
          <h3 class="font-semibold">ความยาวแขนเสื้อ</h3>
          <div class="mt-2 space-y-1">
            <label class="flex items-center">
              <input type="checkbox" class="mr-2 accent-yellow-500" /> แขนสั้น
            </label>
            <label class="flex items-center">
              <input type="checkbox" class="mr-2 accent-yellow-500" /> แขนยาว
            </label>
            <label class="flex items-center">
              <input type="checkbox" class="mr-2 accent-yellow-500" />
              เสื้อแขนกุด
            </label>
          </div>
        </div>

        <!-- วัสดุ -->
        <div class="mb-4">
          <h3 class="font-semibold">วัสดุ</h3>
          <div class="mt-2 space-y-1">
            <label class="flex items-center">
              <input type="checkbox" class="mr-2 accent-yellow-500" /> ผ้าฝ้าย
            </label>
            <label class="flex items-center">
              <input type="checkbox" class="mr-2 accent-yellow-500" />
              โพลีเอสเตอร์
            </label>
            <label class="flex items-center">
              <input type="checkbox" class="mr-2 accent-yellow-500" /> ไหมพรม
            </label>
          </div>
        </div>

        <div class="mb-4">
          <h3 class="font-semibold">ช่วงราคา</h3>
          <input
            id="priceRange"
            type="range"
            min="0"
            max="1000"
            class="w-full mt-2 accent-yellow-500"
            oninput="updatePrice(this.value)"
          />
          <p class="text-center mt-2 text-lg font-semibold text-yellow-950">
            ฿<span id="priceValue">0</span>
          </p>
        </div>

        <script>
          function updatePrice(value) {
            document.getElementById("priceValue").textContent = value;
          }
        </script>
      </div>

      <!-- Product List -->
      <div
        class="w-2/3 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4"
      >
      <?php foreach ($products as $product): ?>
        <div class="bg-white shadow-md rounded-lg p-4">
            <img src="uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-50 object-cover rounded">
            <h2 class="text-xl font-semibold"><?= htmlspecialchars($product['name']) ?></h2>
            <p class="text-gray-700"><?= htmlspecialchars($product['description']) ?></p>
            <p class="text-green-600 font-bold">$<?= number_format($product['price'], 2) ?></p>
            <p class="text-gray-500">Stock: <?= $product['stock'] ?></p>
                <button type="submit" class="bg-yellow-950 text-white px-4 py-2 rounded w-full mt-2 hover:bg-yellow-500">Add to Cart</button>
          
        </div>
    <?php endforeach; ?>

      </div>
    </div>
    <!-- Footer -->
    <footer class="bg-yellow-950 p-6 mt-8 text-center">
      <div class="container mx-auto">
        <p class="text-white font-semibold">
          © 2025 Luné Official Website. All Rights Reserved.
        </p>
        <div class="flex justify-center space-x-4 mt-2">
          <a href="#" class="text-white hover:text-brown-900">Privacy Policy</a>
          <span>|</span>
          <a href="#" class="text-white hover:text-brown-900"
            >Terms of Service</a
          >
          <span>|</span>
          <a href="contact.html" class="text-white hover:text-brown-900"
            >Contact TH</a
          >
        </div>
      </div>
    </footer>
  </body>
</html>

<!-- JavaScript for toggling menu -->
<script>
  const menuBtn = document.getElementById("menu-btn");
  const mobileMenu = document.getElementById("mobile-menu");
  const menuLinks = document.querySelectorAll(".mobile-link");

  // Toggle menu when clicking the hamburger button
  menuBtn.addEventListener("click", () => {
    mobileMenu.classList.toggle("hidden");
  });

  // Close menu when clicking any link inside mobile menu
  menuLinks.forEach((link) => {
    link.addEventListener("click", () => {
      mobileMenu.classList.add("hidden");
    });
  });
</script>

<!-- main.js -->
<script>
  let cart = JSON.parse(localStorage.getItem("cart")) || [];

  function addToCart(name, price) {
    let item = cart.find((item) => item.name === name);
    if (item) {
      item.quantity++;
    } else {
      cart.push({ name, price, quantity: 1 });
    }
    updateCart();
  }

  function updateCart() {
    localStorage.setItem("cart", JSON.stringify(cart));
    document.getElementById("cart-count").innerText = cart.reduce(
      (acc, item) => acc + item.quantity,
      0
    );
  }
</script>

<!-- cart.js -->
<script>
  function loadCart() {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    let cartItems = document.getElementById("cart-items");
    let emptyCart = document.getElementById("empty-cart");
    cartItems.innerHTML = "";

    if (cart.length === 0) {
      emptyCart.style.display = "block";
      return;
    }
    emptyCart.style.display = "none";
    cart.forEach((item, index) => {
      let li = document.createElement("li");
      li.innerHTML = `${item.name} - $${item.price} x ${item.quantity} <button onclick="removeItem(${index})">Remove</button>`;
      cartItems.appendChild(li);
    });
  }

  function removeItem(index) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    cart.splice(index, 1);
    localStorage.setItem("cart", JSON.stringify(cart));
    loadCart();
  }

  function checkout() {
    alert("Checkout successful!");
    localStorage.removeItem("cart");
    loadCart();
  }

  document.addEventListener("DOMContentLoaded", loadCart);
</script>
