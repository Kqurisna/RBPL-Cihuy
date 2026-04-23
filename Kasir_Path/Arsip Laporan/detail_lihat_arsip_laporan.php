<?php
$koneksi = mysqli_connect("localhost", "root", "", "pt_bumijaya");

$id_nota = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Dari arsip_laporan (status & jenis barang) + arsip_nota (foto nota & info nota)
$queryArsip = mysqli_query($koneksi, "
    SELECT al.*, an.foto_nota, an.id_arsip as id_arsip_nota, n.status_laporan
    FROM arsip_laporan al
    LEFT JOIN arsip_nota an ON al.id_nota = an.id_nota
    LEFT JOIN nota n ON al.id_nota = n.id_nota
    WHERE al.id_nota = $id_nota
");

$dataNota = mysqli_fetch_assoc($queryArsip);
if (!$dataNota) {
    die("Data arsip tidak ditemukan!");
}

$id_arsip = $dataNota['id_arsip'];

// Dari arsip_detail_barang
$queryDetail = mysqli_query($koneksi, "
    SELECT * FROM arsip_detail_barang 
    WHERE id_arsip = $id_arsip 
    ORDER BY id_arsip_detail ASC
");

// Dari arsip_retur — key: id_detail (= id_detail dari detail_barang asli)
$returList = [];
$queryRetur = mysqli_query($koneksi, "
    SELECT * FROM arsip_retur WHERE id_arsip = $id_arsip
");
while ($r = mysqli_fetch_assoc($queryRetur)) {
    $returList[$r['id_detail']] = $r;
}

// Dari arsip_bukti_cacat — key: id_detail
$buktiList = [];
$queryBukti = mysqli_query($koneksi, "
    SELECT * FROM arsip_bukti_cacat WHERE id_arsip = $id_arsip
");
while ($b = mysqli_fetch_assoc($queryBukti)) {
    $buktiList[$b['id_detail']][] = $b;
}

// Dari arsip_tanggapan_supplier
$tanggapanList = [];
$queryTanggapan = mysqli_query($koneksi, "
    SELECT * FROM arsip_tanggapan_supplier WHERE id_arsip = $id_arsip
");
while ($t = mysqli_fetch_assoc($queryTanggapan)) {
    $tanggapanList[] = $t;
}
$dataTanggapan = !empty($tanggapanList) ? $tanggapanList[0] : null;

// Cek ada cacat atau tidak
$adaCacat = false;
mysqli_data_seek($queryDetail, 0);
while ($d = mysqli_fetch_assoc($queryDetail)) {
    if ($d['kondisi'] == 'cacat') {
        $adaCacat = true;
        break;
    }
}
mysqli_data_seek($queryDetail, 0);

// Karena arsip_detail_barang tidak punya id_detail asli,
// kita ambil mapping dari detail_barang berdasarkan nama_barang
$mappingDetail = [];
$queryMapping = mysqli_query($koneksi, "
    SELECT db.id_detail, db.nama_barang 
    FROM detail_barang db
    WHERE db.id_nota = $id_nota
");
while ($m = mysqli_fetch_assoc($queryMapping)) {
    $mappingDetail[$m['nama_barang']] = $m['id_detail'];
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Arsip Laporan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            background: #efefef;
            min-height: 100vh;
        }

        .header {
            background: #3f7aa3;
            color: white;
            padding: 18px 20px;
            position: relative;
            overflow: hidden;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 2;
        }

        .header h2 {
            font-weight: 500;
            font-size: 18px;
        }

        .back-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #48b5c1;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }

        .back-link {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }

        .back-link img {
            width: 18px;
            height: 18px;
            object-fit: contain;
        }

        .back-btn:hover {
            transform: scale(1.05);
            transition: 0.2s;
        }

        .header-circle-big {
            position: absolute;
            width: 90px;
            height: 90px;
            background: #5bb7c5;
            border-radius: 50%;
            right: -20px;
            top: 13px;
        }

        .header-circle-small {
            position: absolute;
            width: 45px;
            height: 45px;
            background: #5bb7c5;
            border-radius: 50%;
            left: -11px;
            top: 51px;
        }

        .header-circle-small_2 {
            position: absolute;
            width: 18px;
            height: 18px;
            background: #519eaa;
            border-radius: 50%;
            left: 1px;
            top: 23px;
        }

        .header-circle-small_3 {
            position: absolute;
            width: 14px;
            height: 14px;
            background: #519eaa;
            border-radius: 50%;
            left: 45px;
            top: 53px;
        }

        .container {
            padding: 15px 30px 10px;
        }

        .form-card {
            background: white;
            padding: 1px 20px 35px;
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
            position: relative;
            margin-bottom: 20px;
        }

        .form-card_2 {
            margin-top: 51px;
            background: #8FB5D0;
            padding: 1px 20px 35px;
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
            position: relative;
        }

        .form-group {
            margin-top: 15px;
            margin-bottom: 18px;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 800;
            display: block;
            margin-bottom: 5px;
            color: #111827;
        }

        .form-group input {
            width: 100%;
            height: 36px;
            border-radius: 16px;
            border: none;
            background: #e9edf2;
            padding: 0 15px;
            font-size: 12px;
            font-weight: 500;
            outline: none;
        }

        .form-group_2 {
            margin-top: 15px;
            margin-bottom: 18px;
        }

        .form-group_2 label {
            font-size: 13px;
            font-weight: 800;
            display: block;
            margin-bottom: 5px;
            color: #ffffff;
        }

        .chip-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .chip {
            border-radius: 7px;
            padding: 1px 10px;
            background: #ffffff;
            border: 2px solid #d1d5db;
            font-size: 14px;
            color: #6b7280;
            font-weight: 500;
            pointer-events: none;
        }

        .chip.active {
            background: #3f7aa3;
            color: white;
            border-color: #3f7aa3;
        }

        .container_2 {
            padding: 1px 16px;
        }

        .container_2 label {
            color: white;
        }

        .welcome-card_2 {
            background: #8FB5D0;
            color: white;
            padding: 16px;
            border-radius: 18px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            position: relative;
            margin-top: 10px;
        }

        .card-dots_2_1 {
            position: absolute;
            top: -20px;
            right: 10px;
            display: flex;
            z-index: 2;
        }

        .card-dots_2_1 span {
            width: 37px;
            height: 37px;
            background: #44a2ac;
            border-radius: 50%;
        }

        .card-dots_2_2 {
            position: absolute;
            top: -15px;
            right: 50px;
            display: flex;
            z-index: 2;
        }

        .card-dots_2_2 span {
            width: 31px;
            height: 31px;
            background: #61cbd7;
            border-radius: 50%;
        }

        .card-dots_2_3 {
            position: absolute;
            top: -11px;
            right: 85px;
            display: flex;
            z-index: 2;
        }

        .card-dots_2_3 span {
            width: 26px;
            height: 26px;
            background: #68d4e0;
            border-radius: 50%;
        }

        .chip-container-status {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 8px;
        }

        .chip-status {
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 13px;
            border: 1.5px solid #ccc;
            background: #f9f9f9;
            color: #555;
            pointer-events: none;
        }

        .chip-status.sesuai {
            border-color: #2ecc71;
            color: #2ecc71;
            background: #f3fff7;
        }

        .chip-status.cacat {
            border-color: #e74c3c;
            color: #e74c3c;
            background: #fff5f5;
        }

        .chip-status.active.sesuai {
            background: #2ecc71;
            color: white;
        }

        .chip-status.active.cacat {
            background: #e74c3c;
            color: white;
        }

        .textarea {
            width: 100%;
            border-radius: 16px;
            border: none;
            min-height: 80px;
            background: #e9edf2;
            padding: 14px 4px 10px 15px;
            font-size: 12px;
            font-weight: 300;
            outline: none;
            resize: none;
            line-height: 1.4;
        }

        .success-line {
            display: block;
            margin: 10px auto;
            width: 40%;
            height: 3px;
            background: #c3e0ef;
            border-radius: 3px;
        }

        .img-preview {
            margin-top: 10px;
            background: #fff;
            padding: 8px;
            border-radius: 18px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            transition: 0.3s;
            cursor: pointer;
        }

        .img-preview img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            display: block;
            border-radius: 12px;
        }

        .img-preview:hover {
            transform: scale(1.02);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            padding-top: 50px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
        }

        .modal-content {
            margin: auto;
            display: block;
            max-width: 90%;
            max-height: 85vh;
            border-radius: 12px;
            transition: transform 0.3s ease;
            transform-origin: center;
            cursor: zoom-in;
        }

        .close {
            position: absolute;
            top: 20px;
            right: 35px;
            color: white;
            font-size: 35px;
            cursor: pointer;
        }

        .form-card {
            opacity: 0;
            transform: translateY(25px) scale(0.97);
            transition: all 0.5s ease;
        }

        .form-card.show {
            opacity: 1;
            transform: translateY(0) scale(1);
            transition-delay: 0.05s;
        }

        .box_1 {
            position: absolute;
            width: 42px;
            height: 42px;
            background: #6AD2DE;
            right: 20px;
            top: -32px;
            transform: rotate(18deg);
        }

        .box_2 {
            position: absolute;
            width: 36px;
            height: 36px;
            background: #86D9E2;
            right: 75px;
            top: -27px;
            transform: rotate(18deg);
        }

        .box_3 {
            position: absolute;
            width: 32px;
            height: 32px;
            background: #BBE6EB;
            right: 123px;
            top: -24px;
            transform: rotate(18deg);
        }

        .status-label {
            position: absolute;
            right: 10px;
            top: -8px;
            font-size: 12px;
            font-weight: 500;
            text-align: right;
        }

        .status-line {
            height: 3px;
            border-radius: 3px;
            margin-top: 1px;
        }

        .status-belum {
            color: #9ca3af;
        }

        .status-belum .status-line {
            background: #9ca3af;
        }

        .status-menunggu {
            color: #000;
        }

        .status-menunggu .status-line {
            background: #5BE0E7;
        }

        .status-ditolak {
            color: #000;
        }

        .status-ditolak .status-line {
            background: #40189F;
        }

        .status-disetujui {
            color: #000;
        }

        .status-disetujui .status-line {
            background: #5DADC1;
        }

        .dots {
            display: flex;
            gap: 6px;
        }

        .dots span {
            border-radius: 50%;
            position: relative;
        }

        .dots span:nth-child(1) {
            width: 12px;
            height: 12px;
            background: #6AD2DE;
            top: 7px;
        }

        .dots span:nth-child(2) {
            width: 18px;
            height: 18px;
            background: #98DFE7;
            top: 4px;
        }

        .dots span:nth-child(3) {
            width: 26px;
            height: 26px;
            background: #ABDEE3;
            top: 0;
        }

        .reject-box {
            display: none;
            margin-top: 10px;
        }

        .reject-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            margin-top: 20px;
        }

        .reject-header h3 {
            font-size: 18px;
            font-weight: 600;
        }

        .reject-card {
            background: #F0F0F9;
            padding: 20px;
            border-radius: 20px;
        }

        .reject-card textarea {
            width: 100%;
            border: none;
            background: transparent;
            resize: none;
            font-size: 14px;
            outline: none;
            min-height: 80px;
        }

        .auto-height {
            width: 100%;
            border: none;
            background: transparent;
            resize: none;
            overflow: hidden;
            font-size: 14px;
            line-height: 1.5;
        }

        .item {
            animation: fadeSlideIn 0.3s ease;
        }

        @keyframes fadeSlideIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body>
    <div id="imageModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="modalImg">
    </div>

    <div class="header">
        <div class="header-left">
            <div class="back-btn">
                <a href="list_nota_arsip_laporan.php" class="back-link">
                    <img src="../../UI_GENERAL/logo_back.png" alt="Back">
                </a>
            </div>
            <h2>Arsip Laporan</h2>
        </div>
        <div class="header-circle-big"></div>
        <div class="header-circle-small"></div>
        <div class="header-circle-small_2"></div>
        <div class="header-circle-small_3"></div>
    </div>

    <div class="container">
        <div class="form-card">

            <?php
            $statusLaporan = $dataNota['status_laporan'] ?? '';
            $text = '';
            $class = '';
            if ($statusLaporan == 'belum_diajukan') {
                $text = 'Belum Diajukan';
                $class = 'status-belum';
            } elseif ($statusLaporan == 'menunggu') {
                $text = 'Menunggu Persetujuan';
                $class = 'status-menunggu';
            } elseif ($statusLaporan == 'ditolak') {
                $text = 'Ditolak';
                $class = 'status-ditolak';
            } elseif ($statusLaporan == 'disetujui') {
                $text = 'Disetujui';
                $class = 'status-disetujui';
            }
            ?>

            <div class="form-group" style="position:relative;">
                <label>Nomer Nota</label>
                <input type="text" value="<?= htmlspecialchars($dataNota['nomor_nota']) ?>" readonly>
                <div class="status-label <?= $class ?>">
                    <?= $text ?>
                    <div class="status-line"></div>
                </div>
            </div>

            <div class="form-group">
                <label>Tanggal Nota</label>
                <input type="date" value="<?= $dataNota['tanggal_nota'] ?>" readonly>
            </div>

            <div class="form-group">
                <label>Nama Supplier</label>
                <input type="text" value="<?= htmlspecialchars($dataNota['supplier']) ?>" readonly>
            </div>

            <div class="form-group">
                <label>Jenis Barang</label>
                <div class="chip-container">
                    <?php
                    $jenisList = ["Material Bangunan", "Besi & Logam", "Listrik", "Keramik & Lantai", "Alat Pertukangan", "Kayu & Olahan"];
                    $selectedJenis = array_map('trim', explode(",", $dataNota['jenis_barang']));
                    foreach ($jenisList as $jenis) {
                        $active = in_array($jenis, $selectedJenis) ? "active" : "";
                        echo "<span class='chip $active'>$jenis</span>";
                    }
                    ?>
                </div>
            </div>

            <div class="container_2">
                <div class="welcome-card_2">
                    <div id="inputBarangContainer">
                        <?php
                        $no = 1;
                        while ($detail = mysqli_fetch_assoc($queryDetail)) {
                            $kondisi    = $detail['kondisi'];
                            $namaBarang = $detail['nama_barang'];

                            // Dapat id_detail asli dari mapping nama_barang
                            $idDetailAsli = isset($mappingDetail[$namaBarang]) ? $mappingDetail[$namaBarang] : null;

                            $dataRetur = ($idDetailAsli && isset($returList[$idDetailAsli])) ? $returList[$idDetailAsli] : null;
                            $dataBukti = ($idDetailAsli && isset($buktiList[$idDetailAsli])) ? $buktiList[$idDetailAsli] : [];
                        ?>
                            <div class="item">
                                <div class="card-dots_2_1"><span></span></div>
                                <div class="card-dots_2_2"><span></span></div>
                                <div class="card-dots_2_3"><span></span></div>

                                <div class="form-group">
                                    <label>Nama Barang ke-<?= $no ?></label>
                                    <input type="text" value="<?= htmlspecialchars($namaBarang) ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label>Jumlah Barang ke-<?= $no ?></label>
                                    <input type="number" value="<?= $detail['jumlah_barang'] ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label>Kondisi Barang</label>
                                    <div class="chip-container-status">
                                        <span class="chip-status sesuai <?= $kondisi == 'sesuai' ? 'active' : '' ?>">Sesuai</span>
                                        <span class="chip-status cacat <?= $kondisi == 'cacat' ? 'active' : '' ?>">Cacat</span>
                                    </div>
                                </div>

                                <?php if ($kondisi == 'cacat') { ?>
                                    <div class="form-group">
                                        <label>Jumlah Retur</label>
                                        <input type="number" value="<?= $dataRetur ? $dataRetur['jumlah_retur'] : '0' ?>" readonly>
                                    </div>

                                    <?php if (!empty($dataBukti)) { ?>
                                        <div class="form-group">
                                            <label>Foto Bukti Cacat</label>
                                            <?php foreach ($dataBukti as $bukti) {
                                                $pathBukti = "/RBPL/Kasir_Path/Arsip Laporan/arsip/" . $bukti['path_file'];
                                            ?>
                                                <div class="img-preview" onclick="openModal(this)">
                                                    <img src="<?= $pathBukti ?>">
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php } else { ?>
                                        <p style="font-size:13px; color:#9ca3af; margin-top:8px;">Belum ada foto bukti</p>
                                    <?php } ?>
                                <?php } ?>

                                <div class="success-line"></div>
                            </div>
                        <?php $no++;
                        } ?>
                    </div>
                </div>
            </div>

            <?php
            $fotoNota = $dataNota['foto_nota'] ?? '';
            $namaFolder = $dataNota['nama_folder'] ?? '';
            $pathFotoArsip = "";
            if (!empty($namaFolder)) {
                // Cari file nota di dalam folder arsip
                $folderArsip = "C:/xampp/htdocs/RBPL/Kasir_Path/Arsip Laporan/arsip/" . $namaFolder . "/";
                foreach (glob($folderArsip . "nota.*") as $file) {
                    $ext = pathinfo($file, PATHINFO_EXTENSION);
                    $pathFotoArsip = "/RBPL/Kasir_Path/Arsip Laporan/arsip/" . $namaFolder . "/nota." . $ext;
                    break;
                }
            }
            ?>

            <?php if ($adaCacat) { ?>
                <div class="welcome-card_3">
                    <div class="form-card_2">
                        <div class="box_1"></div>
                        <div class="box_2"></div>
                        <div class="box_3"></div>

                        <div class="form-group_2">
                            <label>Tanggapan & Tindak Lanjut Supplier</label>
                            <textarea class="textarea" readonly><?= $dataTanggapan ? htmlspecialchars($dataTanggapan['tanggapan']) : 'Belum ada tanggapan' ?></textarea>
                        </div>

                        <div class="form-group_2">
                            <label>Bukti Tanggapan Supplier</label>
                            <?php if ($dataTanggapan && !empty($dataTanggapan['lampiran'])) {
                                $basePath = "/RBPL/Kasir_Path/Arsip%20Laporan/arsip/";
                                $namaFile = "supplier_" . basename($dataTanggapan['lampiran']);
                                $pathLampiran = $basePath . $namaFolder . "/" . $namaFile;                            ?>
                                <div class="img-preview" onclick="openModal(this)">
                                    <img src="<?= $pathLampiran ?>">
                                </div>
                            <?php } else { ?>
                                <p style="font-size:13px; color:#9ca3af;">Belum ada bukti dari supplier</p>
                            <?php } ?>
                        </div>

                        <div class="form-group_2">
                            <label>Foto Nota</label>
                            <?php if (!empty($pathFotoArsip)) { ?>
                                <div class="img-preview" onclick="openModal(this)">
                                    <img src="<?= $pathFotoArsip ?>">
                                </div>
                            <?php } else { ?>
                                <p style="font-size:13px; color:#ef4444;">Foto nota tidak tersedia</p>
                            <?php } ?>
                        </div>
                    </div>
                </div>

            <?php } else { ?>

                <div class="welcome-card_3">
                    <div class="form-card_2">
                        <div class="box_1"></div>
                        <div class="box_2"></div>
                        <div class="box_3"></div>

                        <div class="form-group_2">
                            <label>Foto Nota</label>
                            <?php if (!empty($pathFotoArsip)) { ?>
                                <div class="img-preview" onclick="openModal(this)">
                                    <img src="<?= $pathFotoArsip ?>">
                                </div>
                            <?php } else { ?>
                                <p style="font-size:13px; color:#ef4444;">Foto nota tidak tersedia</p>
                            <?php } ?>
                        </div>
                    </div>
                </div>

            <?php } ?>

            <div style="text-align:center; font-size:13px; color:#6b7280; margin-top:15px; background:#f3f4f6; padding:12px; border-radius:12px;">
                <?php if ($statusLaporan === 'ditolak') { ?>
                    Laporan telah ditolak dan saat ini menunggu revisi dari <strong>Manajer Toko</strong>
                <?php } elseif ($statusLaporan === 'disetujui') { ?>
                    Laporan ini telah diarsipkan dan tersimpan sebagai data permanent oleh <strong>Kasir Toko</strong>
                <?php } else { ?>
                    Status laporan saat ini: <strong><?= ucfirst(str_replace("_", " ", $statusLaporan)) ?></strong>
                <?php } ?>
            </div>

        </div>
    </div>
</body>

</html>
<script>
    function closeModal() {
        document.getElementById("imageModal").style.display = "none";
    }

    function openModal(el) {
        const img = el.querySelector("img");
        const modalImg = document.getElementById("modalImg");
        document.getElementById("imageModal").style.display = "block";
        modalImg.src = img.src;
        modalImg.style.transform = "scale(1)";
        modalImg.style.cursor = "zoom-in";
    }

    document.addEventListener("DOMContentLoaded", function() {
        const cards = document.querySelectorAll(".form-card");
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.classList.add("show");
            }, index * 100);
        });

        document.querySelectorAll('.textarea, .auto-height').forEach(el => {
            el.style.height = 'auto';
            el.style.height = el.scrollHeight + 'px';
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        const modalImg = document.getElementById("modalImg");
        let isZoomed = false;

        modalImg.addEventListener("dblclick", function() {
            if (!isZoomed) {
                modalImg.style.transform = "scale(2)";
                modalImg.style.cursor = "zoom-out";
                isZoomed = true;
            } else {
                modalImg.style.transform = "scale(1)";
                modalImg.style.cursor = "zoom-in";
                isZoomed = false;
            }
        });
    });
</script>