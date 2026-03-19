<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Input Nota Barang Masuk</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap"
        rel="stylesheet" />

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

        .success-container {
            padding: 60px 25px;
            text-align: center;
        }

        .success-icon {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 25px;
        }

        .success-icon img {
            width: 70px;
            margin-top: 140px;
            height: 70px;
        }

        .success-title {
            font-weight: 600;
            color: #111827;
        }

        .success-title p {
            font-size: 18px;
        }

        .success-line {
            width: 200px;
            height: 3px;
            background: #48b5c1;
            margin: 12px auto 20px;
            border-radius: 3px;
        }

        .success-desc {
            font-size: 15px;
            font-weight: 500;
            color: #4b5563;
            line-height: 1.6;
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

    <div class="success-container">
        <div class="success-icon">
            <img src="../asset_admingudang/Icon.png" alt="" />
        </div>

        <div class="success-title">
            <p>Berhasil Menyimpan Nota Pembelian</p>
        </div>

        <div class="success-line"></div>

        <div class="success-desc">
            Data nota pembelian telah tersimpan di sistem dan menunggu proses pengecekan oleh <strong>Kasir Toko</strong>
        </div>
    </div>
</body>

</html>