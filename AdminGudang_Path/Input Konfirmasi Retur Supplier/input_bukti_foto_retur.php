<?php
$koneksi = mysqli_connect("localhost", "root", "", "pt_bumijaya");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!isset($_POST['id_nota']) || empty($_POST['id_nota'])) {
        die("ID Nota tidak ditemukan!");
    }

    $id_nota = $_POST['id_nota'];

    $id_detail_list = $_POST['id_detail'] ?? [];
    $jumlah_retur_list = $_POST['jumlah_retur'] ?? [];

    $tanggapan = $_POST['tanggapan_supplier'] ?? '';

    $cek = mysqli_query($koneksi, "SELECT * FROM retur WHERE id_nota = '$id_nota'");

    if (mysqli_num_rows($cek) > 0) {
        $dataRetur = mysqli_fetch_assoc($cek);
        $id_retur = $dataRetur['id_retur'];
    } else {
        mysqli_query($koneksi, "
            INSERT INTO retur (id_nota, tanggal_input)
            VALUES ('$id_nota', NOW())
        ");
        $id_retur = mysqli_insert_id($koneksi);
    }

    $folder = "uploads/tanggapan_supplier/";
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $lampiranFinal = "";

    if (isset($_FILES['foto_retur'])) {

        $fileName = $_FILES['foto_retur']['name'];

        if ($fileName != "") {

            $tmpName = $_FILES['foto_retur']['tmp_name'];
            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $allowed = ['jpg', 'jpeg', 'png', 'pdf'];

            if (in_array($ext, $allowed)) {

                $newName = "tanggapan_" . time() . "." . $ext;

                if (move_uploaded_file($tmpName, $folder . $newName)) {
                    $lampiranFinal = $newName;
                }
            }
        }
    }

    foreach ($id_detail_list as $i => $id_detail) {

        $jumlah_retur = isset($jumlah_retur_list[$i]) ? intval($jumlah_retur_list[$i]) : 0;

        if ($jumlah_retur > 0) {

            $cekDetail = mysqli_query($koneksi, "
                SELECT * FROM retur_detail 
                WHERE id_retur = '$id_retur' AND id_detail = '$id_detail'
            ");

            if (mysqli_num_rows($cekDetail) == 0) {
                mysqli_query($koneksi, "
                    INSERT INTO retur_detail (id_retur, id_detail, jumlah_retur)
                    VALUES ('$id_retur', '$id_detail', '$jumlah_retur')
                ");
            }
        }
    }

    mysqli_query($koneksi, "
        INSERT INTO tanggapan_supplier (id_retur, tanggapan, lampiran, status_dokumentasi)
        VALUES ('$id_retur', '$tanggapan', '$lampiranFinal', 'sudah')
    ");

    mysqli_query($koneksi, "
        UPDATE nota 
        SET 
            status = 'cacat',
            status_laporan = 'belum_diajukan'
        WHERE id_nota = '$id_nota'
    ");

    header("Location: status_success_bukti_supplier.php");
    exit;
}
