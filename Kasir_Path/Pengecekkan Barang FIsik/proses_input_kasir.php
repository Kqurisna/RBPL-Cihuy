<?php
$koneksi = mysqli_connect("localhost", "root", "", "pt_bumijaya");

if (isset($_POST['submit'])) {

    if (!isset($_POST['id_nota']) || empty($_POST['id_nota'])) {
        die("ID Nota tidak ditemukan!");
    }

    $id_nota = $_POST['id_nota'];

    if (!isset($_POST['status_barang'])) {
        die("Status barang belum dipilih!");
    }

    $status_barang = $_POST['status_barang'];
    $keluhan = isset($_POST['keluhan']) ? $_POST['keluhan'] : [];
    $id_detail_list = $_POST['id_detail'];
    $cek = mysqli_query($koneksi, "SELECT * FROM validasi_kasir WHERE id_nota = '$id_nota'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Nota sudah divalidasi!'); window.history.back();</script>";
        exit;
    }

    $folder = "uploads/bukti/";

    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    foreach ($status_barang as $no => $status) {
        $id_detail = $id_detail_list[$no];
        $keterangan = isset($keluhan[$no]) ? $keluhan[$no] : "";
        $namaFile = "";

        if ($status == "cacat") {
            if (!isset($_FILES['foto_bukti']['name'][$no]) || $_FILES['foto_bukti']['name'][$no] == "") {
                echo "<script>alert('Barang cacat wajib upload foto!'); window.history.back();</script>";
                exit;
            }
        }

        if (isset($_FILES['foto_bukti']['name'][$no]) && $_FILES['foto_bukti']['name'][$no] != "") {

            $fileName = $_FILES['foto_bukti']['name'][$no];
            $tmpName  = $_FILES['foto_bukti']['tmp_name'][$no];

            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $allowed = ['jpg', 'jpeg', 'png'];
            if (!in_array($ext, $allowed)) {
                echo "<script>alert('Format file harus JPG/PNG!'); window.history.back();</script>";
                exit;
            }

            $cleanName = preg_replace("/[^a-zA-Z0-9]/", "_", pathinfo($fileName, PATHINFO_FILENAME));

            $namaFile = "bukti_" . uniqid() . "_" . $no . "_" . $cleanName . "." . $ext;

            move_uploaded_file($tmpName, $folder . $namaFile);
        }

        mysqli_query($koneksi, "
            INSERT INTO validasi_kasir 
            (id_nota, id_detail, hasil, keterangan, foto_bukti)
            VALUES 
            ('$id_nota', '$id_detail', '$status', '$keterangan', '$namaFile')
        ");
    }

    $finalStatus = "sesuai";
    $semuaSesuai = true;

    foreach ($status_barang as $status) {
        if ($status == "cacat") {
            $finalStatus = "cacat";
            $semuaSesuai = false;
            break;
        }
    }

    if ($semuaSesuai) {
        $status_pemeriksaan = "sudah";
    } else {
        $status_pemeriksaan = "belum";
    }

    mysqli_query($koneksi, "
    UPDATE nota 
    SET 
        status = '$finalStatus',
        status_pemeriksaan = '$status_pemeriksaan'
    WHERE id_nota = '$id_nota'
");
    header("Location: status_succes_cek_barang_fisik.php");
    exit;
}
