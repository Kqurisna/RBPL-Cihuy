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
$conn = mysqli_connect("localhost", "root", "", "pt_bumijaya");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $nomor_nota   = $_POST['nomer_nota'];
    $tanggal_nota = $_POST['tanggal_nota'];
    $supplier     = $_POST['supplier'];
    $jenis_barang = $_POST['jenis_barang'];

    $folder = "uploads/nota/";

    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $nama_file = $_FILES['foto_nota']['name'];
    $tmp_file  = $_FILES['foto_nota']['tmp_name'];

    if (!empty($nama_file)) {

        $ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png', 'pdf'];

        if (in_array($ext, $allowed)) {

            $nama_baru = "nota_" . time() . "." . $ext;

            $path_simpan = $folder . $nama_baru;

            if (move_uploaded_file($tmp_file, $path_simpan)) {
                $path_simpan = $folder . $nama_baru;
            } else {
                die("Gagal upload file!");
            }
        } else {
            die("Format file tidak diizinkan!");
        }
    } else {
        $path_simpan = "";
    }

    $sql_nota = "INSERT INTO nota 
    (nomor_nota, tanggal_nota, supplier, jenis_barang, foto_nota) 
    VALUES 
    ('$nomor_nota', '$tanggal_nota', '$supplier', '$jenis_barang', '$path_simpan')";

    if (!$conn->query($sql_nota)) {
        die("Gagal simpan nota: " . $conn->error);
    }

    $id_nota = $conn->insert_id;

    $barang = $_POST['barang'];
    $jumlah = $_POST['jumlah'];

    for ($i = 0; $i < count($barang); $i++) {

        $nama_barang   = trim($barang[$i]);
        $jumlah_barang = trim($jumlah[$i]);

        if ($nama_barang == "" || $jumlah_barang == "") continue;

        $sql_detail = "INSERT INTO detail_barang 
        (id_nota, nama_barang, jumlah_barang)
        VALUES 
        ('$id_nota', '$nama_barang', '$jumlah_barang')";

        if (!$conn->query($sql_detail)) {
            die("Gagal simpan barang: " . $conn->error);
        }
    }
    header("Location: input_data_nota_berhasil.php");
    exit();
}
