<?php
include '../config/database.php';
if(!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$produk_id = $_GET['id'];
$query = "INSERT INTO keranjang (user_id, produk_id, jumlah) VALUES ('$user_id','$produk_id',1)";
mysqli_query($conn, $query);
header("Location: ../pages/keranjang.php");
?>