<?php
session_start();

include '../config/koneksi.php';

// Kalau udah login, redirect ke dashboard
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header("Location: dashboard.php");
    exit;
}

$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';

$error_messages = [
    '1'      => 'Kode rahasia salah!',
    'empty'  => 'Kode rahasia dan password baru wajib diisi!',
    'weak'   => 'Password minimal 8 karakter!',
    'match'  => 'Password dan konfirmasi tidak cocok!',
    'server' => 'Terjadi kesalahan pada server. Coba lagi.',
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password | Admin Panel</title>

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

        .error-box {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            padding: 12px 16px;
            border-radius: 9px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            line-height: 1.4;
        }

        .error-box svg {
            flex-shrink: 0;
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .success-box {
            background: #d1fae5;
            border: 1px solid #a7f3d0;
            color: #065f46;
            padding: 12px 16px;
            border-radius: 9px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            line-height: 1.4;
        }

        .success-box svg {
            flex-shrink: 0;
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #263b55;
            margin-bottom: 8px;
        }

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

        .back-link {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
        }

        .back-link a {
            color: #234d82;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        .form-note {
            text-align: center;
            margin-top: 25px;
            font-size: 12px;
            color: #9aa6b2;
        }

        @media (max-width: 750px) {
            .login-wrapper {
                grid-template-columns: 1fr;
                width: 450px;
                min-height: auto;
            }
            .login-brand {
                padding: 35px;
                min-height: 220px;
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

    <div class="login-brand">
        <div class="brand-content">
            <div class="logo">S</div>
            <h1>Reset Password</h1>
            <div class="brand-line"></div>
            <p>
                Masukkan kode rahasia untuk mereset password admin Anda.
                Pastikan kode dirahasiakan.
            </p>
            <div class="brand-footer">
                Sistem Informasi Sekolah
            </div>
        </div>
    </div>

    <div class="login-form-section">

        <div class="form-header">
            <h2>Lupa Password? 🔑</h2>
            <p>Masukkan kode rahasia dan password baru Anda.</p>
        </div>

        <?php if ($error && isset($error_messages[$error])): ?>
            <div class="error-box">
                <svg viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span><?= htmlspecialchars($error_messages[$error]) ?></span>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success-box">
                <svg viewBox="0 0 24 24">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span>Password berhasil direset! Silakan login dengan password baru.</span>
            </div>
        <?php endif; ?>

        <form action="proses_lupa_password.php" method="POST">

            <div class="form-group">
                <label for="reset_code">Kode Rahasia</label>
                <div class="input-wrapper">
                    <span class="input-icon">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </span>
                    <input type="text" id="reset_code" name="reset_code" class="form-control"
                        placeholder="Masukkan kode rahasia" required autocomplete="off">
                </div>
            </div>

            <div class="form-group">
                <label for="new_password">Password Baru</label>
                <div class="input-wrapper">
                    <span class="input-icon">
                        <svg viewBox="0 0 24 24">
                            <rect x="5" y="10" width="14" height="10" rx="2"></rect>
                            <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
                        </svg>
                    </span>
                    <input type="password" id="new_password" name="new_password" class="form-control"
                        placeholder="Minimal 8 karakter" required minlength="8">
                    <button type="button" class="toggle-password" onclick="togglePw('new_password', this)">
                        <svg viewBox="0 0 24 24">
                            <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password Baru</label>
                <div class="input-wrapper">
                    <span class="input-icon">
                        <svg viewBox="0 0 24 24">
                            <rect x="5" y="10" width="14" height="10" rx="2"></rect>
                            <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
                        </svg>
                    </span>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                        placeholder="Ulangi password baru" required minlength="8">
                    <button type="button" class="toggle-password" onclick="togglePw('confirm_password', this)">
                        <svg viewBox="0 0 24 24">
                            <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="login-button">Reset Password</button>
        </form>

        <div class="back-link">
            <a href="login.php">← Kembali ke Login</a>
        </div>

        <div class="form-note">
            © 2026 SMP Muhammadiyah 6 Krian. All rights reserved.
        </div>
    </div>
</div>

<script>
    function togglePw(inputId, btn) {
        const input = document.getElementById(inputId);
        const svg = btn.querySelector('svg');

        if (input.type === 'password') {
            input.type = 'text';
            svg.innerHTML = `
                <path d="M3 3l18 18"></path>
                <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"></path>
                <path d="M9.9 5.2A10.7 10.7 0 0 1 12 5c6.5 0 10 7 10 7a18.3 18.3 0 0 1-3.1 3.8"></path>
                <path d="M6.1 6.1C3.4 8.1 2 12 2 12s3.5 7 10 7a10.5 10.5 0 0 0 3.2-.5"></path>
            `;
        } else {
            input.type = 'password';
            svg.innerHTML = `
                <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            `;
        }
    }
</script>

</body>
</html>