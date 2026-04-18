<?php
include '../config/database.php';
if($_SESSION['role'] != 'admin') { header("Location: ../index.php"); exit; }

$id = $_GET['id'];
// Ambil nama gambar
$query = mysqli_query($conn, "SELECT gambar FROM produk WHERE id='$id'");
$produk = mysqli_fetch_assoc($query);

// Hapus file gambar
if($produk['gambar'] != "" && file_exists("../uploads/".$produk['gambar'])) {
    unlink("../uploads/".$produk['gambar']);
}

// Hapus dari database
mysqli_query($conn, "DELETE FROM produk WHERE id='$id'");
header("Location: ../pages/dashboard_admin.php");
?>