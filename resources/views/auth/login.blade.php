<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>

    <!-- QUAN TRỌNG cho mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .tms-login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
            padding: 16px;
        }

        .tms-login-card {
            width: 100%;
            max-width: 400px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .tms-login-header {
            background: linear-gradient(90deg, #2b6cb0, #3b82f6);
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 20px;
            font-weight: 600;
        }

        .tms-input {
            border-radius: 10px;
            padding: 12px;
            font-size: 16px;
        }

        .tms-btn-primary {
            background: linear-gradient(90deg, #2b6cb0, #3b82f6);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 500;
            color: #fff;
            font-size: 16px;
        }

        .tms-btn-primary:hover {
            opacity: 0.9;
        }

        .tms-footer {
            font-size: 13px;
            text-align: center;
            margin-top: 10px;
        }

        /* ===== MOBILE ===== */
        @media (max-width: 576px) {
            .tms-login-page {
                padding: 16px;
            }

            .tms-login-card {
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
                /* nhẹ hơn desktop */
                border: 1px solid rgba(0, 0, 0, 0.05);
                /* thêm viền cho rõ */
            }

            .tms-login-header {
                font-size: 18px;
                padding: 16px;
            }

            .mb-tms-login-card-body {
                padding: 20px !important;
            }
        }
    </style>
</head>

<body class="tms-login-page">

    <div class="card tms-login-card">
        <div class="tms-login-header">
            🚚 Quản lý vận tải
        </div>

        <div class="mb-tms-login-card-body p-4">
            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="tel" name="phone" class="form-control tms-input"
                        placeholder="Nhập số điện thoại">
                </div>

                <div class="mb-3">
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" name="password" class="form-control tms-input" placeholder="Nhập mật khẩu">
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
                </div>

                <button type="submit" class="tms-btn-primary w-100">
                    Đăng nhập
                </button>
            </form>

            <div class="tms-footer">
                Quên mật khẩu? <a href="#">Khôi phục mật khẩu</a>
            </div>
        </div>
    </div>

    @if (session('error'))
        <div class="alert alert-danger position-fixed top-0 end-0 m-3" style="z-index: 1050;">
            {{ session('error') }}
        </div>
    @endif
</body>

</html>
