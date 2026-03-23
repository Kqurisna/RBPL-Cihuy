<?php
$username = isset($_GET['username']) ? $_GET['username'] : "";
if ($username == "") {
    header("Location: lupa_password.php");
    exit;
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            min-height: 100dvh;
            background: linear-gradient(180deg, #eef3f7, #e6edf3);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            width: 400px;
            background: #fff;
            padding: 45px 35px;
            border-radius: 28px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.08);
        }

        .avatar {
            width: 85px;
            height: 85px;
            margin: 0 auto 25px;
            background: linear-gradient(135deg, #5bb7c5, #3f7aa3);
            border-radius: 22px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .avatar img {
            width: 50px;
        }

        h2 {
            text-align: center;
            font-size: 22px;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            font-size: 13px;
            color: #7b8a9a;
            margin-bottom: 25px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-wrapper {
            position: relative;
            margin-top: 8px;
        }

        .input-wrapper input {
            width: 100%;
            padding: 14px 45px;
            border-radius: 16px;
            border: 1px solid #d9e2ec;
            background: #f7fafc;
        }

        .icon-left {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
        }

        .icon-right {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            cursor: pointer;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            border-radius: 26px;
            border: none;
            background: linear-gradient(90deg, #5bb7c5, #3f7aa3);
            color: white;
            font-weight: 600;
            cursor: pointer;
        }

        .message {
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <div class="login-card">

        <div class="avatar">
            <img src="../asset/icon_lock.png">
        </div>

        <h2>Reset Password</h2>
        <p class="subtitle">Masukkan password baru untuk akun kamu</p>

        <form action="update_password.php" method="POST">

            <input type="hidden" name="username" value="<?= $username ?>">

            <div class="input-group">
                <label>Password Baru</label>
                <div class="input-wrapper">
                    <img src="../asset/icon_lock.png" class="icon-left">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan Password Baru"
                        required>

                    <img
                        src="../asset/icon_eyeclosed.png"
                        class="icon-right"
                        id="togglePassword">
                </div>
            </div>

            <div class="input-group">
                <label>Konfirmasi Password</label>
                <div class="input-wrapper">
                    <img src="../asset/icon_lock.png" class="icon-left">

                    <input
                        type="password"
                        id="confirm"
                        name="confirm"
                        placeholder="Konfirmasi Password"
                        required>

                    <img
                        src="../asset/icon_eyeclosed.png"
                        class="icon-right"
                        id="toggleConfirm">
                </div>
            </div>

            <button type="submit" class="btn-login">Simpan Password</button>

        </form>

        <?php if (isset($_GET['error'])) { ?>
            <p class="message" style="color:red;">
                Password tidak sama!
            </p>
        <?php } ?>

    </div>

</body>

</html>
<script>
    const passwordInput = document.getElementById("password");
    const togglePassword = document.getElementById("togglePassword");

    togglePassword.addEventListener("click", function() {
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            togglePassword.src = "../asset/icon_eyeopen.png";
        } else {
            passwordInput.type = "password";
            togglePassword.src = "../asset/icon_eyeclosed.png";
        }
    });

    const confirmInput = document.getElementById("confirm");
    const toggleConfirm = document.getElementById("toggleConfirm");

    toggleConfirm.addEventListener("click", function() {
        if (confirmInput.type === "password") {
            confirmInput.type = "text";
            toggleConfirm.src = "../asset/icon_eyeopen.png";
        } else {
            confirmInput.type = "password";
            toggleConfirm.src = "../asset/icon_eyeclosed.png";
        }
    });
</script>