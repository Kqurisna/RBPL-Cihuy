<?php
session_start();

$conn = new mysqli("localhost", "root", "", "pt_bumijaya");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin_gudang') {
    header("Location: ../../index.php?error=role");
    exit();
}
require_once "Nota.php";

$koneksi = mysqli_connect("localhost", "root", "", "pt_bumijaya");

if (!isset($_GET['id'])) {
    die("ID tidak ditemukan");
}

$id = intval($_GET['id']);

$nota = new Nota($koneksi);

$hasil = $nota->arsipkanNota($id);

if ($hasil) {
    header("Location: status_sukses_arsip_nota.php");
} else {
    header("Location: arsipkan_nota.php?status=gagal");
}
exit;
