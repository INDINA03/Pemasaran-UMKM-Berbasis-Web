<?php
include '../config/database.php';
if($_SESSION['role'] != 'admin') { header("Location: ../index.php"); exit; }

// Update status pesanan
if(isset($_GET['update_status'])) {
    $id_pesanan = $_GET['id'];
    $status_baru = $_GET['status'];
    mysqli_query($conn, "UPDATE pesanan SET status='$status_baru' WHERE id='$id_pesanan'");
    header("Location: pesanan_admin.php");
}

$query = "SELECT p.*, u.nama as customer_nama 
          FROM pesanan p 
          JOIN users u ON p.user_id = u.id 
          ORDER BY p.created_at DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kelola Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Kelola Pesanan</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th>Alamat</th>
                <th>Metode Bayar</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['customer_nama'] ?></td>
                <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                <td>
                    <span class="badge bg-<?= 
                        $row['status'] == 'pending' ? 'warning' : 
                        ($row['status'] == 'diproses' ? 'info' : 
                        ($row['status'] == 'dikirim' ? 'primary' : 'success')) 
                    ?>">
                        <?= $row['status'] ?>
                    </span>
                </td>
                <td><?= $row['alamat'] ?></td>
                <td><?= $row['metode_pembayaran'] ?></td>
                <td><?= $row['created_at'] ?></td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <?php if($row['status'] == 'pending'): ?>
                            <a href="?update_status=1&id=<?= $row['id'] ?>&status=diproses" class="btn btn-info">Proses</a>
                        <?php elseif($row['status'] == 'diproses'): ?>
                            <a href="?update_status=1&id=<?= $row['id'] ?>&status=dikirim" class="btn btn-primary">Kirim</a>
                        <?php elseif($row['status'] == 'dikirim'): ?>
                            <a href="?update_status=1&id=<?= $row['id'] ?>&status=selesai" class="btn btn-success">Selesai</a>
                        <?php endif; ?>
                        <a href="detail_pesanan.php?id=<?= $row['id'] ?>" class="btn btn-secondary">Detail</a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="dashboard_admin.php" class="btn btn-secondary">Kembali</a>
</div>
</body>
</html>