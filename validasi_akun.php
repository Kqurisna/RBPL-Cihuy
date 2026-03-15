<?php
session_start();
include "koneksi.php";

$username = $_POST['username'];
$password = $_POST['password'];

$query = mysqli_query($conn, "SELECT * FROM akun 
WHERE username='$username' AND password='$password'");

$data = mysqli_fetch_assoc($query);

if ($data) {

    $_SESSION['username'] = $data['username'];
    $_SESSION['role'] = $data['role'];


    if ($data['role'] == "admin_gudang") {
        header("Location: User_Admin.php");
    } else if ($data['role'] == "kasir_toko") {
        header("Location: User_Kasir.php");
    } else if ($data['role'] == "manajer") {
        header("Location: User_Manajer.php");
    }
} else {

    // Jika login gagal / tidak berhasil
    header("Location: index.php?error=1");
    exit();
}
