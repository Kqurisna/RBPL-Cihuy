<!doctype html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>

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
      position: relative;
    }

    /* background */
    .bg-wrapper {
      position: fixed;
      inset: 0;
      overflow: hidden;
      z-index: 0;
    }

    .login-card {
      position: relative;
      z-index: 2;
    }

    .circle {
      position: absolute;
      border-radius: 50%;
      z-index: 1;
    }

    .c1 {
      width: 90px;
      height: 90px;
      background: #5bb7c5;
      top: 40px;
      left: 40px;
    }

    .c2 {
      width: 100px;
      height: 100px;
      background: #1dd1e5;
      top: -35px;
      right: -44px;
    }

    .c3 {
      width: 50px;
      height: 50px;
      background: #8fd3dd;
      bottom: 120px;
      right: 60px;
    }

    .c4 {
      width: 110px;
      height: 110px;
      background: #48b5c1;
      left: 33%;
      transform: translateX(-50%);
      bottom: -70px;
    }

    .c5 {
      width: 33px;
      height: 33px;
      background: #63c9d5;
      top: 91%;
      left: 44%;
    }

    .c6 {
      width: 24px;
      height: 24px;
      background: #5cafb8;
      top: 95%;
      left: 53%;
    }

    .c7 {
      width: 60px;
      height: 60px;
      background: #8ddee7;
      top: 85%;
      left: 60%;
    }

    .c8 {
      width: 60px;
      height: 60px;
      background: #99e4ec;
      top: 85%;
      left: 70%;
    }

    .c9 {
      width: 60px;
      height: 60px;
      background: #beebf0;
      top: 85%;
      left: 80%;
    }

    /* login card */

    .login-card {
      margin-top: 117px;
      margin-bottom: 70px;
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

    .login-card h2 {
      text-align: center;
      font-size: 22px;
      font-weight: 600;
      color: #2c3e50;
      margin-bottom: 30px;
    }

    .input-group {
      margin-bottom: 20px;
    }

    .input-group label {
      font-size: 13px;
      font-weight: 500;
      color: #5f6f81;
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
      outline: none;
      font-size: 15px;
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
      border: none;
      border-radius: 26px;
      background: linear-gradient(90deg, #5bb7c5, #3f7aa3);
      color: white;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
    }

    .divider {
      text-align: center;
      margin: 28px 0;
      font-size: 13px;
      color: #8fa1b3;
    }
  </style>
</head>

<body>

  <div class="bg-wrapper">
    <div class="circle c1"></div>
    <div class="circle c2"></div>
    <div class="circle c3"></div>
    <div class="circle c4"></div>
    <div class="circle c5"></div>
    <div class="circle c6"></div>
    <div class="circle c7"></div>
    <div class="circle c8"></div>
    <div class="circle c9"></div>
  </div>

  <div class="login-card">

    <div class="avatar">
      <img src="asset/Icon_User.png">
    </div>

    <h2>Selamat Datang</h2>

    <form action="validasi_akun.php" method="POST">

      <div class="input-group">
        <label><strong>Username</strong></label>

        <div class="input-wrapper">
          <img src="asset/user_acc_login.png" class="icon-left">

          <input
            type="text"
            name="username"
            placeholder="Masukkan Username"
            required>

        </div>
      </div>


      <div class="input-group">
        <label><strong>Password</strong></label>

        <div class="input-wrapper">
          <img src="asset/icon_lock.png" class="icon-left">

          <input
            type="password"
            id="password"
            name="password"
            placeholder="Masukkan Password"
            required>

          <img
            src="asset/icon_eyeclosed.png"
            class="icon-right"
            id="togglePassword">

        </div>
      </div>

      <button type="submit" class="btn-login">Masuk</button>

    </form>

    <div class="divider">— Atau masuk dengan —</div>

  </div>


  <script>
    ///// ambil input password
    const passwordInput = document.getElementById("password");

    ///// ambil icon mata
    const togglePassword = document.getElementById("togglePassword");

    togglePassword.addEventListener("click", function() {

      if (passwordInput.type === "password") {

        passwordInput.type = "text";
        togglePassword.src = "asset/icon_eyeopen.png";

      } else {

        passwordInput.type = "password";
        togglePassword.src = "asset/icon_eyeclosed.png";

      }

    });
  </script>

</body>

</html>