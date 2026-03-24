<?php
$koneksi = mysqli_connect("localhost", "root", "", "pt_bumijaya");

$username = isset($_POST['username']) ? trim($_POST['username']) : "";

$username = mysqli_real_escape_string($koneksi, $username);

$query = mysqli_query($koneksi, "
    SELECT * FROM akun 
    WHERE username = '$username'
");

if (mysqli_num_rows($query) > 0) {

    header("Location: reset_password.php?username=$username");
    exit;
} else {

    header("Location: lupa_password.php?error=1");
    exit;
}
