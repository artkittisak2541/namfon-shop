<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
  header("Location: index.php");
  exit();
}

$search = $_GET['search'] ?? '';
if ($search) {
  $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ?");
  $like = "%$search%";
  $stmt->bind_param("s", $like);
  $stmt->execute();
  $result = $stmt->get_result();
} else {
  $result = $conn->query("SELECT * FROM products");
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ร้านน้ำฝนแฟชั่น</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <style>
    body {
      margin: 0;
      font-family: 'Sarabun', sans-serif;
      background-image: url('images/background1.png');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      min-height: 100vh;
      position: relative;
    }
    .sparkle-overlay {
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      pointer-events: none;
      background: radial-gradient(white 1px, transparent 1px);
      background-size: 50px 50px;
      animation: sparkleMove 60s linear infinite;
      z-index: 0;
    }
    @keyframes sparkleMove {
      0% { background-position: 0 0; }
      100% { background-position: 1000px 1000px; }
    }

    .snowflake {
      position: fixed;
      top: -10px;
      color: #fff;
      user-select: none;
      z-index: 1;
      animation-name: fall;
      animation-timing-function: linear;
    }
    @keyframes fall {
      0% { transform: translateY(0); opacity: 1; }
      100% { transform: translateY(100vh); opacity: 0; }
    }

    h1 {
      margin-bottom: 30px;
      color: #0d6efd;
      font-weight: bold;
    }
    .product {
      background: #ffffff;
      padding: 15px;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      margin-bottom: 30px;
      transition: transform 0.2s;
      text-align: center;
      position: relative;
    }
    .product:hover {
      transform: translateY(-5px);
    }
    .product img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      border-radius: 8px;
      margin-bottom: 10px;
      cursor: pointer;
    }
    .product h5 {
      font-size: 1.1rem;
      font-weight: 600;
      color: #333;
      margin-bottom: 5px;
      animation: floatText 3s ease-in-out infinite;
    }
    @keyframes floatText {
      0% { transform: translateY(0); }
      50% { transform: translateY(-2px); }
      100% { transform: translateY(0); }
    }
    .product p.text-secondary {
      font-size: 0.95rem;
      font-weight: 500;
      margin-bottom: 10px;
      background: linear-gradient(to right, #fceabb, #f7d989ff);
      padding: 3px 10px;
      border-radius: 10px;
      color: #222;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .tag-badge {
      position: absolute;
      top: 10px;
      left: 10px;
      padding: 4px 8px;
      border-radius: 6px;
      color: white;
      font-size: 0.75rem;
      font-weight: bold;
    }
    .tag-badge.new { background-color: #0d6efd; }
    .tag-badge.bestseller { background-color: #198754; }
    .tag-badge.sale { background-color: #dc3545; }
  </style>
</head>

<body class="container py-4">
  <div class="position-fixed top-0 end-0 m-3">
    <a href="logout.php" class="btn btn-danger">ออกจากระบบ</a>
  </div>

  <?php if (isset($_SESSION['user'])): ?>
    <a href="chat/chat.php" class="btn btn-outline-info mb-3">🗨️ พูดคุยกับร้านค้า</a>
  <?php endif; ?>

  <a href="my_orders.php" class="btn btn-outline-dark mb-3">📦 คำสั่งซื้อของฉัน</a>
  <a href="edit_profile.php" class="btn btn-outline-dark mb-3">👤 แก้ไขข้อมูลส่วนตัว</a>

  <h1 class="text-center">🛍️ สินค้าทั้งหมด</h1>

  <form method="get" class="mb-4">
    <input type="text" name="search" class="form-control" placeholder="ค้นหาสินค้า..." value="<?= htmlspecialchars($search) ?>">
  </form>

  <div class="row">
    <?php while ($row = $result->fetch_assoc()): ?>
    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
      <div class="product animate__animated animate__zoomIn">
        <?php if ($row['tag']): ?>
          <span class="badge tag-badge <?= $row['tag'] ?>">
            <?= $row['tag'] == 'new' ? 'ใหม่' : ($row['tag'] == 'bestseller' ? 'ขายดี' : 'ลดราคา') ?>
          </span>
        <?php endif; ?>
        <?php if ($row['image']): ?>
          <img src="images/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" data-bs-toggle="modal" data-bs-target="#imageModal" onclick="showImage('images/<?= htmlspecialchars($row['image']) ?>')">
        <?php else: ?>
          <div class="text-muted mb-2">ไม่มีรูป</div>
        <?php endif; ?>
        <h5>🏷️ <?= htmlspecialchars($row['name']) ?></h5>
        <p class="text-secondary">💸 ราคา: <?= $row['price'] ?> บาท</p>
        <form method="post" action="add_to_cart.php">
          <input type="hidden" name="id" value="<?= $row['id'] ?>">
          <input type="number" name="qty" value="0" min="0" class="form-control mb-2" required style="width: 70px; margin: 0 auto;">
          <button type="submit" class="btn btn-sm btn-primary">ใส่ตะกร้า</button>
        </form>
      </div>
    </div>
    <?php endwhile; ?>
  </div>

  <div class="text-center mt-4">
    <a href="cart.php" class="btn btn-success">🛒 ดูตะกร้าสินค้า</a>
  </div>

  <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <img id="modalImage" class="img-fluid rounded shadow" alt="แสดงรูป" />
      </div>
    </div>
  </div>

  <script>
    function showImage(src) {
      document.getElementById('modalImage').src = src;
    }

    // ❄️ Snowfall
    function createSnowflake() {
      const snowflake = document.createElement("div");
      snowflake.classList.add("snowflake");
      snowflake.textContent = "❄";
      snowflake.style.left = Math.random() * window.innerWidth + "px";
      snowflake.style.animationDuration = 4 + Math.random() * 5 + "s";
      snowflake.style.fontSize = 12 + Math.random() * 18 + "px";
      document.body.appendChild(snowflake);
      setTimeout(() => snowflake.remove(), 10000);
    }
    setInterval(createSnowflake, 300);

    // ✨ Sparkle Overlay
    const sparkle = document.createElement("div");
    sparkle.className = "sparkle-overlay";
    document.body.appendChild(sparkle);
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
