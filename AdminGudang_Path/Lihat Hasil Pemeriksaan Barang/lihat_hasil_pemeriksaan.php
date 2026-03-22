<?php
$koneksi = mysqli_connect("localhost", "root", "", "pt_bumijaya");

$id_nota = isset($_GET['id']) ? intval($_GET['id']) : 0;

$queryNota = mysqli_query($koneksi, "SELECT * FROM nota WHERE id_nota = $id_nota");
$dataNota = mysqli_fetch_assoc($queryNota);

$queryDetail = mysqli_query($koneksi, "SELECT * FROM detail_barang WHERE id_nota = $id_nota");

$validasiList = [];
$queryValidasi = mysqli_query($koneksi, "SELECT * FROM validasi_kasir WHERE id_nota = $id_nota ORDER BY id_validasi ASC");

while ($v = mysqli_fetch_assoc($queryValidasi)) {
    $validasiList[] = $v;
} ?>
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
            background: #e9edf2;
            padding: 10px 15px;
            font-size: 12px;
            font-weight: 500;
            outline: none;
            resize: none;
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
        }

        .close {
            position: absolute;
            top: 20px;
            right: 35px;
            color: white;
            font-size: 35px;
            cursor: pointer;
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
                <a href="../Lihat Hasil Pemeriksaan Barang/lihat_list_nota.php" class="back-link">
                    <img src="../../UI_GENERAL/logo_back.png" alt="Back">
                </a>
            </div>

            <h2>Pengecekkan Barang Fisik</h2>
        </div>

        <div class="header-circle-big"></div>
        <div class="header-circle-small"></div>
        <div class="header-circle-small_2"></div>
        <div class="header-circle-small_3"></div>

    </div>

    <form action="proses_input_kasir.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_nota" value="<?= $id_nota ?>">
        <div class="container">

            <h3 class="section-title">Pengecekkan Barang Fisik</h3>
            <div class="form-card">
                <div class="form-group">
                    <label>Nomer Nota</label>
                    <input type="text" name="nomer_nota" value="<?= $dataNota['nomor_nota'] ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Tanggal Nota</label>
                    <input type="date" name="tanggal_nota" value="<?= $dataNota['tanggal_nota'] ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Nama Supplier</label>
                    <input type="text" name="supplier" value="<?= $dataNota['supplier'] ?>" readonly>

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
                                            <input type="text" name="barang[]" value="<?= $detail['nama_barang'] ?>" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label>Jumlah barang ke-<?= $no ?></label>
                                            <input type="number" name="jumlah[]" value="<?= $detail['jumlah_barang'] ?>" readonly>
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
                                            <div class="keluhan-box" style="display:block;">

                                                <div class="form-group">
                                                    <label>Keterangan / Keluhan</label>
                                                    <textarea class="textarea" readonly><?= $validasi['keterangan'] ?></textarea>
                                                </div>

                                                <?php if ($validasi['foto_bukti']) { ?>
                                                    <div class="form-group">
                                                        <label>Foto Bukti</label>
                                                        <div class="img-preview" onclick="openModal(this)">
                                                            <img src="../../Kasir_Path/Pengecekkan%20Barang%20FIsik/uploads/bukti/<?= $validasi['foto_bukti'] ?>">
                                                        </div>
                                                    </div>
                                                <?php } ?>

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
            </div>
    </form>
</body>

</html>
<script>
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
    document.querySelectorAll("input[type='file']").forEach(input => {
        input.addEventListener("change", function(e) {

            const file = e.target.files[0];
            const id = this.id.replace("fileInput", "");
            const preview = document.getElementById("previewImage" + id);

            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.style.display = "block";
            }
        });
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
    document.querySelector("form").addEventListener("submit", function(e) {

        let statusInputs = document.querySelectorAll("[id^='statusInput']");
        let belumPilih = false;

        statusInputs.forEach(input => {
            if (input.value === "") {
                belumPilih = true;
            }
        });

        if (belumPilih) {
            alert("Semua kondisi barang harus dipilih!");
            e.preventDefault();
            return;
        }

    });

    function openModal(el) {
        const img = el.querySelector("img");
        const modal = document.getElementById("imageModal");
        const modalImg = document.getElementById("modalImg");

        modal.style.display = "block";
        modalImg.src = img.src;
    }

    function closeModal() {
        document.getElementById("imageModal").style.display = "none";
    }
</script>