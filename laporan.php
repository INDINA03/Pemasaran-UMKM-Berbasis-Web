<?php
include '../config/database.php';
if($_SESSION['role'] != 'admin') { header("Location: ../index.php"); exit; }

// Ambil data penjualan per bulan (6 bulan terakhir)
$query_grafik = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as bulan,
                    COUNT(*) as jumlah_pesanan,
                    SUM(total_harga) as total_penjualan
                 FROM pesanan 
                 WHERE status != 'pending'
                 GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                 ORDER BY bulan DESC
                 LIMIT 6";
$result_grafik = mysqli_query($conn, $query_grafik);

$bulan_labels = [];
$total_data = [];
while($row = mysqli_fetch_assoc($result_grafik)) {
    array_unshift($bulan_labels, $row['bulan']);
    array_unshift($total_data, $row['total_penjualan']);
}

// Produk terlaris
$query_top = "SELECT p.nama_produk, SUM(dp.jumlah) as total_terjual
              FROM detail_pesanan dp
              JOIN produk p ON dp.produk_id = p.id
              JOIN pesanan ps ON dp.pesanan_id = ps.id
              WHERE ps.status != 'pending'
              GROUP BY p.id
              ORDER BY total_terjual DESC
              LIMIT 5";
$top_products = mysqli_query($conn, $query_top);

// Ringkasan penjualan hari ini
$query_hari_ini = "SELECT COUNT(*) as pesanan_hari_ini, SUM(total_harga) as omset_hari_ini 
                   FROM pesanan 
                   WHERE DATE(created_at) = CURDATE() AND status != 'pending'";
$hari_ini = mysqli_fetch_assoc(mysqli_query($conn, $query_hari_ini));

// Ringkasan bulan ini
$query_bulan_ini = "SELECT COUNT(*) as pesanan_bulan_ini, SUM(total_harga) as omset_bulan_ini 
                    FROM pesanan 
                    WHERE MONTH(created_at) = MONTH(CURDATE()) 
                    AND YEAR(created_at) = YEAR(CURDATE())
                    AND status != 'pending'";
$bulan_ini = mysqli_fetch_assoc(mysqli_query($conn, $query_bulan_ini));
?>
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container mt-4">
    <h2>Laporan Penjualan UMKM</h2>
    
    <!-- Kartu Ringkasan -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Pesanan Hari Ini</h5>
                    <h3><?= $hari_ini['pesanan_hari_ini'] ?? 0 ?> pesanan</h3>
                    <p>Rp <?= number_format($hari_ini['omset_hari_ini'] ?? 0, 0, ',', '.') ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Pesanan Bulan Ini</h5>
                    <h3><?= $bulan_ini['pesanan_bulan_ini'] ?? 0 ?> pesanan</h3>
                    <p>Rp <?= number_format($bulan_ini['omset_bulan_ini'] ?? 0, 0, ',', '.') ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Total Semua Pesanan</h5>
                    <?php
                    $total_all = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total, SUM(total_harga) as omset FROM pesanan WHERE status != 'pending'"));
                    ?>
                    <h3><?= $total_all['total'] ?> pesanan</h3>
                    <p>Rp <?= number_format($total_all['omset'] ?? 0, 0, ',', '.') ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Grafik Penjualan -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Grafik Penjualan 6 Bulan Terakhir</h5>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Produk Terlaris</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr><th>Produk</th><th>Terjual</th></tr>
                        </thead>
                        <tbody>
                            <?php while($top = mysqli_fetch_assoc($top_products)): ?>
                            <tr>
                                <td><?= $top['nama_produk'] ?></td>
                                <td><?= $top['total_terjual'] ?> pcs</td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Laporan Detail -->
    <div class="card">
        <div class="card-header">
            <h5>Detail Penjualan per Bulan</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Jumlah Pesanan</th>
                        <th>Total Penjualan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query_detail = "SELECT 
                                        DATE_FORMAT(created_at, '%M %Y') as bulan,
                                        COUNT(*) as jumlah_pesanan,
                                        SUM(total_harga) as total_penjualan
                                    FROM pesanan 
                                    WHERE status != 'pending'
                                    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                                    ORDER BY created_at DESC";
                    $detail_result = mysqli_query($conn, $query_detail);
                    while($detail = mysqli_fetch_assoc($detail_result)):
                    ?>
                    <tr>
                        <td><?= $detail['bulan'] ?></td>
                        <td><?= $detail['jumlah_pesanan'] ?></td>
                        <td>Rp <?= number_format($detail['total_penjualan'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-3">
        <a href="dashboard_admin.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        <button onclick="window.print()" class="btn btn-primary">Cetak Laporan</button>
    </div>
</div>

<script>
// Grafik Penjualan
const ctx = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($bulan_labels) ?>,
        datasets: [{
            label: 'Total Penjualan (Rp)',
            data: <?= json_encode($total_data) ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});
</script>
</body>
</html>