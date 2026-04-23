<?php
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
