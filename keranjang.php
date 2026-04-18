<?php
include '../config/database.php';
if(!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }
$user_id = $_SESSION['user_id'];
$query = "SELECT keranjang.*, produk.nama_produk, produk.harga FROM keranjang JOIN produk ON keranjang.produk_id=produk.id WHERE keranjang.user_id='$user_id'";
$result = mysqli_query($conn, $query);
$total = 0;
?>
<!DOCTYPE html>
<html>
<head><title>Keranjang Belanja</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body>
<div class="container mt-4">
    <h2>Keranjang Saya</h2>
    <table class="table table-bordered">
        <tr><th>Produk</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th></tr>
        <?php while($row = mysqli_fetch_assoc($result)): 
            $subtotal = $row['harga'] * $row['jumlah'];
            $total += $subtotal;
        ?>
        <tr>
            <td><?= $row['nama_produk'] ?></td>
            <td>Rp <?= number_format($row['harga'],0,',','.') ?></td>
            <td><?= $row['jumlah'] ?></td>
            <td>Rp <?= number_format($subtotal,0,',','.') ?></td>
        </tr>
        <?php endwhile; ?>
        <tr><td colspan="3" align="right"><strong>Total</strong></td><td>Rp <?= number_format($total,0,',','.') ?></td></tr>
    </table>
    <a href="checkout.php" class="btn btn-success">Checkout</a>
</div>
</body>
</html>