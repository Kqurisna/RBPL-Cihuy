<?php
$koneksi = mysqli_connect("localhost", "root", "", "pt_bumijaya");

$id_nota = $_POST['id_nota'];

$nomor = $_POST['nomer_nota'];
$tanggal = $_POST['tanggal_nota'];
$supplier = $_POST['supplier'];
$jenis = $_POST['jenis_barang'];

$fotoNotaBaru = $_FILES['foto_nota']['name'];
$fotoNotaLama = $_POST['foto_nota_lama'];

if (!empty($fotoNotaBaru)) {
    $tmp = $_FILES['foto_nota']['tmp_name'];
    $namaFile = time() . "_" . $fotoNotaBaru;

    move_uploaded_file($tmp, "../../AdminGudang_Path/Input Nota Barang Masuk/" . $namaFile);

    $fotoNota = $namaFile;
} else {
    $fotoNota = $fotoNotaLama;
}

mysqli_query($koneksi, "
    UPDATE nota SET 
        nomor_nota = '$nomor',
        tanggal_nota = '$tanggal',
        supplier = '$supplier',
        jenis_barang = '$jenis',
        foto_nota = '$fotoNota'
    WHERE id_nota = $id_nota
");

$barang = $_POST['barang'];
$jumlah = $_POST['jumlah'];

$queryDetail = mysqli_query($koneksi, "SELECT * FROM detail_barang WHERE id_nota = $id_nota");

$i = 0;
while ($d = mysqli_fetch_assoc($queryDetail)) {

    $id_detail = $d['id_detail'];

    mysqli_query($koneksi, "
        UPDATE detail_barang SET
            nama_barang = '" . $barang[$i] . "',
            jumlah_barang = '" . $jumlah[$i] . "'
        WHERE id_detail = $id_detail
    ");

    $i++;
}


if (isset($_POST['keterangan'])) {
    foreach ($_POST['keterangan'] as $id_detail => $ket) {

        $fotoLama = $_POST['foto_bukti_lama'][$id_detail] ?? '';
        $fotoBaru = $_FILES['foto_retur']['name'][$id_detail] ?? '';

        if (!empty($fotoBaru)) {

            $tmp = $_FILES['foto_retur']['tmp_name'][$id_detail];
            $namaFile = time() . "_" . $fotoBaru;

            move_uploaded_file($tmp, "../../Kasir_Path/Pengecekkan Barang FIsik/uploads/bukti/" . $namaFile);

            $fotoFinal = $namaFile;
        } else {
            $fotoFinal = $fotoLama;
        }

        mysqli_query($koneksi, "
            UPDATE validasi_kasir SET
                keterangan = '$ket',
                foto_bukti = '$fotoFinal'
            WHERE id_detail = $id_detail AND id_nota = $id_nota
        ");
    }
}


if (isset($_POST['jumlah_retur'])) {
    foreach ($_POST['jumlah_retur'] as $id_detail => $jml) {

        mysqli_query($koneksi, "
            UPDATE retur_detail SET
                jumlah_retur = '$jml'
            WHERE id_detail = $id_detail
        ");
    }
}

$fotoSupplierBaru = $_FILES['foto_supplier']['name'];
$fotoSupplierLama = $_POST['lampiran_lama'];

if (!empty($fotoSupplierBaru)) {

    $tmp = $_FILES['foto_supplier']['tmp_name'];
    $namaFile = time() . "_" . $fotoSupplierBaru;

    move_uploaded_file($tmp, "../../AdminGudang_Path/Input Konfirmasi Retur Supplier/uploads/tanggapan_supplier/" . $namaFile);

    $fotoSupplier = $namaFile;
} else {
    $fotoSupplier = $fotoSupplierLama;
}

$qRetur = mysqli_query($koneksi, "SELECT id_retur FROM retur WHERE id_nota = $id_nota");
$r = mysqli_fetch_assoc($qRetur);
$id_retur = $r['id_retur'] ?? 0;

if ($id_retur) {
    mysqli_query($koneksi, "
        UPDATE tanggapan_supplier SET
            lampiran = '$fotoSupplier'
        WHERE id_retur = $id_retur
    ");
}

mysqli_query($koneksi, "
    UPDATE nota 
    SET status_laporan = 'menunggu'
    WHERE id_nota = $id_nota
");

echo "<script>
    alert('Data revisi berhasil disimpan!');
    window.location.href = 'list_nota_ditolak.php';
</script>";
