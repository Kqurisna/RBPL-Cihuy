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


    $cek = mysqli_query($koneksi, "SELECT * FROM validasi_kasir WHERE id_nota = '$id_nota'");

    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Nota sudah divalidasi!'); window.history.back();</script>";
        exit;
    }

    foreach ($status_barang as $no => $status) {

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
            $tmpName = $_FILES['foto_bukti']['tmp_name'][$no];

            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $allowed = ['jpg', 'jpeg', 'png'];
            if (!in_array($ext, $allowed)) {
                echo "<script>alert('Format file harus JPG/PNG!'); window.history.back();</script>";
                exit;
            }

            $namaFile = "bukti_" . uniqid() . "_" . $no . "." . $ext;

            move_uploaded_file($tmpName, "upload/" . $namaFile);
        }

        mysqli_query($koneksi, "
            INSERT INTO validasi_kasir (id_nota, hasil, keterangan, foto_bukti)
            VALUES ('$id_nota', '$status', '$keterangan', '$namaFile')
        ");
    }

    $finalStatus = "sesuai";

    foreach ($status_barang as $status) {
        if ($status == "cacat") {
            $finalStatus = "cacat";
            break;
        }
    }


    mysqli_query($koneksi, "
        UPDATE nota 
        SET status = '$finalStatus'
        WHERE id_nota = '$id_nota'
    ");

    header("Location: status_succes_cek_barang_fisik.php");
    exit;
}
?>