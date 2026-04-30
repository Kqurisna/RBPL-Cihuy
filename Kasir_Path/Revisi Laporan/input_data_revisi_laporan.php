<?php
ob_start();
date_default_timezone_set('Asia/Jakarta');

$koneksi = mysqli_connect("localhost", "root", "", "pt_bumijaya");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$id_nota = $_POST['id_nota'];

$updateFields = [];

$nomor   = $_POST['nomer_nota'] ?? '';
$tanggal = $_POST['tanggal_nota'] ?? '';
$supplier = $_POST['supplier'] ?? '';
$jenis   = $_POST['jenis_barang'] ?? '';

if (!empty($nomor)) {
    $updateFields[] = "nomor_nota = '$nomor'";
}

if (!empty($tanggal)) {
    $updateFields[] = "tanggal_nota = '$tanggal'";
}

if (!empty($supplier)) {
    $updateFields[] = "supplier = '$supplier'";
}

if (!empty($jenis)) {
    $updateFields[] = "jenis_barang = '$jenis'";
}

$pathFolder = "../../AdminGudang_Path/Input Nota Barang Masuk/uploads/nota/";
$fotoNotaBaru = $_FILES['foto_nota']['name'];
$fotoNotaLama = $_POST['foto_nota_lama'];

$pathFolder = "../../AdminGudang_Path/Input Nota Barang Masuk/uploads/nota/";

if (!empty($fotoNotaBaru)) {

    $tmp = $_FILES['foto_nota']['tmp_name'];

    $namaFile = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", $fotoNotaBaru);

    $pathBaru = $pathFolder . $namaFile;

    $namaLama = basename($fotoNotaLama);
    $pathLama = $pathFolder . $namaLama;

    if (move_uploaded_file($tmp, $pathBaru)) {

        if (!empty($namaLama) && file_exists($pathLama)) {
            unlink($pathLama);
        }
        $fotoNota = "uploads/nota/" . $namaFile;
    } else {
        $fotoNota = $fotoNotaLama;
    }
} else {
    $fotoNota = $fotoNotaLama;
}
$updateFields[] = "foto_nota = '$fotoNota'";

$q1 = true;

