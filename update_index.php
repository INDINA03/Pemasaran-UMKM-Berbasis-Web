<!-- Di bagian card produk, ubah img src menjadi: -->
<img src="uploads/<?= $row['gambar'] ?>" class="card-img-top" alt="<?= $row['nama_produk'] ?>" style="height:200px; object-fit:cover;">

<!-- Tambahkan fallback jika gambar tidak ada -->
<?php if($row['gambar'] && file_exists("uploads/".$row['gambar'])): ?>
    <img src="uploads/<?= $row['gambar'] ?>" ...>
<?php else: ?>
    <img src="https://via.placeholder.com/300x200?text=No+Image" ...>
<?php endif; ?>