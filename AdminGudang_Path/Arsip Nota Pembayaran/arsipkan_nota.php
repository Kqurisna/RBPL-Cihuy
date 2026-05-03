<?php
session_start();

$conn = new mysqli("localhost", "root", "", "pt_bumijaya");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin_gudang') {
    header("Location: ../../index.php?error=role");
    exit();
}
$koneksi = mysqli_connect("localhost", "root", "", "pt_bumijaya");

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'desc';

$sort = ($sort === 'asc') ? 'ASC' : 'DESC';

$query = mysqli_query($koneksi, "
    SELECT *
    FROM nota
    WHERE status_laporan = 'disetujui'
    ORDER BY tanggal_nota $sort
");
require_once "Nota.php";

$nota = new Nota($koneksi);

$query = $nota->getNotaDisetujui($sort);
?>
<!doctype html>
<html lang="id">

<head>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Arsip Nota & Pembayaran</title>

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

        .container h3 {
            font-size: 18px;
        }

        .form-card {
            background: white;
            padding: 20px 20px 50px;
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
            position: relative;
            z-index: 1;
            opacity: 0;
            transform: translateY(25px) scale(0.97);
            transition: all 0.5s ease;
            will-change: transform, opacity;
        }

        .form-card.show {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .form-card.exit {
            opacity: 0;
            transform: translateX(-100px);
            transition: all 0.3s ease;
        }

        .form-group {
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

        .circle {
            position: absolute;
            bottom: 14px;
            right: 19px;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #d3d8de;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            z-index: 10;
        }

        .circle img {
            width: 8px;
            height: 8px;
            object-fit: cover;
        }

        html {
            scroll-behavior: smooth;
        }

        .empty-container {
            min-height: 70vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;

            padding: 20px;
        }

        .empty-icon {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 15px;
        }

        .empty-icon img {
            width: 70px;
            height: 70px;
            opacity: 0.8;
        }

        .empty-title {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
        }

        .empty-line {
            width: 120px;
            height: 3px;
            background: #48b5c1;
            margin: 12px 0;
            border-radius: 3px;
        }

        .empty-desc {
            font-size: 14px;
            color: #6b7280;
            max-width: 260px;
            line-height: 1.5;
        }

        .status-icon {
            position: absolute;
            top: 10px;
            right: 17px;

            width: 50px;
            height: 50px;

            display: flex;
            justify-content: center;
            align-items: center;

        }

        .status-icon img {
            width: 45px;
            height: 45px;
        }


        .popup-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0);
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            visibility: hidden;
            z-index: 999;
            transition: all 0.25s ease;
        }

        .popup-overlay.show {
            opacity: 1;
            visibility: visible;
            background: rgba(0, 0, 0, 0.4);
        }

        .popup-overlay.hide {
            opacity: 0;
            visibility: hidden;
        }

        .popup-box {
            background: white;
            padding: 25px;
            border-radius: 16px;
            width: 280px;
            text-align: center;
            transform: scale(0.85);
            opacity: 0;
            transition: all 0.25s ease;
        }

        .popup-overlay.show .popup-box {
            transform: scale(1);
            opacity: 1;
        }

        .popup-overlay.hide .popup-box {
            transform: scale(0.85);
            opacity: 0;
        }

        .popup-box {
            background: white;
            padding: 25px;
            border-radius: 16px;
            width: 280px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.25s ease;
        }

        .popup-box h3 {
            margin-bottom: 10px;
        }

        .popup-box p {
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
        }

        .popup-btn {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .popup-btn button {
            flex: 1;
            padding: 8px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }

        .btn-yes {
            background: #3f7aa3;
            color: white;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .btn-yes:hover {
            background: #356c91;
        }

        .btn-yes:active {
            transform: scale(0.96);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .image-preview-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .image-preview-overlay img {
            max-width: 90%;
            max-height: 85%;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
        }

        .close-btn {
            position: absolute;
            top: 20px;
            right: 25px;
            font-size: 30px;
            color: white;
            cursor: pointer;
        }

        .container p {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
        }

        .image-preview-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0);
            display: flex;
            justify-content: center;
            align-items: center;

            opacity: 0;
            visibility: hidden;

            z-index: 1000;
            transition: all 0.3s ease;
        }

        .image-preview-overlay.show {
            opacity: 1;
            visibility: visible;
            background: rgba(0, 0, 0, 0.85);
        }

        .image-preview-overlay.hide {
            opacity: 0;
            visibility: hidden;
        }

        .image-preview-overlay img {
            max-width: 90%;
            max-height: 85%;
            border-radius: 12px;

            transform: scale(0.8);
            opacity: 0;

            transition: all 0.25s ease;
        }

        .image-preview-overlay.show img {
            transform: scale(1);
            opacity: 1;
        }

        .image-preview-overlay.hide img {
            transform: scale(0.8);
            opacity: 0;
        }
    </style>
</head>

<body>

    <div class="header">

        <div class="header-left">

            <div class="back-btn">
                <a href="arsip_menu.php" class="back-link">
                    <img src="../../UI_GENERAL/logo_back.png" alt="Back">
                </a>
            </div>

            <h2>Arsip Nota & Pembayaran</h2>
        </div>

        <div class="header-circle-big"></div>
        <div class="header-circle-small"></div>
        <div class="header-circle-small_2"></div>
        <div class="header-circle-small_3"></div>

    </div>


    <div class="container">
        <h4> Arsipkan Nota Pembayaran</h4>
        <div style="display:flex; align-items:center; gap:8px; margin:5px;">
            <h5 class="section-title" style="display:flex; align-items:center; gap:6px;">
                <span style="color:#000;">Sorting by</span>
                <a href="?sort=asc" style="text-decoration:none;">
                    <span style="
                        color: <?= ($sort == 'ASC') ? '#3f7aa3' : '#9ca3af' ?>;
                        font-weight: <?= ($sort == 'ASC') ? '600' : '400' ?>;">ASC
                    </span>
                </a>
                <a href="?sort=desc" style="text-decoration:none;">
                    <span style="
                    color: <?= ($sort == 'DESC') ? '#3f7aa3' : '#9ca3af' ?>;
                    font-weight: <?= ($sort == 'DESC') ? '600' : '400' ?>;">DESC
                    </span>
                </a>
            </h5>

        </div>
        <?php if (mysqli_num_rows($query) > 0) { ?>

            <?php while ($data = mysqli_fetch_assoc($query)) { ?>

                <div class="form-card">
                    <h3 class="section-title">Nota</h3>

                    <div class="form-group">
                        <label>Nomer Nota<span style="color:red">*</span></label>
                        <input type="text" value="<?= $data['nomor_nota'] ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Nota<span style="color:red">*</span></label>
                        <input type="date" value="<?= $data['tanggal_nota'] ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nama Supplier<span style="color:red">*</span></label>
                        <input type="text" value="<?= $data['supplier'] ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Foto Nota</label>

                        <?php
                        $fotoPath = $nota->getFotoPath($data['foto_nota']);
                        ?>

                        <?php if ($fotoPath) { ?>
                            <img src="<?= htmlspecialchars($fotoPath) ?>"
                                style="width:100%; border-radius:12px; margin-top:8px; cursor:pointer;"
                                onclick="showPreview(this.src)"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <p style="display:none; font-size:12px; color:#e74c3c;">Gagal memuat foto</p>
                        <?php } else { ?>
                            <p style="font-size:12px; color:#9ca3af;">Tidak ada foto</p>
                        <?php } ?>
                    </div>
                    <div class="circle" onclick="showPopup(<?= $data['id_nota'] ?>)">
                        <img src="../../Kasir_Path/asset_kasir/logo_masuk_id.png">
                    </div>

                </div>
                <br>
            <?php } ?>

        <?php } else { ?>

            <div class="empty-container">
                <div class="empty-icon">
                    <img src="../../UI_GENERAL/logo_x.png" alt="">
                </div>

                <div class="empty-title">
                    Tidak ada barang yang harus dicek
                </div>

                <div class="empty-line"></div>

                <div class="empty-desc">
                    Semua barang sudah diperiksa atau belum ada data masuk
                </div>
            </div>

        <?php } ?>

    </div>
    <div id="popupKonfirmasi" class="popup-overlay">
        <div class="popup-box">

            <h3>Konfirmasi</h3>
            <p>Yakin ingin mengarsipkan nota ini?</p>

            <div class="popup-btn">
                <button onclick="tutupPopup()">Batal</button>
                <button class="btn-yes" onclick="lanjutArsip()">Ya, Arsipkan</button>
            </div>

        </div>
    </div>
    <div id="popupGagal" class="popup-overlay">
        <div class="popup-box">

            <h3 style="color:#e74c3c;">Gagal</h3>
            <p>Nota tidak berhasil diarsipkan.<br>Silakan coba lagi.</p>

            <div class="popup-btn">
                <button class="btn-yes" onclick="tutupPopupGagal()">OK</button>
            </div>

        </div>
    </div>
    <div id="imagePreview" class="image-preview-overlay">
        <span class="close-btn" onclick="closePreview()">×</span>
        <img id="previewImg" src="">
    </div>
</body>

</html>
<script>
    let selectedId = null;
    let isPopupAnimating = false;

    function showPopup(id) {
        if (isPopupAnimating) return;

        selectedId = id;

        const popup = document.getElementById("popupKonfirmasi");

        popup.classList.remove("hide");
        popup.classList.remove("show");

        void popup.offsetWidth;

        popup.classList.add("show");
    }

    function tutupPopup() {
        const popup = document.getElementById("popupKonfirmasi");

        if (isPopupAnimating) return;
        isPopupAnimating = true;

        popup.classList.remove("show");
        popup.classList.add("hide");

        popup.addEventListener("transitionend", function handler() {
            popup.classList.remove("hide");
            isPopupAnimating = false;
            popup.removeEventListener("transitionend", handler);
        });
    }

    function lanjutArsip() {
        window.location.href = "proses_arsip.php?id=" + selectedId;
    }
    document.getElementById("popupKonfirmasi").addEventListener("click", function(e) {
        if (e.target === this) {
            tutupPopup();
        }
    });

    let isPreviewAnimating = false;

    function showPreview(src) {
        if (isPreviewAnimating) return;

        const overlay = document.getElementById("imagePreview");
        const img = document.getElementById("previewImg");

        img.src = src;

        overlay.classList.remove("hide");
        overlay.classList.remove("show");

        void overlay.offsetWidth;

        overlay.classList.add("show");
    }

    function closePreview() {
        const overlay = document.getElementById("imagePreview");

        if (isPreviewAnimating) return;
        isPreviewAnimating = true;

        overlay.classList.remove("show");
        overlay.classList.add("hide");

        overlay.addEventListener("transitionend", function handler() {
            overlay.classList.remove("hide");
            isPreviewAnimating = false;
            overlay.removeEventListener("transitionend", handler);
        });
    }

    document.getElementById("imagePreview").addEventListener("click", function(e) {
        if (e.target === this) {
            closePreview();
        }
    });

    function tutupPopupGagal() {
        document.getElementById("popupGagal").style.display = "none";
    }

    document.addEventListener("DOMContentLoaded", function() {

        const params = new URLSearchParams(window.location.search);

        if (params.get("status") === "gagal") {
            document.getElementById("popupGagal").style.display = "flex";
        }

    });
    document.addEventListener("DOMContentLoaded", function() {

        const cards = document.querySelectorAll(".form-card");

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {

                if (entry.isIntersecting) {

                    setTimeout(() => {
                        entry.target.classList.add("show");
                    }, index * 80);

                    observer.unobserve(entry.target);
                }

            });
        }, {
            threshold: 0.2
        });

        cards.forEach(card => observer.observe(card));

    });
</script>