if (!empty($updateFields)) {
    $setQuery = implode(", ", $updateFields);

    $q1 = mysqli_query($koneksi, "
        UPDATE nota SET $setQuery
        WHERE id_nota = $id_nota
    ");
}

$q2 = true;

if (isset($_POST['barang'])) {

    $barang = $_POST['barang'];
    $jumlah = $_POST['jumlah'];

    $queryDetail = mysqli_query($koneksi, "SELECT * FROM detail_barang WHERE id_nota = $id_nota");

    $i = 0;

    while ($d = mysqli_fetch_assoc($queryDetail)) {

        $id_detail = $d['id_detail'];
        $fields = [];

        if (!empty($barang[$i])) {
            $fields[] = "nama_barang = '" . $barang[$i] . "'";
        }

        if ($jumlah[$i] !== "") {
            $fields[] = "jumlah_barang = '" . $jumlah[$i] . "'";
        }

        if (!empty($fields)) {
            $set = implode(", ", $fields);

            if (!mysqli_query($koneksi, "
                UPDATE detail_barang SET $set
                WHERE id_detail = $id_detail
            ")) {
                $q2 = false;
            }
        }

        $i++;
    }
}

$q3 = true;

if (isset($_POST['keterangan'])) {
    foreach ($_POST['keterangan'] as $id_detail => $ket) {

        $fields = [];

        if (!empty($ket)) {
            $fields[] = "keterangan = '$ket'";
        }

        $fotoLama = $_POST['foto_bukti_lama'][$id_detail] ?? '';
        $fotoBaru = $_FILES['foto_retur']['name'][$id_detail] ?? '';

        if (!empty($fotoBaru) && $_FILES['foto_retur']['error'][$id_detail] === 0) {

            $tmp = $_FILES['foto_retur']['tmp_name'][$id_detail];
            $namaFile = time() . "_" . rand(1000, 9999) . "_" . $fotoBaru;

            move_uploaded_file($tmp, "../../Kasir_Path/Pengecekkan Barang FIsik/uploads/bukti/" . $namaFile);

            // hapus foto lama
            $pathLama = "../../Kasir_Path/Pengecekkan Barang FIsik/uploads/bukti/" . $fotoLama;
            if (!empty($fotoLama) && $fotoLama !== $namaFile && file_exists($pathLama)) {
                unlink($pathLama);
            }

            $fields[] = "foto_bukti = '$namaFile'";
        } else if (!empty($fotoLama)) {
            $fields[] = "foto_bukti = '$fotoLama'";
        }

        if (!empty($fields)) {
            $set = implode(", ", $fields);

            if (!mysqli_query($koneksi, "
                UPDATE validasi_kasir SET $set
                WHERE id_detail = $id_detail AND id_nota = $id_nota
            ")) {
                $q3 = false;
            }
        }
    }
}

$q4 = true;

if (isset($_POST['jumlah_retur'])) {
    foreach ($_POST['jumlah_retur'] as $id_detail => $jml) {

        if ($jml !== "") {
            if (!mysqli_query($koneksi, "
                UPDATE retur_detail SET
                    jumlah_retur = '$jml'
                WHERE id_detail = $id_detail
            ")) {
                $q4 = false;
            }
        }
    }
}

$q5 = true;

$fotoSupplierBaru = $_FILES['foto_supplier']['name'] ?? '';
$fotoSupplierLama = $_POST['lampiran_lama'] ?? '';
$tanggapan = $_POST['tanggapan_supplier'] ?? '';
$lampiran = '';

$folderSupplier = "../../AdminGudang_Path/Input Konfirmasi Retur Supplier/uploads/tanggapan_supplier/";

if (!empty($fotoSupplierBaru) && $_FILES['foto_supplier']['error'] === 0) {

    $tmp = $_FILES['foto_supplier']['tmp_name'];

    $ext = strtolower(pathinfo($fotoSupplierBaru, PATHINFO_EXTENSION));

    $ext = preg_replace("/[^a-z0-9]/", "", $ext);

    $namaFile = "tanggapan_" . time() . "." . $ext;

    $pathBaru = $folderSupplier . $namaFile;

    if (move_uploaded_file($tmp, $pathBaru)) {

        $pathLama = $folderSupplier . $fotoSupplierLama;
        if (!empty($fotoSupplierLama) && file_exists($pathLama)) {
            unlink($pathLama);
        }

        $lampiran = $namaFile;
    } else {
        $lampiran = $fotoSupplierLama;
    }
} else {
    $lampiran = $fotoSupplierLama;
}

$qRetur = mysqli_query($koneksi, "SELECT id_retur FROM retur WHERE id_nota = $id_nota");
$r = mysqli_fetch_assoc($qRetur);
$id_retur = $r['id_retur'] ?? 0;

if ($id_retur) {

    if (!mysqli_query($koneksi, "
    UPDATE tanggapan_supplier SET
        tanggapan = IF('$tanggapan' != '', '$tanggapan', tanggapan),
        lampiran = '$lampiran',
        status_dokumentasi = 'sudah'
    WHERE id_retur = $id_retur
")) {
        $q5 = false;
    }
}

$q6 = mysqli_query($koneksi, "
    UPDATE nota 
    SET status_laporan = 'menunggu'
    WHERE id_nota = $id_nota
");
$q7 = mysqli_query($koneksi, "
    DELETE FROM approval_manager 
    WHERE id_nota = $id_nota
");
if ($q1 && $q2 && $q3 && $q4 && $q5 && $q6 && $q7) {
    header("Location: input_revisi_laporan_berhasil.php");
    exit;
} else {
    echo "Terjadi kesalahan saat menyimpan data.";
}
