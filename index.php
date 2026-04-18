<?php
include 'config/database.php';
$result = mysqli_query($conn, "SELECT * FROM produk");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>UMKM Mart - Pemasaran Produk UMKM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="index.php">UMKM Mart</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="pages/keranjang.php">Keranjang</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="auth/logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="auth/login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h1>Katalog Produk UMKM</h1>
    <div class="row">
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="images/<?= $row['gambar'] ?>" class="card-img-top" alt="<?= $row['nama_produk'] ?>" style="height:200px; object-fit:cover;">
                <div class="card-body">
                    <h5><?= $row['nama_produk'] ?></h5>
                    <p>Rp <?= number_format($row['harga'],0,',','.') ?></p>
                    <p>Stok: <?= $row['stok'] ?></p>
                    <a href="proses/tambah_keranjang.php?id=<?= $row['id'] ?>" class="btn btn-primary">Tambah ke Keranjang</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>