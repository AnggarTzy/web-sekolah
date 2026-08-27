<?php
session_start();

if (isset($_SESSION['login'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Admin Panel</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            min-height: 100vh;
            background: #f5f7fa;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #17375e;
        }

        .login-wrapper {
            width: 900px;
            max-width: 92%;
            min-height: 520px;
            background: white;
            border-radius: 18px;
            overflow: hidden;
            display: grid;
            grid-template-columns: 42% 58%;
            box-shadow: 0 15px 45px rgba(20, 50, 90, 0.15);
        }

        /* =========================
           LEFT - BRANDING
        ========================== */

        .login-brand {
            background: #234d82;
            color: white;
            padding: 50px 34px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .login-brand::before {
            content: "";
            position: absolute;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            top: -105px;
            left: -105px;
        }

        .login-brand::after {
            content: "";
            position: absolute;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.04);
            bottom: -150px;
            right: -120px;
        }

        .brand-content {
            position: relative;
            z-index: 2;
        }

        .logo {
            width: 70px;
            height: 70px;
            background: #e2bb3e;
            color: #17375e;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 34px;
            font-weight: bold;
            margin-bottom: 27px;
        }

        .login-brand h1 {
            font-size: 30px;
            margin-bottom: 0;
        }

        .brand-line {
            width: 54px;
            height: 4px;
            background: #e2bb3e;
            border-radius: 10px;
            margin: 23px 0;
        }

        .login-brand p {
            color: #e2ebf5;
            line-height: 1.7;
            font-size: 14px;
            max-width: 280px;
        }

        .brand-footer {
            margin-top: 45px;
            font-size: 12px;
            color: #c6d7e9;
        }

        /* =========================
           RIGHT - FORM
        ========================== */

        .login-form-section {
            padding: 55px 65px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            margin-bottom: 30px;
        }

        .form-header h2 {
            font-size: 28px;
            color: #17375e;
            margin-bottom: 8px;
        }

        .form-header p {
            font-size: 14px;
            color: #718096;
        }

        .form-group {
            margin-bottom: 21px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #263b55;
            margin-bottom: 8px;
        }

        /* INPUT */

        .input-wrapper {
            position: relative;
        }

        .form-control {
            width: 100%;
            height: 48px;
            border: 1px solid #d5dde7;
            border-radius: 9px;
            padding: 0 45px 0 45px;
            font-size: 14px;
            color: #26384d;
            outline: none;
            background: #fbfcfe;
            transition: all 0.25s ease;
        }

        .form-control:focus {
            border-color: #234d82;
            background: white;
            box-shadow: 0 0 0 3px rgba(35, 77, 130, 0.10);
        }

        .form-control::placeholder {
            color: #a0acba;
        }

        /* ICON INPUT */

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 19px;
            height: 19px;
            color: #687789;
            pointer-events: none;
        }

        .input-icon svg {
            width: 100%;
            height: 100%;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* PASSWORD EYE */

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 23px;
            height: 23px;
            border: none;
            background: transparent;
            cursor: pointer;
            color: #687789;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-password:hover {
            color: #234d82;
        }

        .toggle-password svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* BUTTON */

        .login-button {
            width: 100%;
            height: 49px;
            border: none;
            border-radius: 9px;
            background: #234d82;
            color: white;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 7px;
            transition: all 0.25s ease;
            box-shadow: 0 5px 13px rgba(35, 77, 130, 0.20);
        }

        .login-button:hover {
            background: #17375e;
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(35, 77, 130, 0.25);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .form-note {
            text-align: center;
            margin-top: 25px;
            font-size: 12px;
            color: #9aa6b2;
        }

        /* =========================
           RESPONSIVE
        ========================== */

        @media (max-width: 750px) {

            .login-wrapper {
                grid-template-columns: 1fr;
                width: 450px;
                min-height: auto;
            }

            .login-brand {
                padding: 35px;
                min-height: 250px;
            }

            .login-brand h1 {
                font-size: 25px;
            }

            .logo {
                width: 58px;
                height: 58px;
                font-size: 28px;
                margin-bottom: 18px;
            }

            .brand-line,
            .brand-footer {
                display: none;
            }

            .login-form-section {
                padding: 40px 35px;
            }
        }

        @media (max-width: 450px) {

            body {
                padding: 20px;
            }

            .login-wrapper {
                max-width: 100%;
                border-radius: 14px;
            }

            .login-brand {
                padding: 30px 25px;
            }

            .login-form-section {
                padding: 35px 25px;
            }
        }
    </style>
</head>

<body>

<div class="login-wrapper">

    <!-- =========================
         BRANDING
    ========================== -->

    <div class="login-brand">

        <div class="brand-content">

            <div class="logo">S</div>

            <h1>Admin Panel</h1>

            <div class="brand-line"></div>

            <p>
                Kelola informasi sekolah dengan mudah,
                cepat, dan terorganisir melalui panel
                administrasi.
            </p>

            <div class="brand-footer">
                Sistem Informasi Sekolah
            </div>

        </div>

    </div>


    <!-- =========================
         LOGIN FORM
    ========================== -->

    <div class="login-form-section">

        <div class="form-header">

            <h2>
                Selamat Datang 👋
            </h2>

            <p>
                Silakan login untuk mengakses panel admin.
            </p>

        </div>


        <form action="proses_login.php" method="POST">

            <!-- USERNAME -->

            <div class="form-group">

                <label for="username">
                    Username
                </label>

                <div class="input-wrapper">

                    <span class="input-icon">

                        <!-- USER ICON -->
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="8" r="4"></circle>
                            <path d="M4 21c0-4 3.5-7 8-7s8 3 8 7"></path>
                        </svg>

                    </span>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="form-control"
                        placeholder="Masukkan username"
                        autocomplete="username"
                        required
                    >

                </div>

            </div>


            <!-- PASSWORD -->

            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <div class="input-wrapper">

                    <span class="input-icon">

                        <!-- LOCK ICON -->
                        <svg viewBox="0 0 24 24">
                            <rect x="5" y="10" width="14" height="10" rx="2"></rect>
                            <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
                        </svg>

                    </span>


                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                    >


                    <!-- TOGGLE PASSWORD -->

                    <button
                        type="button"
                        class="toggle-password"
                        id="togglePassword"
                        aria-label="Tampilkan password"
                    >

                        <!-- EYE ICON -->
                        <svg id="eyeIcon" viewBox="0 0 24 24">

                            <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z"></path>

                            <circle cx="12" cy="12" r="3"></circle>

                        </svg>

                    </button>

                </div>

            </div>


            <!-- LOGIN BUTTON -->

            <button type="submit" class="login-button">
                Login ke Admin Panel
            </button>

        </form>


        <div class="form-note">
            © 2026 Sistem Informasi Sekolah
        </div>

    </div>

</div>


<script>

    const passwordInput = document.getElementById("password");
    const togglePassword = document.getElementById("togglePassword");
    const eyeIcon = document.getElementById("eyeIcon");

    togglePassword.addEventListener("click", function () {

        if (passwordInput.type === "password") {

            passwordInput.type = "text";

            togglePassword.setAttribute(
                "aria-label",
                "Sembunyikan password"
            );

            eyeIcon.innerHTML = `
                <path d="M3 3l18 18"></path>
                <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"></path>
                <path d="M9.9 5.2A10.7 10.7 0 0 1 12 5c6.5 0 10 7 10 7a18.3 18.3 0 0 1-3.1 3.8"></path>
                <path d="M6.1 6.1C3.4 8.1 2 12 2 12s3.5 7 10 7a10.5 10.5 0 0 0 3.2-.5"></path>
            `;

        } else {

            passwordInput.type = "password";

            togglePassword.setAttribute(
                "aria-label",
                "Tampilkan password"
            );

            eyeIcon.innerHTML = `
                <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            `;

        }

    });

</script>

</body>
</html>