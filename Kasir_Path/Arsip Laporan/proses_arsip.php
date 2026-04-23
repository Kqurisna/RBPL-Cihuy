<?php
$koneksi = mysqli_connect("localhost", "root", "", "pt_bumijaya");

if (!isset($_POST['id_nota'])) {
    die("ID Nota tidak ditemukan!");
}

$id_nota = intval($_POST['id_nota']);

$nota = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT * FROM nota WHERE id_nota = $id_nota
"));

if (!$nota) die("Nota tidak ditemukan!");

if ($nota['status_laporan'] !== 'disetujui') {
    die("Nota belum disetujui!");
}

if ($nota['status_arsip_laporan'] === 'sudah') {
    die("Sudah diarsipkan!");
}

$cekCacat = mysqli_query($koneksi, "
    SELECT * FROM validasi_kasir 
    WHERE id_nota = $id_nota AND hasil = 'cacat'
");

$status_global = mysqli_num_rows($cekCacat) > 0 ? 'cacat' : 'sesuai';

$nama_folder = "arsip_nota_" . $id_nota . "_" . time();

$base_path = __DIR__ . "/arsip/";

if (!file_exists($base_path)) {
    mkdir($base_path, 0777, true);
}

$folder_path = $base_path . $nama_folder;

$folder_bukti = $folder_path . "/bukti_cacat";

mkdir($folder_path, 0777, true);
mkdir($folder_bukti, 0777, true);

if (!empty($nota['foto_nota'])) {

    $file_nota = trim($nota['foto_nota']);

    $root = $_SERVER['DOCUMENT_ROOT'] . "/RBPL";

    // 🔥 kalau sudah ada "uploads/" di database → jangan ditambah lagi
    if (strpos($file_nota, 'uploads/') !== false) {
        $source_nota = $root . "/AdminGudang_Path/Input Nota Barang Masuk/" . $file_nota;
    } else {
        $source_nota = $root . "/AdminGudang_Path/Input Nota Barang Masuk/uploads/nota/" . $file_nota;
    }

    if (file_exists($source_nota)) {

        $ext = pathinfo($source_nota, PATHINFO_EXTENSION);
        $dest_nota = $folder_path . "/nota." . $ext;

        copy($source_nota, $dest_nota);
    } else {

        echo "<pre>";
        echo "❌ FILE TIDAK DITEMUKAN\n";
        echo "Nama File : $file_nota\n";
        echo "Path      : $source_nota\n";
        echo "</pre>";
        die();
    }
}

mysqli_query($koneksi, "
    INSERT INTO arsip_laporan (
        id_nota, nomor_nota, tanggal_nota,
        supplier, jenis_barang, status, nama_folder
    ) VALUES (
        '{$nota['id_nota']}',
        '{$nota['nomor_nota']}',
        '{$nota['tanggal_nota']}',
        '{$nota['supplier']}',
        '{$nota['jenis_barang']}',
        '$status_global',
        '$nama_folder'
    )
");

$id_arsip = mysqli_insert_id($koneksi);

$queryDetail = mysqli_query($koneksi, "
    SELECT * FROM detail_barang WHERE id_nota = $id_nota
");

$queryValidasi = mysqli_query($koneksi, "
    SELECT * FROM validasi_kasir WHERE id_nota = $id_nota ORDER BY id_validasi ASC
");

$validasiList = [];
while ($v = mysqli_fetch_assoc($queryValidasi)) {
    $validasiList[] = $v;
}

$no = 0;

$id_detail_post = $_POST['id_detail'] ?? [];
$jumlah_retur_post = $_POST['jumlah_retur'] ?? [];

while ($d = mysqli_fetch_assoc($queryDetail)) {

    $kondisi = $validasiList[$no]['hasil'] ?? 'sesuai';

    mysqli_query($koneksi, "
        INSERT INTO arsip_detail_barang (
            id_arsip, nama_barang, jumlah_barang, kondisi
        ) VALUES (
            '$id_arsip',
            '{$d['nama_barang']}',
            '{$d['jumlah_barang']}',
            '$kondisi'
        )
    ");

    $idDetailAsli = $d['id_detail'];

    foreach ($id_detail_post as $index => $idPost) {

        if ($idPost == $idDetailAsli) {

            $jumlahRetur = $jumlah_retur_post[$index] ?? 0;

            if ($jumlahRetur > 0) {

                mysqli_query($koneksi, "
                    INSERT INTO arsip_retur (
                        id_arsip, id_detail, jumlah_retur
                    ) VALUES (
                        '$id_arsip',
                        '$idDetailAsli',
                        '$jumlahRetur'
                    )
                ");
            }
        }
    }

    $no++;
}

$queryBukti = mysqli_query($koneksi, "
    SELECT * FROM validasi_kasir 
    WHERE id_nota = $id_nota AND hasil = 'cacat'
");

while ($v = mysqli_fetch_assoc($queryBukti)) {

    if (!empty($v['foto_bukti'])) {

        $source = "C:/xampp/htdocs/RBPL/Kasir_Path/Pengecekkan Barang FIsik/uploads/bukti/" . $v['foto_bukti'];
        $nama_file = "bukti_" . $v['id_detail'] . ".jpg";
        $destination = $folder_bukti . "/" . $nama_file;

        if (file_exists($source)) {

            copy($source, $destination);

            mysqli_query($koneksi, "
                INSERT INTO arsip_bukti_cacat (
                    id_arsip, id_detail, nama_file, path_file
                ) VALUES (
                    '$id_arsip',
                    '{$v['id_detail']}',
                    '$nama_file',
                    '$nama_folder/bukti_cacat/$nama_file'
                )
            ");
        } else {
            echo "❌ Bukti tidak ditemukan: $source<br>";
        }
    }
}

$queryTanggapan = mysqli_query($koneksi, "
    SELECT ts.*
    FROM tanggapan_supplier ts
    JOIN retur r ON ts.id_retur = r.id_retur
    WHERE r.id_nota = $id_nota
");

while ($t = mysqli_fetch_assoc($queryTanggapan)) {

    if (!empty($t['lampiran'])) {

        $source = "C:/xampp/htdocs/RBPL/AdminGudang_Path/Input Konfirmasi Retur Supplier/uploads/tanggapan_supplier/" . $t['lampiran'];
        $dest   = $folder_path . "/supplier_" . basename($t['lampiran']);

        if (file_exists($source)) {
            copy($source, $dest);
        }
    }

    mysqli_query($koneksi, "
        INSERT INTO arsip_tanggapan_supplier (
            id_arsip, tanggapan, lampiran,
            status_dokumentasi, created_at
        ) VALUES (
            '$id_arsip',
            '{$t['tanggapan']}',
            '{$t['lampiran']}',
            'tersimpan',
            NOW()
        )
    ");
}

mysqli_query($koneksi, "
    UPDATE nota 
    SET status_arsip_laporan = 'sudah'
    WHERE id_nota = $id_nota
");

header("Location: arsipkan_laporan.php?success=1");
exit;
