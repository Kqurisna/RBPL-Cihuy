<!doctype html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pengecekkan Barang Fisik</title>

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

    .back-btn img {
      width: 20px;
    }

    .logout-btn {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      background: #ffffff33;
      display: flex;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      z-index: 2;
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
      padding: 25px 20px 70px;
    }

    .page-title {
      font-size: 20px;
      font-weight: 600;
      margin-bottom: 20px;
      color: #1f2937;
    }

    .form-card {
      background: white;
      padding: 25px 20px 35px;
      border-radius: 24px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
      position: relative;
    }

    .form-group {
      margin-bottom: 18px;
    }

    .form-group label {
      font-size: 16px;
      font-weight: 800;
      display: block;
      margin-bottom: 8px;
      color: #111827;
    }

    .form-group input {
      width: 100%;
      height: 50px;
      border-radius: 16px;
      border: none;
      background: #e9edf2;
      padding: 0 15px;
      font-size: 14px;
      font-weight: 500;
      outline: none;
    }

    .status-wrapper {
      margin-top: 10px;
    }

    .status-badge {
      display: inline-block;
      padding: 6px 14px;
      border-radius: 14px;
      border: 2px solid #ff3b00;
      color: #ff3b00;
      font-size: 14px;
      font-weight: 500;
      background: white;
    }

    .expand-btn {
      position: absolute;
      bottom: -22px;
      left: 50%;
      transform: translateX(-50%);
      width: 42px;
      height: 42px;
      border-radius: 50%;
      background: #e2e8f0;
      display: flex;
      justify-content: center;
      align-items: center;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
      cursor: pointer;
      transition: 0.2s;
    }

    .expand-btn img {
      width: 22px;
      height: 22px;
    }

    .expand-btn:hover {
      transform: translateX(-50%) translateY(-3px);
    }

    @media (max-width:480px) {

      .header h2 {
        font-size: 16px;
      }

      .back-btn,
      .logout-btn {
        width: 34px;
        height: 34px;
      }

      .page-title {
        font-size: 18px;
      }

      .form-group label {
        font-size: 15px;
      }

      .form-group input {
        height: 46px;
      }

    }
  </style>
</head>

<body>

  <div class="header">
    <div class="header-left">
      <div class="back-btn">
        <img src="../asset_kasir/logo_back.png" alt="">
      </div>
      <h2>Pengecekkan Barang Fisik</h2>
    </div>

    <div class="header-circle-big"></div>
    <div class="header-circle-small"></div>
    <div class="header-circle-small_2"></div>
    <div class="header-circle-small_3"></div>

  </div>

  <div class="container">

    <h3 class="page-title">Input Hasil Pengecekkan Fisik Barang</h3>

    <div class="form-card">

      <div class="form-group">
        <label>Nomer Nota</label>
        <input type="text" name="nomer_nota">
      </div>

      <div class="form-group">
        <label>Tanggal Nota</label>
        <input type="text" name="tanggal_nota">
      </div>

      <div class="status-wrapper">
        <span class="status-badge">Belum Dicek</span>
      </div>

      <div class="expand-btn">
        <img src="../asset_kasir/logo_down.png" alt="Expand">
      </div>

    </div>

  </div>

</body>

</html>