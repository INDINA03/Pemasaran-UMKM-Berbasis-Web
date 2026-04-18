<?php
include '../config/database.php';
if($_SESSION['role'] != 'admin') { header("Location: ../index.php"); exit; }
$produk = mysqli_query($conn, "SELECT * FROM produk");
?>
<!DOCTYPE html>
<html>
<head><title>Admin UMKM</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body>
<div class="container mt-4">
    <h2>Dashboard Admin</h2>
    <a href="tambah_produk_form.php" class="btn btn-success mb-2">Tambah Produk</a>
    <table class="table">
        <tr><th>ID</th><th>Nama</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr>
        <?php while($p = mysqli_fetch_assoc($produk)): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td><?= $p['nama_produk'] ?></td>
            <td><?= $p['harga'] ?></td>
            <td><?= $p['stok'] ?></td>
            <td>
                <a href="edit_produk.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="../proses/hapus_produk.php?id=<?= $p['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <a href="pesanan_admin.php" class="btn btn-info">Kelola Pesanan</a>
    <a href="laporan.php" class="btn btn-secondary">Lihat Laporan</a>
</div>
</body>
</html>