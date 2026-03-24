<?php
$koneksi = mysqli_connect("localhost", "root", "", "pt_bumijaya");

$id_nota       = $_POST['id_nota'];
$nomor_nota    = $_POST['nomer_nota'];
$tanggal_nota  = $_POST['tanggal_nota'];
$supplier      = $_POST['supplier'];
$jenis_barang  = $_POST['jenis_barang'];

mysqli_query($koneksi, "
    INSERT INTO retur (id_nota, nomor_nota, tanggal_nota, supplier, jenis_barang)
    VALUES ('$id_nota', '$nomor_nota', '$tanggal_nota', '$supplier', '$jenis_barang')
");

$id_retur = mysqli_insert_id($koneksi);


if (isset($_POST['id_detail'])) {

    foreach ($_POST['id_detail'] as $index => $id_detail) {

        $jumlah_retur = $_POST['jumlah_retur'][$index];

        $q = mysqli_query($koneksi, "
            SELECT * FROM detail_barang 
            WHERE id_detail = '$id_detail'
        ");
        $d = mysqli_fetch_assoc($q);

        $v = mysqli_query($koneksi, "
            SELECT * FROM validasi_kasir 
            WHERE id_detail = '$id_detail'
        ");
        $validasi = mysqli_fetch_assoc($v);

        if ($jumlah_retur > 0) {

            mysqli_query($koneksi, "
                INSERT INTO retur_detail (
                    id_retur,
                    id_detail,
                    nama_barang,
                    jumlah_barang,
                    jumlah_retur,
                    hasil_validasi,
                    keterangan,
                    foto_bukti
                ) VALUES (
                    '$id_retur',
                    '$id_detail',
                    '{$d['nama_barang']}',
                    '{$d['jumlah_barang']}',
                    '$jumlah_retur',
                    '{$validasi['hasil']}',
                    '{$validasi['keterangan']}',
                    '{$validasi['foto_bukti']}'
                )
            ");
        }
    }
}

mysqli_query($koneksi, "
    UPDATE nota 
    SET status_retur = 'sudah'
    WHERE id_nota = '$id_nota'
");

header("Location: status_success_retur_barang.php");
exit;
