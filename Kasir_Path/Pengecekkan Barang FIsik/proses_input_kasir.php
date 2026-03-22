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

    // ✅ CEK SUDAH VALIDASI
    $cek = mysqli_query($koneksi, "SELECT * FROM validasi_kasir WHERE id_nota = '$id_nota'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Nota sudah divalidasi!'); window.history.back();</script>";
        exit;
    }

    // ✅ FOLDER BARU UNTUK FOTO BUKTI
    $folder = "uploads/bukti/";

    // jika folder belum ada, buat otomatis
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    foreach ($status_barang as $no => $status) {

        $keterangan = isset($keluhan[$no]) ? $keluhan[$no] : "";
        $namaFile = "";

        // ✅ WAJIB UPLOAD JIKA CACAT
        if ($status == "cacat") {
            if (!isset($_FILES['foto_bukti']['name'][$no]) || $_FILES['foto_bukti']['name'][$no] == "") {
                echo "<script>alert('Barang cacat wajib upload foto!'); window.history.back();</script>";
                exit;
            }
        }

        // ✅ PROSES UPLOAD
        if (isset($_FILES['foto_bukti']['name'][$no]) && $_FILES['foto_bukti']['name'][$no] != "") {

            $fileName = $_FILES['foto_bukti']['name'][$no];
            $tmpName  = $_FILES['foto_bukti']['tmp_name'][$no];

            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // validasi format
            $allowed = ['jpg', 'jpeg', 'png'];
            if (!in_array($ext, $allowed)) {
                echo "<script>alert('Format file harus JPG/PNG!'); window.history.back();</script>";
                exit;
            }

            // ✅ BERSIHKAN NAMA FILE (hindari emoji & spasi)
            $cleanName = preg_replace("/[^a-zA-Z0-9]/", "_", pathinfo($fileName, PATHINFO_FILENAME));

            // nama file unik
            $namaFile = "bukti_" . uniqid() . "_" . $no . "_" . $cleanName . "." . $ext;

            // upload ke folder bukti
            move_uploaded_file($tmpName, $folder . $namaFile);
        }

        // ✅ SIMPAN KE DATABASE
        mysqli_query($koneksi, "
            INSERT INTO validasi_kasir (id_nota, hasil, keterangan, foto_bukti)
            VALUES ('$id_nota', '$status', '$keterangan', '$namaFile')
        ");
    }

    // ✅ CEK STATUS AKHIR
    $finalStatus = "sesuai";

    foreach ($status_barang as $status) {
        if ($status == "cacat") {
            $finalStatus = "cacat";
            break;
        }
    }

    // ✅ UPDATE STATUS NOTA
    mysqli_query($koneksi, "
        UPDATE nota 
        SET status = '$finalStatus'
        WHERE id_nota = '$id_nota'
    ");

    header("Location: status_succes_cek_barang_fisik.php");
    exit;
}
