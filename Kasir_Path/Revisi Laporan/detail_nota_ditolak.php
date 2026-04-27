<?php
$koneksi = mysqli_connect("localhost", "root", "", "pt_bumijaya");

$id_nota = isset($_GET['id']) ? intval($_GET['id']) : 0;

$queryNota = mysqli_query($koneksi, "SELECT * FROM nota WHERE id_nota = $id_nota");
$dataNota = mysqli_fetch_assoc($queryNota);
$queryApproval = mysqli_query($koneksi, "
    SELECT * 
    FROM approval_manager 
    WHERE id_nota = $id_nota 
    ORDER BY id_approval DESC 
    LIMIT 1
");
$dataApproval = mysqli_fetch_assoc($queryApproval);
$mode = 'edit';
$queryDetail = mysqli_query($koneksi, "SELECT * FROM detail_barang WHERE id_nota = $id_nota");

$validasiList = [];
$queryValidasi = mysqli_query($koneksi, "SELECT * FROM validasi_kasir WHERE id_nota = $id_nota ORDER BY id_validasi ASC");
$queryRetur = mysqli_query($koneksi, "
    SELECT rd.* 
    FROM retur_detail rd
    JOIN retur r ON rd.id_retur = r.id_retur
    WHERE r.id_nota = $id_nota
");

$returList = [];
while ($r = mysqli_fetch_assoc($queryRetur)) {
    $returList[$r['id_detail']] = $r;
}
while ($v = mysqli_fetch_assoc($queryValidasi)) {
    $validasiList[] = $v;
}
$tanggapanList = [];

$queryTanggapan = mysqli_query($koneksi, "
    SELECT ts.*, rd.id_detail
    FROM tanggapan_supplier ts
    JOIN retur r ON ts.id_retur = r.id_retur
    JOIN retur_detail rd ON rd.id_retur = r.id_retur
    WHERE r.id_nota = $id_nota
");

while ($t = mysqli_fetch_assoc($queryTanggapan)) {
    $tanggapanList[$t['id_detail']] = $t;
}
$adaCacat = false;
foreach ($validasiList as $v) {
    if (($v['hasil'] ?? '') == 'cacat') {
        $adaCacat = true;
        break;
    }
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Revisi Laporan</title>

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

        .page-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #1f2937;
        }

        .form-card {
            background: white;
            padding: 1px 20px 35px;
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
            position: relative;
            margin-bottom: 10px;
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
            margin-bottom: 15px;
        }

        .form-group_2 label {
            font-size: 13px;
            font-weight: 800;
            display: block;
            margin-bottom: 5px;
            color: #ffffff;
        }

        .form-group_2 input {
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
        }

        .expand-btn img {
            width: 22px;
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
            cursor: pointer;
            transition: all 0.2s ease;
            pointer-events: none;
        }

        .chip.active {
            background: #3f7aa3;
            color: white;
            border-color: #3f7aa3;
        }

        .plus_btn {
            width: 35px;
            height: 35px;
            background: #426279;
            border-radius: 10px;

            display: flex;
            justify-content: center;
            align-items: center;

            cursor: pointer;
        }

        .minus_btn {
            width: 35px;
            height: 35px;
            background: #293d4b;
            border-radius: 10px;

            display: flex;
            justify-content: center;
            align-items: center;

            cursor: pointer;
        }

        .plus_btn img {
            width: 18px;
            height: 18px;
            object-fit: contain;
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

        .welcome-card_2 h3 {
            font-weight: 600;
            margin-bottom: 10px;
        }

        .welcome-card_2 p {
            font-size: 14px;
            line-height: 1.6;
        }

        .welcome-circle_2a {
            position: absolute;
            width: 62px;
            height: 62px;
            background: #48b5c1;
            border-radius: 50%;
            top: -40px;
            left: -10px;
        }

        .welcome-circle_2b {
            position: absolute;
            width: 26px;
            height: 26px;
            background: #3c96a0;
            border-radius: 50%;
            top: -48px;
            left: 55px;
        }

        .welcome-circle_2c {
            position: absolute;
            width: 17px;
            height: 17px;
            background: #66d2de;
            border-radius: 50%;
            top: -21px;
            left: 77px;
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

        .btn-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .upload-box {
            margin-top: 15px;
            background: #e5e5e5;
            border-radius: 20px;
            padding: 30px 20px;
            text-align: center;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: 0.2s;
        }

        .upload-box:hover {
            background: #dcdcdc;
        }

        .upload-icon {
            width: 40px;
            margin-bottom: 10px;
            opacity: 0.6;
        }

        .upload-text {
            font-size: 16px;
            font-weight: 500;
            color: #9ca3af;
        }

        .upload-subtext {
            font-size: 13px;
            color: #b0b0b0;
        }

        .btn-login {
            margin: 10px auto;
            display: block;
            width: 80%;
            justify-content: center;
            padding: 10px;
            border: none;
            border-radius: 21px;
            background-color: #67A2CD;
            color: white;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
        }

        .success-line {
            display: block;
            justify-content: center;
            margin: 10px auto;
            width: 40%;
            height: 3px;
            background: #c3e0ef;
            border-radius: 3px;
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

        .fade-out {
            animation: fadeOut 0.25s ease forwards;
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }

            to {
                opacity: 0;
                transform: translateY(-10px);
            }
        }

        .plus_btn:active,
        .minus_btn:active {
            transform: scale(0.9);
        }

        .plus_btn,
        .minus_btn {
            transition: 0.15s;
        }

        .error-input {
            border: 2px solid red !important;
        }

        .chip.error {
            border-color: red;
        }

        html {
            scroll-behavior: smooth;
        }

        .chip-status-container {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .chip-status {
            padding: 10px 20px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border: 2px solid #ccc;
            transition: all 0.3s ease;
            user-select: none;
        }

        .chip-status.sesuai {
            border-color: #2ecc71;
            color: #2ecc71;
            background: #f0fff5;
        }

        .chip-status.cacat {
            border-color: #e74c3c;
            color: #e74c3c;
            background: #fff5f5;
        }

        .chip-status.active.sesuai {
            background: #2ecc71;
            color: white;
            box-shadow: 0 4px 10px rgba(46, 204, 113, 0.3);
        }

        .chip-status.active.cacat {
            background: #e74c3c;
            color: white;
            box-shadow: 0 4px 10px rgba(231, 76, 60, 0.3);
        }

        .keluhan-box {
            display: none;
            margin-top: 10px;
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
            cursor: pointer;
            border: 1.5px solid #ccc;
            transition: all 0.25s ease;
            background: #f9f9f9;
            color: #555;
        }

        .chip-status:hover {
            opacity: 0.8;
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

        .keluhan-box {
            display: none;
            margin-top: 10px;
        }

        .chip {
            transition: all 0.2s ease;
        }

        .chip:hover {
            transform: scale(1.05);
        }

        .textarea {
            width: 100%;
            border-radius: 16px;
            border: none;
            min-height: 120px;
            background: #e9edf2;

            padding: 14px 4px 10px 15px;
            text-align: left;
            font-size: 12px;
            font-weight: 300;

            outline: none;
            resize: none;

            line-height: 1.4;
        }

        .textarea_2 {
            width: 100%;
            border-radius: 16px;
            border: none;
            min-height: 40px;
            background: #e9edf2;

            padding: 10px 4px 10px 15px;
            text-align: left;
            font-size: 12px;
            font-weight: 300;

            outline: none;
            resize: none;

            line-height: 1.4;
        }

        .section-title {
            margin-bottom: 10px;
        }

        .img-preview {
            margin-top: 10px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            transition: 0.3s;
        }

        .img-preview img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            display: block;
        }

        .img-preview:hover {
            transform: scale(1.02);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
        }

        .img-preview {
            background: #fff;
            padding: 8px;
            border-radius: 18px;
        }

        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.85);
            z-index: 9999;

            justify-content: center;
            align-items: center;
        }

        .modal img {
            max-width: 90%;
            max-height: 90%;
            border-radius: 12px;
            object-fit: contain;

            animation: zoomIn 0.25s ease;
        }

        .close {
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 32px;
            color: white;
            cursor: pointer;
            font-weight: bold;
        }

        /* ANIMASI MASUK */
        @keyframes zoomIn {
            from {
                transform: scale(0.8);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* ANIMASI KELUAR */
        @keyframes zoomOut {
            from {
                transform: scale(1);
                opacity: 1;
            }

            to {
                transform: scale(0.8);
                opacity: 0;
            }
        }

        /* CLASS KELUAR */
        .modal.hide img {
            animation: zoomOut 0.25s ease forwards;
        }

        .close {
            position: absolute;
            top: 20px;
            right: 35px;
            color: white;
            font-size: 35px;
            cursor: pointer;
        }

        .btn-retur {
            width: 80%;
            margin-bottom: 0px;
            padding: 5px;
            border: none;
            border-radius: 15px;
            background-color: #67A2CD;
            color: white;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            box-shadow: 0 6px 15px rgba(63, 122, 163, 0.3);
            opacity: 0;
            transform: translateY(20px);
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .btn-retur.show {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        .btn-retur:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(63, 122, 163, 0.4);
        }

        .btn-retur:active {
            transform: scale(0.97);
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
            color: #000000;
        }

        .status-menunggu .status-line {
            background: #5BE0E7;
        }

        .status-ditolak {
            color: #000000;
        }

        .status-ditolak .status-line {
            background: #40189F;
        }

        .status-disetujui {
            color: #000000;
        }

        .status-disetujui .status-line {
            background: #5DADC1;
        }

        .status-label {
            text-align: right;
        }

        .approval-box {
            margin-top: 20px;
            text-align: center;
        }

        .approval-group {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .btn-approval {
            position: relative;
            padding: 3px 30px;
            border-radius: 10px;
            border: 2px solid #ccc;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.25s ease;
            font-size: 14px;
        }

        .btn-approval::after {
            content: "";
            position: absolute;
            bottom: -6px;
            left: 50%;
            transform: translateX(-50%);
            width: 30%;
            height: 1px;
            border-radius: 3px;
            background: currentColor;
            transition: all 0.3s ease;
        }

        .btn-approval.active::after {
            width: 100%;
        }

        .btn-approval.approve {
            border-color: #2ecc71;
            color: #2ecc71;
        }

        .btn-approval.reject {
            border-color: #e74c3c;
            color: #e74c3c;
        }

        .btn-approval.active.approve {
            background: #2ecc71;
            color: white;
        }

        .btn-approval.active.reject {
            background: #e74c3c;
            color: white;
        }

        .reject-box {
            display: none;
            margin-top: 20px;
        }

        .reject-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            margin-top: 20px;
        }

        .reject-header h3 {
            font-size: 14px;
            font-weight: 800;
            display: block;
            margin-bottom: 5px;
            color: #111827;
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
            top: 4px;
            right: 0px;
        }

        .dots span:nth-child(2) {
            width: 18px;
            height: 18px;
            background: #98DFE7;
            top: 1px;
            right: -2px;
        }

        .dots span:nth-child(3) {
            width: 26px;
            height: 26px;
            background: #ABDEE3;
            top: -3px;
            right: -4px;
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
            min-height: 120px;
            font-weight: 400;
        }

        .action-area {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .reject-box {
            display: none;
            margin-top: 10px;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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

        .textarea-dynamic {
            width: 100%;
            border: none;
            border-radius: 16px;
            background: #e9edf2;
            padding: 5px 15px;
            font-size: 14px;
            line-height: 1.5;
            min-height: 120px;
            resize: none;
            overflow: hidden;
        }

        .btn-approval[style] {
            cursor: not-allowed;
        }

        .upload-box-modern {
            margin-top: 10px;
            background: #e5e5e5;
            border-radius: 20px;
            padding: 35px;
            text-align: center;
            cursor: pointer;
        }

        .upload-text-modern {
            font-size: 14px !important;
            color: #9ca3af;
        }

        .upload-subtext-modern {
            font-size: 11px !important;
            color: #b0b0b0;
        }

        .upload-text-modern-box-2 {
            font-size: 15px !important;
            color: #9ca3af;
        }

        .upload-subtext-modern-box-2 {
            font-size: 12px !important;
            color: #b0b0b0;
        }

        .preview-wrapper {
            position: relative;
            margin-top: 10px;
        }

        .preview-img {
            display: none;
            width: 100%;
            border-radius: 12px;
        }

        .remove-btn {
            display: none;
            position: absolute;
            top: 8px;
            right: 8px;
            background: white;
            color: #5bb7c5;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            text-align: center;
            line-height: 25px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div id="imageModal" class="modal">
        <span class="close">&times;</span>
        <img id="modalImg">
    </div>
    <div class="header">

        <div class="header-left">

            <div class="back-btn">
                <a href="../Revisi Laporan/list_nota_ditolak.php" class="back-link">
                    <img src="../../UI_GENERAL/logo_back.png" alt="Back">
                </a>
            </div>

            <h2>Revisi Laporan</h2>
        </div>

        <div class="header-circle-big"></div>
        <div class="header-circle-small"></div>
        <div class="header-circle-small_2"></div>
        <div class="header-circle-small_3"></div>

    </div>

    <form action="input_data_revisi_laporan.php" method="POST" enctype="multipart/form-data" data-mode="<?= $mode ?>">
        <input type="hidden" name="id_nota" value="<?= $id_nota ?>">
        <div class="container">

            <div class="form-card">
                <div class="form-group" style="position:relative;">
                    <label>Nomer Nota</label>

                    <input type="text" name="nomer_nota" value="<?= $dataNota['nomor_nota'] ?>">

                    <?php
                    $status = $dataNota['status_laporan'];

                    $text = '';
                    $class = '';

                    if ($status == 'belum_diajukan') {
                        $text = 'Belum Diajukan';
                        $class = 'status-belum';
                    } elseif ($status == 'menunggu') {
                        $text = 'Menunggu Persetujuan';
                        $class = 'status-menunggu';
                    } elseif ($status == 'ditolak') {
                        $text = 'Ditolak';
                        $class = 'status-ditolak';
                    } elseif ($status == 'disetujui') {
                        $text = 'Disetujui';
                        $class = 'status-disetujui';
                    }
                    ?>

                </div>

                <div class="form-group">
                    <label>Tanggal Nota</label>
                    <input type="date" name="tanggal_nota" value="<?= $dataNota['tanggal_nota'] ?>">
                </div>

                <div class="form-group">
                    <label>Nama Supplier</label>
                    <input type="text" name="supplier" value="<?= $dataNota['supplier'] ?>">
                    <?php if ($dataNota['status_laporan'] === 'ditolak') { ?>

                        <div id="rejectBox" class="reject-box" style="display:block; margin-top:15px;">

                            <div class="reject-header">
                                <h3>Catatan Revisi</h3>
                                <div class="dots">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </div>

                            <div class="reject-card">
                                <textarea class="auto-height" readonly>
<?= $dataApproval ? $dataApproval['catatan_revisi'] : 'Tidak ada catatan revisi' ?>
                                </textarea>
                            </div>

                        </div>

                    <?php } ?>
                    <div class="form-group">
                        <label>Jenis Barang</label>
                        <div class="chip-container">
                            <?php
                            $jenisList = [
                                "Material Bangunan",
                                "Besi & Logam",
                                "Listrik",
                                "Keramik & Lantai",
                                "Alat Pertukangan",
                                "Kayu & Olahan"
                            ];

                            $selectedJenis = array_map('trim', explode(",", $dataNota['jenis_barang']));

                            foreach ($jenisList as $jenis) {
                                $active = in_array($jenis, $selectedJenis) ? "active" : "";
                                echo "<span class='chip $active'>$jenis</span>";
                            }
                            ?>
                        </div>

                        <input type="hidden" name="jenis_barang" id="jenisBarangInput" value="<?= $dataNota['jenis_barang'] ?>">
                        <small id="errorJenis" style="color:red; display:none;">
                            Wajib pilih minimal 1 jenis barang!
                        </small>
                    </div>



                    <div class="container_2">
                        <div class="welcome-card_2">

                            <div id="inputBarangContainer">

                                <?php
                                $no = 1;
                                while ($detail = mysqli_fetch_assoc($queryDetail)) {
                                    $validasi = $validasiList[$no - 1];
                                    $status = $validasi['hasil'];
                                ?>

                                    <div class="item">

                                        <div class="card-dots_2_1"><span></span></div>
                                        <div class="card-dots_2_2"><span></span></div>
                                        <div class="card-dots_2_3"><span></span></div>

                                        <div class="form-group">
                                            <label>Nama Barang ke-<?= $no ?></label>
                                            <input type="text" name="barang[]" value="<?= $detail['nama_barang'] ?>">
                                        </div>

                                        <div class="form-group">
                                            <label>Jumlah barang ke-<?= $no ?></label>
                                            <input type="number" name="jumlah[]" value="<?= $detail['jumlah_barang'] ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Kondisi Barang</label>

                                            <div class="chip-container-status">

                                                <span class="chip-status sesuai <?= $status == 'sesuai' ? 'active' : '' ?>">
                                                    Sesuai
                                                </span>

                                                <span class="chip-status cacat <?= $status == 'cacat' ? 'active' : '' ?>">
                                                    Cacat
                                                </span>

                                            </div>
                                        </div>

                                        <?php if ($status == 'cacat') { ?>
                                            <?php
                                            $idDetail = $detail['id_detail'];
                                            $dataRetur = isset($returList[$idDetail]) ? $returList[$idDetail] : null;
                                            $dataTanggapan = isset($tanggapanList[$idDetail]) ? $tanggapanList[$idDetail] : null;
                                            ?>

                                            <div class="form-group">
                                                <label>Jumlah Retur</label>
                                                <input type="number"
                                                    name="jumlah_retur[<?= $detail['id_detail'] ?>]"
                                                    value="<?= $dataRetur ? $dataRetur['jumlah_retur'] : '' ?>"
                                                    min="0"
                                                    max="<?= $detail['jumlah_barang'] ?>">
                                            </div>
                                            <div class="keluhan-box" style="display:block;">

                                                <div class="form-group">
                                                    <label>Keterangan / Keluhan</label>
                                                    <textarea name="keterangan[<?= $detail['id_detail'] ?>]" class="textarea_2"><?= $validasi['keterangan'] ?></textarea>
                                                </div>

                                                <label>Foto Bukti Lama</label>

                                                <?php if ($validasi['foto_bukti']) { ?>
                                                    <div class="img-preview" onclick="openModal(this)">
                                                        <img src="../../Kasir_Path/Pengecekkan%20Barang%20FIsik/uploads/bukti/<?= $validasi['foto_bukti'] ?>">
                                                    </div>
                                                <?php } else { ?>
                                                    <p style="font-size:13px; color:#9ca3af;">Tidak ada foto</p>
                                                <?php } ?>

                                                <input type="hidden" name="foto_bukti_lama[<?= $detail['id_detail'] ?>]" value="<?= $validasi['foto_bukti'] ?>">
                                                <label style="margin-top:10px;">Upload Foto Baru</label>

                                                <div class="upload-container">

                                                    <div class="upload-box-modern" onclick="triggerUpload(this)">
                                                        <img src="../../UI_GENERAL/logo_plus.png" class="upload-icon-modern">

                                                        <p class="upload-text-modern">Unggah Foto Retur</p>
                                                        <p class="upload-subtext-modern">(JPG / PNG, maks. 5 MB)</p>

                                                        <input type="file" name="foto_retur[<?= $detail['id_detail'] ?>]" accept="image/*" hidden>
                                                    </div>

                                                    <div class="preview-wrapper"></div>

                                                </div>

                                            </div>
                                        <?php } ?>
                                        <div class="success-line"></div>
                                    </div>
                                <?php
                                    $no++;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $pathFoto = "../../AdminGudang_Path/Input Nota Barang Masuk/" . $dataNota['foto_nota'];
                ?>

                <?php
                $dataTanggapan = null;
                if (!empty($tanggapanList)) {
                    $dataTanggapan = reset($tanggapanList);
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
                                <textarea name="tanggapan_supplier" class="textarea">
<?= $dataTanggapan ? $dataTanggapan['tanggapan'] : '' ?>
</textarea>
                            </div>

                            <div class="form-group">
                                <div class="form-group_2">
                                    <label>Bukti Tanggapan Supplier</label>

                                    <label>Bukti Lama Supplier</label>

                                    <?php
                                    $pathSupplier = "../../AdminGudang_Path/Input Konfirmasi Retur Supplier/uploads/tanggapan_supplier/" . ($dataTanggapan['lampiran'] ?? '');
                                    if ($dataTanggapan && !empty($dataTanggapan['lampiran']) && file_exists($pathSupplier)) {
                                    ?>
                                        <div class="img-preview" onclick="openModal(this)">
                                            <img src="<?= $pathSupplier ?>">
                                        </div>
                                    <?php } else { ?>
                                        <p style="font-size:13px; color:#9ca3af;">Tidak ada bukti</p>
                                    <?php } ?>

                                    <input type="hidden" name="lampiran_lama" value="<?= $dataTanggapan['lampiran'] ?? '' ?>">

                                    <label style="margin-top:10px;">Upload Bukti Baru</label>

                                    <div class="upload-container">
                                        <div class="upload-box-modern" onclick="triggerUpload(this)">
                                            <img src="../../UI_GENERAL/logo_plus.png" class="upload-icon-modern">

                                            <p class="upload-text-modern">Unggah Bukti Supplier</p>
                                            <p class="upload-subtext-modern">(JPG / PNG, maks. 5 MB)</p>

                                            <input type="file" name="foto_supplier" accept="image/*" hidden>
                                        </div>

                                        <div class="preview-wrapper"></div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                <?php } ?>

                <div class="welcome-card_3">
                    <div class="form-card_2">

                        <div class="box_1"></div>
                        <div class="box_2"></div>
                        <div class="box_3"></div>

                        <div class="form-group_2">
                            <label>Foto Nota Lama</label>

                            <?php
                            if (!empty($dataNota['foto_nota']) && file_exists($pathFoto)) {
                            ?>
                                <div class="img-preview" onclick="openModal(this)">
                                    <img src="<?= $pathFoto ?>">
                                </div>
                            <?php } else { ?>
                                <p style="font-size:13px; color:#9ca3af;">Tidak ada foto lama</p>
                            <?php } ?>

                            <input type="hidden" name="foto_nota_lama" value="<?= $dataNota['foto_nota'] ?>">

                            <label style="margin-top:10px;">Upload Foto Nota Baru</label>

                            <div class="upload-container">
                                <div class="upload-box-modern" onclick="triggerUpload(this)">
                                    <img src="../../UI_GENERAL/logo_plus.png" class="upload-icon-modern">

                                    <p class="upload-text-modern">Unggah Foto Nota</p>
                                    <p class="upload-subtext-modern">(JPG / PNG, maks. 5 MB)</p>

                                    <input type="file" name="foto_nota" accept="image/*" hidden>
                                </div>

                                <div class="preview-wrapper"></div>
                            </div>

                        </div>

                    </div>
                </div>

            </div>


        </div>
        <div class="action-area">
            <button type="submit" name="submit" class="btn-login">Submit Data Nota</button>
        </div>

        </div>
    </form>
</body>

</html>
<script>
    function autoResize(el) {
        el.style.height = "auto";
        el.style.height = el.scrollHeight + "px";
    }
    let selectedItems = "<?= $dataNota['jenis_barang'] ?>".split(",");
    const chips = document.querySelectorAll(".chip");
    chips.forEach(chip => {
        chip.addEventListener("click", () => {

            const value = chip.innerText;

            if (selectedItems.includes(value)) {
                selectedItems = selectedItems.filter(item => item !== value);
                chip.classList.remove("active");
            } else {
                selectedItems.push(value);
                chip.classList.add("active");
            }

            document.getElementById("jenisBarangInput").value = selectedItems.join(",");
        });
    });
    chips.forEach(chip => {
        if (selectedItems.includes(chip.innerText)) {
            chip.classList.add("active");
        }
    });
    let count = 1;

    const container = document.getElementById("inputBarangContainer");

    function updateLabels() {
        const items = container.querySelectorAll(".item");

        items.forEach((item, index) => {
            const nomor = index + 1;

            const labels = item.querySelectorAll("label");

            labels[0].innerText = `Nama Barang ke-${nomor}`;
            labels[1].innerText = `Jumlah Barang ke-${nomor}`;
        });

        count = items.length;
    }

    function updateMinusState() {
        if (count === 1) {
            btnHapus.classList.add("disabled");
        } else {
            btnHapus.classList.remove("disabled");
        }
    }

    function updateDivider() {
        const items = container.querySelectorAll(".item");

        items.forEach((item, index) => {
            let line = item.querySelector(".success-line");

            if (!line) {
                line = document.createElement("div");
                line.classList.add("success-line");
                item.appendChild(line);
            }

            if (items.length > 1 && index !== items.length - 1) {
                line.style.display = "block";
            } else {
                line.style.display = "none";
            }
        });
    }

    document.addEventListener("keydown", function(e) {
        if (e.target.classList.contains("input-number")) {
            if (e.key === 'e' || e.key === 'E' || e.key === '+' || e.key === '-') {
                e.preventDefault();
            }
        }
    });
    const inputJenis = document.getElementById("jenisBarangInput");
    const errorMsg = document.getElementById("errorJenis");




    function setStatus(no, status, el) {

        let container = el.parentElement;
        let chips = container.querySelectorAll('.chip-status');

        let input = document.getElementById('statusInput' + no);
        let box = document.getElementById('keluhanBox' + no);

        if (el.classList.contains("active")) {

            chips.forEach(chip => chip.classList.remove('active'));

            input.value = "";

            box.style.display = "none";

            return;
        }

        chips.forEach(chip => chip.classList.remove('active'));

        el.classList.add('active');
        input.value = status;

        if (status === 'cacat') {
            box.style.display = 'block';
        } else {
            box.style.display = 'none';
        }
    }


    function closeModal() {
        document.getElementById("imageModal").style.display = "none";
    }
    document.querySelectorAll('.textarea').forEach(el => {
        el.style.height = 'auto';
        el.style.height = el.scrollHeight + 'px';
    });
    document.addEventListener("DOMContentLoaded", function() {

        const cards = document.querySelectorAll(".form-card");

        cards.forEach((card, index) => {

            setTimeout(() => {
                card.classList.add("show");
            }, index * 100);

        });

    });


    function openModal(el) {
        const img = el.querySelector("img");
        const modal = document.getElementById("imageModal");
        const modalImg = document.getElementById("modalImg");

        modal.style.display = "flex";
        modalImg.src = img.src;
    }
    document.addEventListener("DOMContentLoaded", function() {

        const modalImg = document.getElementById("modalImg");

        let lastTap = 0;
        let isZoomed = false;
        let scale = 1;
        let posX = 0;

        modalImg.addEventListener("dblclick", function() {

            if (!isZoomed) {

                scale = 2;
                posX = 0;

                modalImg.style.transform = `scale(${scale}) translateX(0px)`;
                modalImg.style.cursor = "grab";

                isZoomed = true;

            } else {

                scale = 1;
                posX = 0;

                modalImg.style.transform = "scale(1) translateX(0px)";
                modalImg.style.cursor = "zoom-in";

                isZoomed = false;
            }

        });
        let startX = 0;
        let isDragging = false;

        modalImg.addEventListener("mousedown", function(e) {
            if (!isZoomed) return;

            isDragging = true;
            startX = e.clientX - posX;

            modalImg.style.cursor = "grabbing";
        });

        document.addEventListener("mousemove", function(e) {
            if (!isDragging) return;

            posX = e.clientX - startX;

            modalImg.style.transform = `scale(${scale}) translateX(${posX}px)`;
        });

        document.addEventListener("mouseup", function() {
            isDragging = false;

            if (isZoomed) {
                modalImg.style.cursor = "grab";
            }
        });
        modalImg.addEventListener("touchstart", function(e) {
            if (!isZoomed) return;

            const touch = e.touches[0];

            isDragging = true;
            startX = touch.clientX - posX;
        });

        modalImg.addEventListener("touchmove", function(e) {
            if (!isDragging) return;

            const touch = e.touches[0];

            posX = touch.clientX - startX;

            modalImg.style.transform = `scale(${scale}) translateX(${posX}px)`;
        });

        modalImg.addEventListener("touchend", function() {
            isDragging = false;
        });

        modalImg.addEventListener("touchend", function(e) {
            const currentTime = new Date().getTime();
            const tapLength = currentTime - lastTap;

            if (tapLength < 300 && tapLength > 0) {
                zoomHandler(e);
                e.preventDefault();
            }

            lastTap = currentTime;
        });

        function zoomHandler(e) {

            if (!isZoomed) {

                const rect = modalImg.getBoundingClientRect();

                let x = 50;
                let y = 50;

                if (e.changedTouches) {
                    const touch = e.changedTouches[0];
                    x = ((touch.clientX - rect.left) / rect.width) * 100;
                    y = ((touch.clientY - rect.top) / rect.height) * 100;
                } else {
                    x = ((e.clientX - rect.left) / rect.width) * 100;
                    y = ((e.clientY - rect.top) / rect.height) * 100;
                }

                modalImg.style.transformOrigin = `${x}% ${y}%`;
                modalImg.style.transform = "scale(2)";
                modalImg.style.cursor = "zoom-out";

                isZoomed = true;

            } else {

                modalImg.style.transform = "scale(1)";
                modalImg.style.transformOrigin = "center";
                modalImg.style.cursor = "zoom-in";

                isZoomed = false;
            }
        }

    });
    document.querySelector("form").addEventListener("submit", function(e) {

        let firstError = null;

        const inputs = document.querySelectorAll("input[required], textarea[required]");

        inputs.forEach(input => {
            if (!input.value.trim()) {
                input.classList.add("error-input");

                if (!firstError) {
                    firstError = input;
                }
            } else {
                input.classList.remove("error-input");
            }
        });

        if (selectedItems.length === 0) {

            errorMsg.style.display = "block";
            chips.forEach(c => c.classList.add("error"));

            if (!firstError) {
                firstError = document.querySelector(".chip-container");
            }

        } else {
            errorMsg.style.display = "none";
            chips.forEach(c => c.classList.remove("error"));
        }

        let statusInputs = document.querySelectorAll("[id^='statusInput']");
        let belumPilih = false;

        statusInputs.forEach(input => {
            if (input.value === "") {
                belumPilih = true;
            }
        });

        if (belumPilih) {
            alert("Semua kondisi barang harus dipilih!");

            if (!firstError) {
                firstError = statusInputs[0];
            }
        }

        if (firstError) {
            e.preventDefault();

            firstError.scrollIntoView({
                behavior: "smooth",
                block: "center"
            });

            if (firstError.tagName === "INPUT" || firstError.tagName === "TEXTAREA") {
                firstError.focus();
            }
        }

    });

    function setApproval(value, el) {
        const buttons = document.querySelectorAll(".btn-approval");
        const submitBtn = document.querySelector(".btn-retur");
        const rejectBox = document.getElementById("rejectBox");
        const alasan = document.getElementById("alasanReject");

        if (el.classList.contains("active")) {
            el.classList.remove("active");

            document.getElementById("approvalInput").value = "";

            submitBtn.classList.remove("show");
            rejectBox.style.display = "none";
            alasan.value = "";

            return;
        }

        buttons.forEach(btn => btn.classList.remove("active"));

        el.classList.add("active");
        document.getElementById("approvalInput").value = value;

        submitBtn.classList.add("show");

        if (value === "reject") {
            rejectBox.style.display = "block";

            setTimeout(() => {
                const textarea = document.getElementById("alasanReject");
                if (textarea) autoResize(textarea);
            }, 100);

        } else {
            rejectBox.style.display = "none";
            alasan.value = "";
        }
    }
    document.addEventListener("DOMContentLoaded", function() {

        const form = document.querySelector("form");
        const mode = form.getAttribute("data-mode");

        if (mode === "view") {

            const inputs = form.querySelectorAll("input, textarea, select, button");

            inputs.forEach(el => {
                if (el.type !== "hidden") {
                    el.readOnly = true;
                }
            });

            document.querySelectorAll(".btn-approval").forEach(btn => {
                btn.style.pointerEvents = "none";
            });

            const submitBtn = document.querySelector(".btn-retur");
            if (submitBtn) submitBtn.style.display = "none";
        }

    });
    document.querySelectorAll('.auto-height').forEach(el => {
        el.style.height = 'auto';
        el.style.height = el.scrollHeight + 'px';
    });
    document.addEventListener("DOMContentLoaded", function() {

        const textarea = document.getElementById("alasanReject");

        if (textarea) {
            autoResize(textarea);

            textarea.addEventListener("input", function() {
                autoResize(this);
            });
        }

    });

    function triggerUpload(box) {
        const input = box.querySelector("input[type='file']");
        input.click();
    }

    document.querySelectorAll(".upload-container input[type='file']").forEach(input => {

        input.addEventListener("change", function() {

            const file = this.files[0];
            if (!file) return;

            const container = this.closest(".upload-container");
            const wrapper = container.querySelector(".preview-wrapper");

            wrapper.innerHTML = "";

            const img = document.createElement("img");
            img.src = URL.createObjectURL(file);
            img.style.width = "100%";
            img.style.borderRadius = "12px";
            img.style.cursor = "pointer";

            img.onclick = function() {
                const modal = document.getElementById("imageModal");
                const modalImg = document.getElementById("modalImg");

                modal.style.display = "flex";
                modalImg.src = this.src;
            };
            const remove = document.createElement("span");
            remove.innerHTML = "×";
            remove.classList.add("remove-btn");
            remove.style.display = "block";

            remove.onclick = () => {
                wrapper.innerHTML = "";
                input.value = "";
            };

            wrapper.appendChild(img);
            wrapper.appendChild(remove);
        });

    });
    const modal = document.getElementById("imageModal");
    const modalImg = document.getElementById("modalImg");
    const closeBtn = document.querySelector(".close");

    closeBtn.addEventListener("click", closeModalAnimated);

    modal.addEventListener("click", function(e) {
        if (e.target === modal) {
            closeModalAnimated();
        }
    });

    function closeModalAnimated() {
        modal.classList.add("hide");

        setTimeout(() => {
            modal.style.display = "none";
            modal.classList.remove("hide");
            modalImg.src = "";
        }, 250);
    }
</script>