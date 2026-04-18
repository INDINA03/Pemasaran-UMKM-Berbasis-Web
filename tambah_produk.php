<?php
include '../config/database.php';
if($_SESSION['role'] != 'admin') { header("Location: ../index.php"); exit; }

if(isset($_POST['submit'])) {
    $nama = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $deskripsi = $_POST['deskripsi'];
    
    // Upload gambar
    $target_dir = "../uploads/";
    $file_name = time() . "_" . basename($_FILES["gambar"]["name"]);
    $target_file = $target_dir . $file_name;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Cek apakah file gambar asli
    $check = getimagesize($_FILES["gambar"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "<script>alert('File bukan gambar!'); window.location='../pages/tambah_produk_form.php';</script>";
        $uploadOk = 0;
    }
    
    // Cek ukuran file (max 2MB)
    if ($_FILES["gambar"]["size"] > 2000000) {
        echo "<script>alert('Ukuran file terlalu besar (max 2MB)!'); window.location='../pages/tambah_produk_form.php';</script>";
        $uploadOk = 0;
    }
    
    // Izinkan format tertentu
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        echo "<script>alert('Hanya file JPG, JPEG, PNG & GIF yang diperbolehkan!'); window.location='../pages/tambah_produk_form.php';</script>";
        $uploadOk = 0;
    }
    
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            $query = "INSERT INTO produk (nama_produk, harga, stok, deskripsi, gambar) 
                      VALUES ('$nama', '$harga', '$stok', '$deskripsi', '$file_name')";
            if(mysqli_query($conn, $query)) {
                echo "<script>alert('Produk berhasil ditambahkan!'); window.location='../pages/dashboard_admin.php';</script>";
            } else {
                echo "<script>alert('Gagal menyimpan ke database!'); window.location='../pages/tambah_produk_form.php';</script>";
            }
        } else {
            echo "<script>alert('Gagal upload gambar!'); window.location='../pages/tambah_produk_form.php';</script>";
        }
    }
}
?>