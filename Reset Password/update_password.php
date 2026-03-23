<?php
$koneksi = mysqli_connect("localhost", "root", "", "pt_bumijaya");

$username = $_POST['username'];
$password = $_POST['password'];
$confirm  = $_POST['confirm'];

if (empty($password) || empty($confirm)) {
    header("Location: reset_password.php?username=$username&error=1");
    exit;
}

if ($password !== $confirm) {
    header("Location: reset_password.php?username=$username&error=1");
    exit;
}

mysqli_query($koneksi, "
    UPDATE akun 
    SET password = '$password'
    WHERE username = '$username'
");

header("Location: ../index.php?reset=success");
exit;
