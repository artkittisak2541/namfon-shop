<?php
if ($_SERVER['HTTP_HOST'] === 'localhost') {
  // 👉 ใช้งานบนเครื่องเรา (XAMPP) - MySQL
  $host = "localhost";
  $user = "root";
  $pass = "";
  $dbname = "shop_db";

  $conn = new mysqli($host, $user, $pass, $dbname);

  if ($conn->connect_error) {
    die("❌ MySQL Connection failed (localhost): " . $conn->connect_error);
  }

  $conn->set_charset("utf8mb4");
  if (!defined('DB_TYPE')) define('DB_TYPE', 'mysql');

} else {
  // 👉 ใช้งานบน Render - PostgreSQL
  $host = "dpg-d23o6nadbo4c7383o6qg-a";
  $dbname = "namfonshop_db";
  $user = "namfonshop_db_user";
  $pass = "gObGj49w4TEsZlZzGhNLzzXhQWKJH8eC";
  $port = "5432";

  $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$pass");

  if (!$conn) {
    die("❌ PostgreSQL Connection failed (Render): " . pg_last_error());
  }

  pg_query($conn, "SET client_encoding TO 'UTF8'");
  if (!defined('DB_TYPE')) define('DB_TYPE', 'pgsql');
}
?>
