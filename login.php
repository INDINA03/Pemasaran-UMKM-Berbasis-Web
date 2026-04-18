<?php
include '../config/database.php';
if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass = md5($_POST['password']);
    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$pass'");
    if(mysqli_num_rows($query) > 0) {
        $user = mysqli_fetch_assoc($query);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['nama'] = $user['nama'];
        header("Location: ../index.php");
    } else {
        echo "<script>alert('Login gagal'); window.location='login.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Login UMKM</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body>
<div class="container mt-5" style="max-width:500px">
    <h2>Login</h2>
    <form method="POST">
        <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
        <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
        <button type="submit" name="login" class="btn btn-primary">Login</button>
    </form>
    <p class="mt-2">Demo: admin@umkm.com / admin123 | budi@gmail.com / budi123</p>
</div>
</body>
</html>