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

        .page-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #1f2937;
        }

        .form-card {
            background: white;
            padding: 20px 20px 35px;
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
            position: relative;
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

    <form action="proses_validasi_input.php" method="POST" enctype="multipart/form-data">

        <div class="container">

            <div class="form-card">

                <h3 class="section-title">Informasi Barang Masuk</h3>

                <div class="form-group">
                    <label>Nomer Nota<span style="color:red">*</span></label>
                    <input type="text" name="nomer_nota" required>
                </div>

                <div class="form-group">
                    <label>Tanggal Nota<span style="color:red">*</span></label>
                    <input type="date" name="tanggal_nota" required>
                </div>

                <div class="form-group">
                    <label>Nama Supplier<span style="color:red">*</span></label>
                    <input type="text" name="supplier" required>
                </div>

                <div class="form-group">
                    <label>Jenis Barang <span style="color:red">*</span></label>

                    <div class="chip-container">
                        <span class="chip">Material Bangunan</span>
                        <span class="chip">Besi & Logam</span>
                        <span class="chip">Listrik</span>
                        <span class="chip">Keramik & Lantai</span>
                        <span class="chip">Alat Pertukangan</span>
                        <span class="chip">Kayu & Olahan</span>
                    </div>

                    <input type="hidden" name="jenis_barang" id="jenisBarangInput">

                    <small id="errorJenis" style="color:red; display:none;">
                        Wajib pilih minimal 1 jenis barang!
                    </small>
                </div>

                <div class="btn-group">
                    <div class="plus_btn" id="tambahBarang">
                        <img src="../../UI_ADMIN/logo_plus.png">
                    </div>

                    <div class="minus_btn" id="hapusBarang">
                        <img src="../../UI_ADMIN/logo_minus.png">
                    </div>
                </div>

                <div class="container_2">
                    <div class="welcome-card_2">

                        <div id="inputBarangContainer">

                            <div class="item">

                                <div class="card-dots_2_1"><span></span></div>
                                <div class="card-dots_2_2"><span></span></div>
                                <div class="card-dots_2_3"><span></span></div>

                                <div class="form-group">
                                    <label>Nama Barang ke-1</label>
                                    <input type="text" name="barang[]" required>
                                </div>

                                <div class="form-group">
                                    <label>Jumlah barang ke-1</label>
                                    <input type="number" name="jumlah[]" class="input-number" required>
                                </div>
                                <div class="success-line"></div>

                            </div>

                        </div>

                    </div>
                </div>

            </div>

        </div>

        <div class="container">

            <div class="form-card">

                <h3 class="section-title">Lampiran Foto Nota</h3>

                <div class="upload-box" onclick="document.getElementById('fileInput').click()">

                    <img src="UI_ADMIN/logo_plus.png" class="upload-icon">

                    <p class="upload-text">Unggah Foto Nota</p>
                    <p class="upload-subtext">(JPG / PNG, maks. 5 MB)</p>
                    <input type="file" id="fileInput" name="foto_nota" accept="image/*" hidden required>
                </div>

                <img id="previewImage"
                    style="display:none; width:100%; margin-top:10px; border-radius:10px;">

            </div>

        </div>

        <div style="padding: 0 30px 30px;">
            <button type="submit" name="submit" class="btn-login">Submit Data Nota</button>
        </div>

    </form>

</body>

</html>
<script>
    const chips = document.querySelectorAll(".chip");
    chips.forEach(chip => {
        chip.addEventListener("click", () => {
            chip.classList.toggle("active");
        });
    });

    let count = 1;

    const container = document.getElementById("inputBarangContainer");
    const btnTambah = document.getElementById("tambahBarang");
    const btnHapus = document.getElementById("hapusBarang");

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

    btnTambah.onclick = function() {

        const div = document.createElement("div");
        div.classList.add("item");

        div.innerHTML = `
            <div class="form-group">
                <label>Nama Barang ke-${count + 1}</label>
                <input type="text" name="barang[]" required>
            </div>

            <div class="form-group">
                <label>Jumlah Barang ke-${count + 1}</label>
                <input type="number" name="jumlah[]" class="input-number" required>
            </div>
        `;

        container.appendChild(div);

        updateLabels();
        updateMinusState();
        updateDivider();
    };

    btnHapus.onclick = function() {

        const items = container.querySelectorAll(".item");

        if (items.length === 1) return;

        const lastItem = items[items.length - 1];

        lastItem.classList.add("fade-out");

        setTimeout(() => {
            container.removeChild(lastItem);

            updateLabels();
            updateMinusState();
            updateDivider();
        }, 250);
    };

    updateLabels();
    updateMinusState();
    updateDivider();

    document.getElementById("fileInput").addEventListener("change", function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById("previewImage");

        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = "block";
        }
    });
    document.addEventListener("keydown", function(e) {
        if (e.target.classList.contains("input-number")) {
            if (e.key === 'e' || e.key === 'E' || e.key === '+' || e.key === '-') {
                e.preventDefault();
            }
        }
    });
    const inputJenis = document.getElementById("jenisBarangInput");
    const errorMsg = document.getElementById("errorJenis");

    let selectedItems = [];

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

            inputJenis.value = selectedItems.join(",");
        });
    });

    document.querySelector("form").addEventListener("submit", function(e) {

        const requiredInputs = document.querySelectorAll("input[required], textarea[required]");
        let firstInvalid = null;

        requiredInputs.forEach(input => {
            if (!input.value.trim()) {
                if (!firstInvalid) {
                    firstInvalid = input;
                }
            }
        });

        if (typeof selectedItems !== "undefined" && selectedItems.length === 0) {
            const chipContainer = document.querySelector(".chip-container");

            e.preventDefault();

            chipContainer.scrollIntoView({
                behavior: "smooth",
                block: "center"
            });

            return;
        }

        if (firstInvalid) {
            e.preventDefault();

            firstInvalid.scrollIntoView({
                behavior: "smooth",
                block: "center"
            });

            firstInvalid.focus();
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

            const chipContainer = document.querySelector(".chip-container");

            chips.forEach(c => c.classList.add("error"));

            if (!firstError) {
                firstError = chipContainer;
            }

        } else {
            errorMsg.style.display = "none";
            chips.forEach(c => c.classList.remove("error"));
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
</script>