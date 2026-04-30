<?php
session_start();

$conn = new mysqli("localhost", "root", "", "pt_bumijaya");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'manajer') {
    header("Location: ../../index.php?error=role");
    exit();
}
$koneksi = mysqli_connect("localhost", "root", "", "pt_bumijaya");

$id_nota = $_POST['id_nota'];
$status = $_POST['status_keputusan'];
$catatan = $_POST['alasan_reject'] ?? null;

$id_akun = $_SESSION['id'];
if (!$status) {
    die("Status keputusan belum dipilih");
}

if ($status == 'approve') {

    mysqli_query($koneksi, "
        UPDATE nota 
        SET status_laporan = 'disetujui'
        WHERE id_nota = $id_nota
    ");
} else if ($status == 'reject') {

    if (!$catatan) {
        die("Catatan reject wajib diisi");
    }

    mysqli_query($koneksi, "
        UPDATE nota 
        SET status_laporan = 'ditolak'
        WHERE id_nota = $id_nota
    ");
}

mysqli_query($koneksi, "
    INSERT INTO approval_manager 
    (id_nota, id_akun, catatan_revisi, approved_at)
    VALUES (
        $id_nota,
        $id_akun,
        " . ($catatan ? "'$catatan'" : "NULL") . ",
        NOW()
    )
");

if ($status == 'approve') {

    header("Location: status_sucess_review_laporan_acc.php");
} else if ($status == 'reject') {

    header("Location: status_sucess_review_laporan_reject.php");
}

exit;
