<?php
$koneksi = mysqli_connect("localhost", "root", "", "pt_bumijaya");
$filter_bulan = isset($_GET['bulan']) ? $_GET['bulan'] : null;
$mode_bulan = isset($_GET['mode']) ? $_GET['mode'] : null;
if ($filter_bulan) {
    $query = mysqli_query($koneksi, "
        SELECT * FROM nota 
        WHERE status IN ('sesuai', 'cacat')
        AND DATE_FORMAT(tanggal_nota, '%Y-%m') = '$filter_bulan'
        ORDER BY tanggal_nota DESC
    ");
} else {
    $query = mysqli_query($koneksi, "
        SELECT * FROM nota 
        WHERE status IN ('sesuai', 'cacat') 
        ORDER BY tanggal_nota DESC
    ");
}
$jumlahData = mysqli_num_rows($query); ?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lihat Hasil Pemeriksaan Barang</title>

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

        .time-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.4s ease;
        }

        .time-info,
        .caption-time,
        .time-text {
            transition: all 0.4s ease;
        }

        .time-info {
            width: 17px;
            height: 17px;
            border-radius: 50%;
            background-color: #6AD2DE;
            position: relative;
            right: -5px;
            top: -3px;
        }

        .time-line {
            display: flex;
            flex-direction: column;
        }

        .time-text {
            font-size: 12px;
            font-weight: 600;
            color: #3f7aa3;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .time-text.with-icon {
            margin-bottom: 3px;
        }

        .time-text.no-icon {
            margin-bottom: 8px;
        }

        .caption-time {
            height: 3px;
            width: 100px;
            border-radius: 20px;
            background-color: #6AD2DE;
            margin-bottom: 10px;
        }

        .img_rubah_filter {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-time {
            width: 22px;
            height: 22px;
            position: relative;

        }

        .icon-time.mode-tanggal {
            top: 3px;
            right: -20px;
        }

        .icon-time.mode-bulan {
            top: 3px;
            right: -35px;
        }

        .mode-bulan .time-info {
            background-color: #4597a0;
        }

        .mode-bulan .caption-time {
            background-color: #4597a0;
        }

        .mode-bulan .time-text {
            color: #3f7aa3;
        }

        .fade-slide {
            opacity: 0;
            transform: translateY(10px);
            animation: fadeSlideIn 0.4s ease forwards;
        }

        @keyframes fadeSlideIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-card {
            opacity: 0;
            transform: translateY(20px) scale(0.98);
            transition: all 0.5s ease;
        }

        .form-card.show {
            opacity: 1;
            transform: translateY(0) scale(1);
            transition-delay: 0.05s;
        }
    </style>
</head>

<body>

    <div class="header">

        <div class="header-left">

            <div class="back-btn">
                <a href="../../User_Admin.php" class="back-link">
                    <img src="../../UI_GENERAL/logo_back.png" alt="Back">
                </a>
            </div>

            <h2>Lihat Hasil Pemeriksaan Barang</h2>
        </div>

        <div class="header-circle-big"></div>
        <div class="header-circle-small"></div>
        <div class="header-circle-small_2"></div>
        <div class="header-circle-small_3"></div>

    </div>
    <div class="container">

        <?php if ($jumlahData > 0) { ?>

            <?php
            $tanggal_sebelumnya = null;
            $isFirst = true;

            while ($data = mysqli_fetch_assoc($query)) {

                $tanggal_sekarang = $data['tanggal_nota'];
                $bulan = date("n", strtotime($tanggal_sekarang));

                $map_width = [
                    1 => 84,
                    2 => 88,
                    3 => 72,
                    4 => 58,
                    5 => 55,
                    6 => 62,
                    7 => 58,
                    8 => 76,
                    9 => 100,
                    10 => 81,
                    11 => 96,
                    12 => 96
                ];

                if ($mode_bulan == 'bulan') {
                    $text = date("F Y", strtotime($tanggal_sekarang));
                } else {
                    $text = date("d F Y", strtotime($tanggal_sekarang));
                }

                if ($mode_bulan == 'bulan') {

                    $final_width = isset($map_width[$bulan]) ? $map_width[$bulan] : 80;
                } else {

                    $char_length = mb_strlen($text);

                    $final_width = ($char_length * 6) + 20;

                    $adjust = [
                        1  => -6,
                        2  => -7,
                        3  => -8,
                        4  => -12,
                        5  => -8,
                        6  => -6,
                        7  => -8,
                        8  => -5,
                        9  => -4,
                        10 => -8,
                        11 => -4,
                        12 => -4
                    ];

                    $final_width += ($adjust[$bulan] ?? 0);

                    if (isset($adjust[$bulan])) {
                        $final_width += $adjust[$bulan];
                    }

                    $final_width = max(65, $final_width);
                }

                $group_sekarang = ($mode_bulan == 'bulan')
                    ? date("Y-m", strtotime($tanggal_sekarang))
                    : $tanggal_sekarang;
                if ($group_sekarang != $tanggal_sebelumnya) { ?>

                    <div class="time-wrapper fade-slide <?= $mode_bulan == 'bulan' ? 'mode-bulan' : '' ?>">
                        <div class="time-info"></div>

                        <div class="time-line">
                            <div class="time-text <?= $isFirst ? 'with-icon' : 'no-icon' ?>">
                                <span>
                                    <?php
                                    if ($mode_bulan == 'bulan') {
                                        echo date("F Y", strtotime($tanggal_sekarang));
                                    } else {
                                        echo date("d F Y", strtotime($tanggal_sekarang));
                                    }
                                    ?>
                                </span>
                                <div class="img_rubah_filter">
                                    <?php if ($isFirst) { ?>
                                        <?php if ($mode_bulan == 'bulan') { ?>
                                            <a href="?">
                                                <img src="../../UI_ADMIN/logo_swap.png" class="icon-time <?= $mode_bulan == 'bulan' ? 'mode-bulan' : 'mode-tanggal' ?>" title="Kembali ke mode tanggal">
                                            </a>
                                        <?php } else { ?>
                                            <a href="?mode=bulan" onclick="return smoothSwitch(event, this.href)">
                                                <img src="../../UI_ADMIN/logo_swap.png" class="icon-time <?= $mode_bulan == 'bulan' ? 'mode-bulan' : 'mode-tanggal' ?>" title="Ubah ke mode bulan">
                                            </a>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="caption-time" style="width: <?= $final_width ?>px;"></div>
                        </div>
                    </div>

                <?php
                    $tanggal_sebelumnya = $group_sekarang;
                    $isFirst = false;
                }
                ?>

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

                    <div class="circle" onclick="goToDetail(<?= $data['id_nota'] ?>)">
                        <img src="../../Kasir_Path/asset_kasir/logo_masuk_id.png">
                    </div>
                </div>
                <br>

            <?php } ?>

        <?php } else { ?>

            <div class="empty-container">
                <div class="empty-icon">
                    <img src="../../UI_GENERAL/logo_x.png">
                </div>

                <div class="empty-title">
                    Tidak ada data barang yang sudah di cek dari Kasir Toko
                </div>

                <div class="empty-line"></div>

                <div class="empty-desc">
                    Belum ada hasil pemeriksaan yang tersimpan
                </div>
            </div>

        <?php } ?>

    </div>
</body>

</html>
<script>
    function goToDetail(id) {
        window.location.href = "lihat_hasil_pemeriksaan.php?id=" + id;
    }

    function smoothSwitch(e, url) {
        e.preventDefault();

        document.querySelectorAll('.time-wrapper').forEach(el => {
            el.style.opacity = "0";
            el.style.transform = "translateY(10px)";
        });

        setTimeout(() => {
            window.location.href = url;
        }, 250);

        return false;
    }
    document.addEventListener("DOMContentLoaded", function() {

        const cards = document.querySelectorAll(".form-card");

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {

                    setTimeout(() => {
                        entry.target.classList.add("show");
                    }, index * 100);

                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.2
        });

        cards.forEach(card => {
            observer.observe(card);
        });

    });
</script>