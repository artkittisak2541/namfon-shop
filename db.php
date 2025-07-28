<?php
if ($_SERVER['HTTP_HOST'] === 'localhost') {
  // ใช้งานบนเครื่องเรา (XAMPP)
  $host = "localhost";
  $user = "root";
  $pass = "";
  $dbname = "shop_db";  // ✅ แก้ให้ตรงกับฐานข้อมูลที่ใช้อยู่จริง
} else {
  // ใช้งานบนโฮสต์จริง (InfinityFree)
  $host = "sql102.infinityfree.com";
  $user = "if0_39508686";
  $pass = "DBmvRQd9LG";
  $dbname = "if0_39508686_shop_db";
}

$conn = new mysqli($host, $user, $pass, $dbname);
$conn = new mysqli("localhost", "root", "", "ชื่อฐานข้อมูลใน phpMyAdmin");

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
