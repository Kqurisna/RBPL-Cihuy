<?php
$koneksi = mysqli_connect("localhost", "root", "", "pt_bumijaya");

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'desc';
$sort = ($sort === 'asc') ? 'ASC' : 'DESC';

$query = mysqli_query($koneksi, "
    SELECT * FROM nota 
    WHERE status_laporan = 'disetujui' 
    AND status_arsip_laporan = 'sudah'
    ORDER BY tanggal_nota $sort
");
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$chooseDisetujui = ($filter == 'disetujui') ? 'btn-choose-disetujui' : '';
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

        .form-card {
            will-change: transform, opacity;
        }

        .form-card.exit {
            opacity: 0;
            transform: translateX(-100px);
            transition: all 0.3s ease;
        }

        .status-label {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 13px;
            font-weight: 500;
            padding-bottom: 1px;
        }

        .status-menunggu {
            border-bottom: 2px solid #5BE0E7;
        }

        .status-ditolak {
            border-bottom: 2px solid #40189F;

        }

        .status-setuju {
            border-bottom: 2px solid #5DADC1;

        }

        .filter-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 20px 0;
        }

        .filter-btn {
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            padding-bottom: 3px;
            border-bottom: 2px solid transparent;
            transition: all 0.25s ease;
            transform: scale(1);
        }

        .btn-choose-menunggu,
        .btn-choose-ditolak,
        .btn-choose-disetujui {
            transition: all 0.25s ease;
            transform: scale(1.05);
        }

        .btn-choose-menunggu {
            box-shadow: 0 2px 8px rgba(0, 188, 212, 0.2);
        }

        .btn-choose-ditolak {
            box-shadow: 0 2px 8px rgba(64, 24, 159, 0.2);
        }

        .btn-choose-disetujui {
            box-shadow: 0 2px 8px rgba(93, 173, 193, 0.2);
        }

        .filter-btn.menunggu {
            color: #000000;
        }

        .filter-btn.menunggu:hover {
            border-bottom: 2px solid #5BE0E7;
        }

        .filter-btn.ditolak {
            color: #000000;
        }

        .filter-btn.ditolak:hover {
            border-bottom: 2px solid #40189F;
        }

        .filter-btn.disetujui {
            color: #000000;
        }

        .filter-btn.disetujui:hover {
            border-bottom: 2px solid #5DADC1;
        }

        .btn-choose-menunggu {
            border: 2px solid #00bcd4;
            border-radius: 6px;
            padding: 2px 6px;
        }

        .btn-choose-ditolak {
            border: 2px solid #40189F;
            border-radius: 6px;
            padding: 2px 6px;
        }

        .btn-choose-disetujui {
            border: 2px solid #5DADC1;
            border-radius: 6px;
            padding: 2px 6px;
        }

        .sort-text {
            color: #9ca3af;
            font-size: 13px;
            font-weight: 400;
            transition: all 0.3s ease;
        }

        .sort-text.active {
            color: #3f7aa3;
            font-weight: 600;
        }

        .sorting-line {
            height: 2px;
            width: 100%;
            border-radius: 20px;
            background-color: #6AD2DE;
            margin-top: 1px;
        }
    </style>
</head>

<body>

    <div class="header">

        <div class="header-left">

            <div class="back-btn">
                <a href="arsip_laporan_menu.php" class="back-link">
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

    <div class="filter-container">
        <a href="?filter=disetujui" class="filter-btn disetujui <?= $chooseDisetujui ?>">Sudah Disetujui</a>
    </div>
    <div class="sorting-wrapper" style="display:flex; justify-content:center; margin-top:6px;">

        <div style="display:flex; flex-direction:column; align-items:flex-start;">

            <div style="display:flex; align-items:center; gap:8px;">

                <span style="color:#000; font-size:13px;">Sorting by</span>

                <a href="?filter=<?= $filter ?>&sort=asc"
                    onclick="return animSort(event, this)"
                    style="text-decoration:none;">
                    <span class="sort-text <?= ($sort == 'ASC') ? 'active' : '' ?>">
                        ASC
                    </span>
                </a>

                <a href="?filter=<?= $filter ?>&sort=desc"
                    onclick="return animSort(event, this)"
                    style="text-decoration:none;">
                    <span class="sort-text <?= ($sort == 'DESC') ? 'active' : '' ?>">
                        DESC
                    </span>
                </a>

            </div>

            <div class="sorting-line"></div>

        </div>

    </div>
    <div class="container">

        <?php if (mysqli_num_rows($query) > 0) { ?>

            <?php while ($data = mysqli_fetch_assoc($query)) { ?>
                <?php
                $statusText = "";
                $statusClass = "";

                if ($data['status_laporan'] == 'menunggu') {
                    $statusText = "Menunggu Persetujuan";
                    $statusClass = "status-menunggu";
                } elseif ($data['status_laporan'] == 'ditolak') {
                    $statusText = "Ditolak";
                    $statusClass = "status-ditolak";
                } elseif ($data['status_laporan'] == 'disetujui') {
                    $statusText = "Sudah Disetujui";
                    $statusClass = "status-setuju";
                }
                ?>
                <div class="form-card">
                    <div class="status-label <?= $statusClass ?>">
                        <?= $statusText ?>
                    </div>
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
            <?php
            $judul = "Tidak ada laporan barang masuk";
            $deskripsi = "Belum ada laporan barang masuk dari <strong>Kasir Toko</strong>";

            if ($filter == 'disetujui') {
                $judul = "Tidak ada laporan disetujui";
                $deskripsi = "Belum ada laporan yang sudah disetujui";
            }
            ?>
            <div class="empty-container">
                <div class="empty-icon">
                    <img src="../../UI_GENERAL/logo_x.png" alt="">
                </div>

                <div class="empty-title">
                    Arsip masih kosong
                </div>

                <div class="empty-line"></div>

                <div class="empty-desc">
                    Belum ada laporan barang masuk yang diarsipkan
                </div>
            </div>

        <?php } ?>

    </div>
</body>

</html>
<script>
    function goToDetail(id) {

        const cards = document.querySelectorAll(".form-card");

        cards.forEach((card, index) => {
            setTimeout(() => {
                card.classList.add("exit");
            }, index * 50);
        });

        setTimeout(() => {
            window.location.href = "detail_lihat_arsip_laporan.php?id=" + id;
        }, 300);
    }
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
    document.addEventListener("DOMContentLoaded", function() {

        const params = new URLSearchParams(window.location.search);
        const filterContainer = document.querySelector(".filter-container");

        if (params.has("filter") && filterContainer) {

            filterContainer.addEventListener("dblclick", function() {

                const cards = document.querySelectorAll(".form-card");

                cards.forEach((card, index) => {
                    setTimeout(() => {
                        card.classList.add("exit");
                    }, index * 40);
                });

                setTimeout(() => {
                    window.location.href = window.location.pathname;
                }, 250);

            });

        }

    });
    document.querySelectorAll(".filter-btn").forEach(btn => {
        btn.addEventListener("click", function(e) {

            const currentFilter = new URLSearchParams(window.location.search).get("filter");
            const targetFilter = this.getAttribute("href").split("=")[1];

            if (currentFilter === targetFilter) {
                e.preventDefault();
                window.location.href = window.location.pathname;
            }

        });
    });

    function animSort(e, el) {
        e.preventDefault();

        const all = document.querySelectorAll('.sort-text');

        all.forEach(item => item.classList.remove('active'));

        const text = el.querySelector('.sort-text');
        text.classList.add('active');

        setTimeout(() => {
            window.location.href = el.href;
        }, 250);

        return false;
    }
</script>