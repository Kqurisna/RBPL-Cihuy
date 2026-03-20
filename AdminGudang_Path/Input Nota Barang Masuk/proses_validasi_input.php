<?php
include "../../koneksi.php";

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

    $nama_baru = time() . "_" . $nama_file;
    $path_simpan = $folder . $nama_baru;

    move_uploaded_file($tmp_file, $path_simpan);

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
    // Ke halaman sukses
    header("Location: input_data_nota_berhasil.php");
    exit();
}
