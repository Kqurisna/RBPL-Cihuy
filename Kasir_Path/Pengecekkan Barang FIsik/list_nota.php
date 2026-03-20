<?php
$koneksi = mysqli_connect("localhost", "root", "", "pt_bumijaya");

$query = mysqli_query($koneksi, "SELECT * FROM nota ORDER BY id_nota DESC");
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Input Nota Barang Masuk</title>

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

            <h2>Input Nota Barang Masuk</h2>
        </div>

        <div class="header-circle-big"></div>
        <div class="header-circle-small"></div>
        <div class="header-circle-small_2"></div>
        <div class="header-circle-small_3"></div>

    </div>


    <div class="container">
        <?php while ($data = mysqli_fetch_assoc($query)) { ?>
            <div class="form-card">

                <h3 class="section-title">Nota</h3>

                <div class="form-group">
                    <label>Nomer Nota<span style="color:red">*</span></label>
                    <input type="text" name="nomer_nota" value="<?= $data['nomor_nota'] ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Tanggal Nota<span style="color:red">*</span></label>
                    <input type="date" name="tanggal_nota" value="<?= $data['tanggal_nota'] ?>" readonly>
                </div>
                <div class="circle" onclick="goToDetail(<?= $data['id_nota'] ?>)">
                    <img src="../asset_kasir/logo_masuk_id.png" alt="">
                </div>


            </div>
            <br>
        <?php } ?>
    </div>
</body>

</html>
<script>
    function goToDetail(id) {
        window.location.href = "cek_barang_fisik.php?id=" + id;
    }
</script>