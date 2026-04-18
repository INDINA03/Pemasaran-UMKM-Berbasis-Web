<?php
include '../config/database.php';
if(!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }
$user_id = $_SESSION['user_id'];
if(isset($_POST['checkout'])) {
    $alamat = $_POST['alamat'];
    $metode = $_POST['metode'];
    
    // Hitung total
    $query_total = mysqli_query($conn, "SELECT SUM(produk.harga * keranjang.jumlah) as total FROM keranjang JOIN produk ON keranjang.produk_id=produk.id WHERE keranjang.user_id='$user_id'");
    $total = mysqli_fetch_assoc($query_total)['total'];
    
    // Insert pesanan
    mysqli_query($conn, "INSERT INTO pesanan (user_id, total_harga, status, alamat, metode_pembayaran) VALUES ('$user_id','$total','pending','$alamat','$metode')");
    $pesanan_id = mysqli_insert_id($conn);
    
    // Insert detail pesanan
    $keranjang = mysqli_query($conn, "SELECT * FROM keranjang WHERE user_id='$user_id'");
    while($item = mysqli_fetch_assoc($keranjang)) {
        $produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT harga FROM produk WHERE id=".$item['produk_id']));
        mysqli_query($conn, "INSERT INTO detail_pesanan (pesanan_id, produk_id, jumlah, harga) VALUES ('$pesanan_id','".$item['produk_id']."','".$item['jumlah']."','".$produk['harga']."')");
        // Kurangi stok
        mysqli_query($conn, "UPDATE produk SET stok = stok - ".$item['jumlah']." WHERE id=".$item['produk_id']);
    }
    // Hapus keranjang
    mysqli_query($conn, "DELETE FROM keranjang WHERE user_id='$user_id'");
    echo "<script>alert('Pesanan berhasil!'); window.location='pesanan_saya.php';</script>";
}
?>
<!DOCTYPE html>
<html>
<head><title>Checkout</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body>
<div class="container mt-4">
    <h2>Form Checkout</h2>
    <form method="POST">
        <textarea name="alamat" class="form-control mb-2" placeholder="Alamat lengkap" required></textarea>
        <select name="metode" class="form-control mb-2" required>
            <option value="transfer">Transfer Bank</option>
            <option value="cod">COD (Bayar di Tempat)</option>
        </select>
        <button type="submit" name="checkout" class="btn btn-primary">Pesan Sekarang</button>
    </form>
</div>
</body>
</html>