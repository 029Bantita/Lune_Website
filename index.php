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
    <title>Luné Official Website</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="js/main.js"></script>
  </head>
  <!-- CSS สำหรับปรับสีปุ่มและจุด -->
  <style>
    .swiper-button-next,
    .swiper-button-prev {
      color: lightgray !important;
    }

    .swiper-button-next:hover,
    .swiper-button-prev:hover {
      color: yellow !important;
    }

    .swiper-pagination-bullet {
      background-color: lightgray !important;
      opacity: 0.6;
    }

    .swiper-pagination-bullet-active {
      background-color: yellow !important;
      opacity: 1;
    }
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

    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css"
    />
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

    <!-- Image Slider -->
    <section class="w-full mx-auto">
      <div class="swiper mySwiper">
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <img
              src="src/ime/01.png"
              class="w-full opacity-70 transition-opacity hover:opacity-100"
            />
          </div>
          <div class="swiper-slide">
            <img
              src="src/ime/02.png"
              class="w-full opacity-70 transition-opacity hover:opacity-100"
            />
          </div>
          <div class="swiper-slide">
            <img
              src="src/ime/03.png"
              class="w-full opacity-70 transition-opacity hover:opacity-100"
            />
          </div>
          <div class="swiper-slide">
            <img
              src="src/ime/04.png"
              class="w-full opacity-70 transition-opacity hover:opacity-100"
            />
          </div>
          <div class="swiper-slide">
            <img
              src="src/ime/05.png"
              class="w-full opacity-70 transition-opacity hover:opacity-100"
            />
          </div>
        </div>

        <!-- ปุ่มเลื่อนซ้าย-ขวา -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>

        <!-- จุดแสดงสถานะ -->
        <div class="swiper-pagination"></div>
      </div>
    </section>

    <!-- Hero Section -->
    <section class="text-center p-8">
      <h2 class="text-4xl font-extrabold text-brown-700">Recommended Items</h2>
      <h3 class="text-2xl font-bold">รายการแนะนำ</h3>
    </section>

    <!-- Product List -->
    <div class="container mx-auto p-6">
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4 p-4">
            <div class="bg-white p-4 rounded-xl shadow-lg">
                <img src="uploads/1.png" alt="1" class="w-full rounded" />
                <h3 class="text-xl font-semibold mt-2">Luné</h3>
                <p class="text-gray-700">เสื้อสีขาว เเขนตุ๊กตา</p>
                <p class="text-green-600 font-bold">$120.00</p>
                <p class="text-gray-500">Stock: 32</p>
                <button type="submit" class="bg-yellow-950 text-white px-4 py-2 rounded w-full mt-2 hover:bg-yellow-500">Add to Cart</button>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-lg">
                <img src="uploads/2.png" alt="1" class="w-full rounded" />
                <h3 class="text-xl font-semibold mt-2">Luné</h3>
                <p class="text-gray-700">เสื้อสีน้ำตาลปาดไหล่</p>
                <p class="text-green-600 font-bold">$110.00</p>
                <p class="text-gray-500">Stock: 36</p>
                <button type="submit" class="bg-yellow-950 text-white px-4 py-2 rounded w-full mt-2 hover:bg-yellow-500">Add to Cart</button>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-lg">
                <img src="uploads/3.png" alt="1" class="w-full rounded" />
                <h3 class="text-xl font-semibold mt-2">Luné</h3>
                <p class="text-gray-700">ชุดเซ้ต 2 ชิ้น สีขาว</p>
                <p class="text-green-600 font-bold">$259.00</p>
                <p class="text-gray-500">Stock: 19</p>
                <button type="submit" class="bg-yellow-950 text-white px-4 py-2 rounded w-full mt-2 hover:bg-yellow-500">Add to Cart</button>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-lg">
                <img src="uploads/4.png" alt="1" class="w-full rounded" />
                <h3 class="text-xl font-semibold mt-2">Luné</h3>
                <p class="text-gray-700">เสื้อลายดอกไม้ สีน้ำตาล แขนยาว</p>
                <p class="text-green-600 font-bold">$150.00</p>
                <p class="text-gray-500">Stock: 24</p>
                <button type="submit" class="bg-yellow-950 text-white px-4 py-2 rounded w-full mt-2 hover:bg-yellow-500">Add to Cart</button>
            </div>
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

<!-- JavaScript สำหรับ Swiper -->
<script>
  var swiper = new Swiper(".mySwiper", {
    slidesPerView: 1,
    spaceBetween: 10,
    loop: true,
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });
</script>

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
