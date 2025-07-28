<?php
if ($_SERVER['HTTP_HOST'] === 'localhost') {
  $host = "localhost";
  $user = "postgres";
  $pass = "1234";
  $dbname = "shop_db";
  $port = "5432";
} else {
  $host = "dpg-xxxxx.singapore-postgres.render.com"; // ✅ จาก Render
  $user = "namfonshop_db_user"; // ✅ จาก Render
  $pass = "gObGj49w4TEsZlZzGhNLzzXhQWKJH8eC"; // ✅ จาก Render
  $dbname = "namfonshop_db"; // ✅ จาก Render
  $port = "5432";
}

$conn = pg_connect("host=$host dbname=$dbname user=$user password=$pass port=$port");
if (!$conn) {
  die("❌ Connection failed: " . pg_last_error());
}
?>
