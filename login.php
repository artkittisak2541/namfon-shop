<?php

session_start();
require 'db.php';

if (isset($_SESSION['user'])) {
  header("Location: shop.php");
  exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $remember = isset($_POST['remember']);

  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user'] = $user;
    if ($remember) {
      setcookie("remember_user", $username, time() + (86400 * 30), "/");
    } else {
      setcookie("remember_user", "", time() - 3600, "/");
    }
    header("Location: shop.php");
    exit();
  } else {
    $error = "❌ ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
  }
}
?>

<!-- HTML and CSS content omitted for brevity (included in next cell in full version) -->


<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <title>เข้าสู่ระบบ - ร้านน้ำฝนแฟชั่น</title>

  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
  

</head>
<body>
<style>
  /* ✔️ CSS Style ครบระบบ สำหรับหน้าล็อกอิน พร้อม Snow Effect */

body, html {
  margin: 0;
  padding: 0;
  font-family: 'Kanit', sans-serif;
  height: 100%;
  overflow-x: hidden;
}

/* 🔥 Background slideshow */
body::before {
  content: "";
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  z-index: -2;
  background-size: cover;
  background-position: center;
  animation: slideshow 24s infinite;
  filter: brightness(0.9);
}

body::after {
  content: "";
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  z-index: -1;
  background: linear-gradient(to bottom right, #fce4ecaa, #f8bbd0aa, #f3e5f5aa);
}

@keyframes slideshow {
  0%   { background-image: url('images/bg1.jpg'); }
  33%  { background-image: url('images/bg2.jpg'); }
  66%  { background-image: url('images/bg3.jpg'); }
  100% { background-image: url('images/bg1.jpg'); }
}

/* 🔲 Glass Card */
.glass-card {
  background: rgba(255, 255, 255, 0.25);
  backdrop-filter: blur(14px);
  border-radius: 20px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
  padding: 30px;
  max-width: 500px;
  margin: 100px auto;
  text-align: center;
  animation: fadeIn 1.8s ease;
  color: #333;
  transition: transform 0.6s ease;
}
.glass-card:hover {
  transform: rotateX(6deg) rotateY(-6deg);
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(30px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* 🔘 ปุ่ม */
.btn-pink, .btn-primary {
  background-color: #f48fb1;
  color: white;
  border: none;
  border-radius: 25px;
  padding: 10px 25px;
  transition: all 0.3s ease;
}

.btn-primary:hover, .btn-pink:hover {
  background-color: #ec407a;
  transform: scale(1.05);
}

.btn-outline-secondary {
  border: 1px solid #aaa;
  background-color: rgba(255,255,255,0.5);
}

/* ✨ Header */
header {
  position: sticky;
  top: 0;
  z-index: 1000;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 25px 50px;
  border-radius: 0 0 30px 30px;
  backdrop-filter: blur(14px);
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
  border-bottom: 1px solid rgba(255, 255, 255, 0.3);
  background: linear-gradient(270deg, #ffe4f1, #f3e5f5, #d1c4e9, #ffe0f0);
  background-size: 800% 800%;
  animation: gradientFlow 15s ease infinite;
  
}

@keyframes gradientFlow {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

header h1.animated-title {
  font-family: 'Playfair Display', serif;
  font-size: 2.5rem;
  background: linear-gradient(90deg, #ec407a, #8e24aa);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  animation: shimmer 4s linear infinite;
  background-size: 200% auto;
}

@keyframes shimmer {
  0% { background-position: -100%; }
  100% { background-position: 200%; }
}

nav a {
  margin: 0 15px;
  text-decoration: none;
  color: #333;
  font-weight: 500;
  font-size: 16px;
  position: relative;
}

nav a::after {
  content: "";
  display: block;
  width: 0;
  height: 2px;
  background: #e52e71;
  transition: width 0.3s;
  position: absolute;
  bottom: -5px;
  left: 0;
}

nav a:hover {
  color: #e52e71;
}
nav a:hover::after {
  width: 100%;
}

/* 🖼️ รูปภาพแอนิเมชัน */
.shadow-rounded-img, .shadow-rounded-img2 {
  object-fit: cover;
  border-radius: 16px;
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.25);
  transition: transform 0.3s ease;
  display: inline-block;
}

.shadow-rounded-img:hover, .shadow-rounded-img2:hover {
  transform: scale(1.07);
}

.shadow-rounded-img {
  width: 150px;
  height: 200px;
}

.shadow-rounded-img2 {
  width: 150px;
  height: 300px;
}

/* 🌊 Animation */
@keyframes waveFloat {
  0%   { transform: translateY(0) rotate(0deg); }
  25%  { transform: translateY(-6px) rotate(-0.7deg); }
  50%  { transform: translateY(0px) rotate(0.7deg); }
  75%  { transform: translateY(6px) rotate(-0.5deg); }
  100% { transform: translateY(0) rotate(0deg); }
}

.wave-animation {
  animation: waveFloat 6s ease-in-out infinite;
}

.wave-float {
  animation: waveFloat 7s ease-in-out infinite;
}

/* 🐱 แมวลอย */
.floating-image-container {
  position: absolute;
  top: 430px;
  right: 10px;
  z-index: 998;
}

/* ✨ Overlay ระยิบระยับ */
.sparkle-overlay {
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  z-index: 1;
  pointer-events: none;
  background-image:
    radial-gradient(circle, rgba(255,255,255,0.4) 2px, transparent 3px),
    radial-gradient(circle, rgba(255,255,255,0.2) 1px, transparent 2px);
  background-size: 100px 100px, 150px 150px;
  animation: sparkleMove 30s linear infinite;
}

@keyframes sparkleMove {
  0% { background-position: 0 0, 0 0; }
  100% { background-position: 1000px 1000px, 800px 800px; }
}

/* 👁️ Password toggle */
.input-group .btn {
  border-top-right-radius: 12px;
  border-bottom-right-radius: 12px;
}

/* ✨ Glow โลโก้ */
.logo {
  display: block;
  margin: 0 auto 20px;
  max-width: 140px;
  animation: glow 2s ease-in-out infinite alternate;
}
@keyframes glow {
  from { filter: drop-shadow(0 0 3px #ffb6c1); }
  to   { filter: drop-shadow(0 0 8px #ff69b4); }
}

/* 📦 Modal */
.login-modal-content {
  border-radius: 16px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25),
              0 0 25px rgba(255, 105, 180, 0.4),
              0 0 40px rgba(255, 182, 193, 0.3);
  background: linear-gradient(to right, #d7e2c1ff, #dadfb2ff);
  color: #333;
  border: 2px solid rgba(255, 192, 203, 0.6);
}

.modal-body .form-control {
  border-radius: 12px;
}

.modal-header {
  background: #ffffff80;
  border-bottom: none;
  border-top-left-radius: 16px;
  border-top-right-radius: 16px;
}

/* 📄 Footer */
footer {
  background-color: transparent;
  color: black;
  text-align: center;
  padding: 30px 20px;
  text-shadow: 0 1px 3px rgba(0,0,0,0.6);
}

.image-center-container {
  display: flex;
  justify-content: center;
  padding: 40px 20px;
}

.product-row {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 24px;
  max-width: 1200px;
}

.product-img {
  width: 160px;
  height: auto;
  object-fit: contain;
  border-radius: 12px;
  box-shadow: 0 6px 14px rgba(0, 0, 0, 0.2);
  transition: transform 0.4s ease, box-shadow 0.4s ease;
  animation: floatImage 6s ease-in-out infinite;
  position: relative;
  background-color: #ffb6c1;
}

.product-img:hover {
  transform: scale(1.12) rotate(-1deg);
  box-shadow: 0 8px 20px rgba(255, 105, 180, 0.3);
}

@keyframes floatImage {
  0%   { transform: translateY(0px); }
  50%  { transform: translateY(-6px); }
  100% { transform: translateY(0px); }
}

/* ป้ายแสดงบนมุมรูป เช่น “ใหม่”, “ลดราคา” */
.badge-overlay {
  position: absolute;
  top: 8px;
  left: 8px;
  background: linear-gradient(to right, #ff4081, #f06292);
  color: rgb(255, 255, 255);
  padding: 4px 12px;
  font-size: 0.75rem;
  font-weight: bold;
  border-radius: 8px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.3);
  z-index: 9;
}

/* ❄️ Snowfall Effect */
.snowflake {
  position: fixed;
  top: -10px;
  color: white;
  font-size: 1.2em;
  user-select: none;
  z-index: 9999;
  animation-name: fall;
  animation-timing-function: linear;
  animation-iteration-count: infinite;
  opacity: 0.8;
}

@keyframes fall {
  0% {
    transform: translateY(0) rotate(0deg);
    opacity: 0.8;
  }
  100% {
    transform: translateY(100vh) rotate(360deg);
    opacity: 0.3;
  }
}
/* รองรับขนาดหน้าจอเล็ก */
@media (max-width: 576px) {
  .modal-dialog {
    margin: 0 auto;
    width: 95%;
  }

  .login-modal-content {
    padding: 20px;
    font-size: 15px;
  }

  .modal-body .form-control {
    font-size: 15px;
  }

  .modal-title {
    font-size: 18px;
  }

  .product-img {
    width: 45%;
    margin-bottom: 16px;
  }

  .image-center-container {
    padding: 20px 10px;
  }

  nav a {
    font-size: 14px;
    margin: 0 8px;
  }

  header {
    flex-direction: column;
    padding: 15px 10px;
    text-align: center;
  }

  .btn {
    font-size: 15px;
    padding: 8px 16px;
  }

  .btn-outline-secondary,
  .btn-primary {
    width: 100%;
  }
}
.product-row {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 16px;
}

.product-img {
  width: 140px;
  max-width: 45vw;
}
/* 🎯 Responsive Header */
.responsive-header {
  backdrop-filter: blur(10px);
  background: linear-gradient(to right, #fce4ec, #f3e5f5);
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
  z-index: 999;
  flex-wrap: wrap;
}

.animated-title {
  font-size: 2rem;
  background: linear-gradient(90deg, #ec407a, #8e24aa);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  animation: shimmer 4s linear infinite;
}

@keyframes shimmer {
  0% { background-position: -100%; }
  100% { background-position: 200%; }
}

.main-nav a {
  text-decoration: none;
  color: #333;
  font-weight: 500;
  position: relative;
}
.snowflake {
  position: fixed;
  top: -10px;
  color: white;
  font-size: 1.2em;
  user-select: none;
  z-index: 2; /* ต่ำกว่า modal (9999), สูงกว่ bg */
  animation-name: fall;
  animation-timing-function: linear;
  animation-iteration-count: infinite;
  opacity: 0.8;
  pointer-events: none;
}

@keyframes fall {
  0% {
    transform: translateY(0) rotate(0deg);
    opacity: 0.9;
  }
  100% {
    transform: translateY(100vh) rotate(360deg);
    opacity: 0.2;
  }
}
@keyframes swingImage {
  0%   { transform: translateX(0); }
  25%  { transform: translateX(-6px) rotate(-1deg); }
  50%  { transform: translateX(6px) rotate(1deg); }
  75%  { transform: translateX(-4px) rotate(-0.5deg); }
  100% { transform: translateX(0); }
}

.swing-image {
  animation: swingImage 5s ease-in-out infinite;
}
@keyframes floatIn {
  0% {
    opacity: 0;
    transform: translateY(30px) scale(0.95);
  }
  100% {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

.product-img.animated-in {
  animation: floatIn 1s ease-out forwards;
}
.product-img.swing-image.animated-in {
  animation:
    floatIn 1s ease-out forwards,
    swingImage 5s ease-in-out infinite 1s; /* เริ่ม swing หลัง floatIn เสร็จ */
}
body::after {
  content: "";
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  z-index: -1;
  background: linear-gradient(to bottom right, var(--bg-overlay, #fce4ecaa), var(--bg-overlay, #f8bbd0aa));
}
:root {
  --bg-overlay: #fce4ecaa;
}
body::after {
  ...
  background: linear-gradient(to bottom right, var(--bg-overlay, #fce4ecaa), var(--bg-overlay, #f8bbd0aa));
}
body {
  transition: background 2s ease;
}

body::after {
  transition: background 2s ease;
}
:root {
  --bg-overlay: #fce4ecaa;
}

body::after {
  content: "";
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  z-index: -1;
  background: linear-gradient(to bottom right, var(--bg-overlay), var(--bg-overlay));
  transition: background 2s ease;
}


</style>

<header class="responsive-header d-flex justify-content-between align-items-center flex-wrap px-4 py-3">
  <div class="brand-title">
    <h1 class="animated-title m-0">ร้านน้ำฝนแฟชั่น</h1>
  </div>
  <nav class="main-nav d-flex align-items-center flex-wrap gap-3">
    <a href="index.php">หน้าแรก</a>
    <a href="show.php">สินค้า</a>
    <a href="about.php">เกี่ยวกับ</a>
    <a href="contact.php">ติดต่อ</a>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">เข้าสู่ระบบ</button>
  </nav>
</header>



<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content login-modal-content animate__animated animate__zoomIn">
      <div class="modal-header">
        <h5 class="modal-title">เข้าสู่ระบบ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
      </div>
      <div class="modal-body">
        <img src="images/logo1.png" alt="โลโก้" class="logo">
        <form method="post" autocomplete="on">
          <?php if ($error): ?>
            <div class="alert alert-danger animate__animated animate__shakeX"><?= $error ?></div>
          <?php endif; ?>
          <div class="mb-3">
            <label class="form-label">ชื่อผู้ใช้</label>
            <input type="text" name="username" class="form-control" required autocomplete="username" value="<?= $_COOKIE['remember_user'] ?? '' ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">รหัสผาน</label>
            <div class="input-group">
              <input type="password" name="password" id="password" class="form-control" required autocomplete="current-password">
              <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                <i id="toggleIcon" class="fa fa-eye"></i>
              </button>
            </div>
          </div>
          <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" name="remember" id="remember" <?= isset($_COOKIE['remember_user']) ? 'checked' : '' ?> >
            <label class="form-check-label" for="remember">จดจำรหัสผาน</label>
          </div>
          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary ripple">เข้าสู่ระบบ</button>
            <a href="register.php" class="btn btn-outline-secondary">ยังไม่มีบัญชี? สมัครสมาชิก</a>
            <a href="forgot_password.php" class="btn btn-link mt-2">ลืมรหัสผาน?</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal แสดงภาพสินค้า -->
<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-transparent border-0">
      <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
      <img id="modalImage" src="" class="img-fluid rounded shadow-lg" alt="แสดงภาพสินค้า" />
    </div>
  </div>
</div>

<div class="image-center-container">
  <div class="product-row">
<img src="images/5.png" alt="สินค้า1" class="product-img wave-animation-image" data-bs-toggle="modal" data-bs-target="#productModal" data-img="images/5.png" />
<img src="images/6.png" alt="สินค้า2" class="product-img wave-animation-image" data-bs-toggle="modal" data-bs-target="#productModal" data-img="images/6.png" />
<img src="images/7.png" alt="สินค้า3" class="product-img wave-animation-image" data-bs-toggle="modal" data-bs-target="#productModal" data-img="images/7.png" />
<img src="images/8.png" alt="สินค้า1" class="product-img wave-animation-image" data-bs-toggle="modal" data-bs-target="#productModal" data-img="images/8.png" />
<img src="images/9.png" alt="สินค้า2" class="product-img wave-animation-image" data-bs-toggle="modal" data-bs-target="#productModal" data-img="images/9.png" />
<img src="images/10.png" alt="สินค้า3" class="product-img wave-animation-image" data-bs-toggle="modal" data-bs-target="#productModal" data-img="images/10.png" />

  </div>
</div>
<div class="modal-body px-3 py-4">

<footer>
  <p>© 2025 ร้านน้ำฝนแฟชั่น. สงวนลิขสิทธิ์.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function togglePassword() {
    const input = document.getElementById("password");
    const icon = document.getElementById("toggleIcon");
    if (input.type === "password") {
      input.type = "text";
      icon.classList.remove("fa-eye");
      icon.classList.add("fa-eye-slash");
    } else {
      input.type = "password";
      icon.classList.remove("fa-eye-slash");
      icon.classList.add("fa-eye");
    }
  }
</script>
<?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && $error): ?>
<script>
  const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
  loginModal.show();
</script>
<?php endif; ?>
<!-- ❄️ Snowflakes -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    for (let i = 0; i < 30; i++) {
      let snowflake = document.createElement('div');
      snowflake.className = 'snowflake';
      snowflake.textContent = '❄';

      // สุ่มตำแหน่งและความเร็ว
      snowflake.style.left = Math.random() * 100 + 'vw';
      snowflake.style.animationDuration = (Math.random() * 10 + 5) + 's';
      snowflake.style.fontSize = (Math.random() * 10 + 10) + 'px';
      snowflake.style.opacity = Math.random();

      document.body.appendChild(snowflake);
    }
  });
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  const images = document.querySelectorAll('.product-img');
  images.forEach((img, index) => {
    setTimeout(() => {
      img.classList.add('animated-in');
    }, 400 * index); // หน่วง 0.4s ต่อภาพ
  });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  const images = document.querySelectorAll('.product-img');
  images.forEach((img, index) => {
    setTimeout(() => {
      img.classList.add('animated-in', 'swing-image');
    }, 400 * index);
  });
});
</script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('productModal');
    const modalImage = document.getElementById('modalImage');
    document.querySelectorAll('.product-img').forEach(img => {
      img.addEventListener('click', () => {
        const src = img.getAttribute('data-img');
        modalImage.src = src;
      });
    });
  });
</script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    function getRandomPastelColor() {
      const hue = Math.floor(Math.random() * 360);
      return `hsl(${hue}, 100%, 85%)`; // สีพาสเทลอ่อน
    }

    function updateBackgroundColor() {
      const color = getRandomPastelColor();
      document.body.style.setProperty('--bg-overlay', color + 'aa');
      document.body.style.background = color;
    }

    // เปลี่ยนสีทันทีตอนโหลด
    updateBackgroundColor();

    // เปลี่ยนสีทุก 10 วินาที
    setInterval(updateBackgroundColor, 5000);
  });
</script>


</body>
</html>
