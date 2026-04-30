<?php
session_start();
include "koneksi.php";

$username = $_POST['username'];
$password = $_POST['password'];

$query = mysqli_query($conn, "SELECT * FROM akun WHERE username='$username'");
$data = mysqli_fetch_assoc($query);

if ($data && $password == $data['password']) {
    $_SESSION['id'] = $data['id'];
    $_SESSION['username'] = $data['username'];
    $_SESSION['nama'] = $data['nama'];
    $_SESSION['role'] = $data['role'];

    if ($data['role'] == "admin_gudang") {
        header("Location: User_Admin.php");
        exit();
    } else if ($data['role'] == "kasir_toko") {
        header("Location: User_Kasir.php");
        exit();
    } else if ($data['role'] == "manajer") {
        header("Location: User_Manajer.php");
        exit();
    }
} else {
    header("Location: index.php?error=1");
    exit();
}
