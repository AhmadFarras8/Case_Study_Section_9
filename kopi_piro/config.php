<?php
// XAMPP database settings
$host = "localhost";
$user = "root";
$pass = "";
$db   = "kopi_piro";
$port = 3306;

$conn = mysqli_connect($host, $user, $pass, $db, $port);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8mb4");
