<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login — E-Voting</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>
        body {
            min-height: 100vh;
            margin: 0;
            background:
                linear-gradient(
                    135deg,
                    rgba(9, 84, 58, .96),
                    rgba(25, 135, 84, .88)
                );
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, Helvetica, sans-serif;
        }

        .login-wrapper {
            width: min(92%, 440px);
        }

        .login-card {
            border: 0;
            border-radius: 24px;
            box-shadow: 0 24px 70px rgba(0, 0, 0, .25);
        }

        .logo-box {
            width: 86px;
            height: 86px;
            margin: 0 auto 18px;
            border-radius: 22px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .12);
        }

        .logo-box img {
            width: 58px;
            height: 58px;
            object-fit: contain;
        }
    </style>
</head>

<body>

<div class="login-wrapper">

    <div class="card login-card">
        <div class="card-body p-4 p-md-5">

            <div class="text-center mb-4">

                <div class="logo-box">
                    <img
                        src="{{ asset('images/logos/logo-bwi.png') }}"
                        alt="Logo Kabupaten Banyuwangi"
                    >
                </div>

                <h2 class="fw-bold mb-1">
                    Panel E-Voting
                </h2>

                <p class="text-secondary mb-0">
                    Masuk menggunakan akun petugas.
                </p>

            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form
                action="{{ route('login.process') }}"
                method="POST"
            >
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Email
                    </label>

                    <div class="input-group input-group-lg">
                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="form-control"
                            placeholder="admin@barurejo.id"
                            autocomplete="email"
                            autofocus
                            required
                        >
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Password
                    </label>

                    <div class="input-group input-group-lg">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>

                        <input
                            type="password"
                            name="password"
                            id="passwordInput"
                            class="form-control"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            id="togglePassword"
                        >
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-check mb-4">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="remember"
                        value="1"
                        id="remember"
                    >

                    <label
                        class="form-check-label"
                        for="remember"
                    >
                        Ingat saya
                    </label>
                </div>

                <button
                    type="submit"
                    class="btn btn-success btn-lg w-100 fw-semibold"
                >
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Masuk
                </button>

            </form>

        </div>
    </div>

    <div class="text-center text-white-50 small mt-4">
        E-Voting Desa Barurejo<br>
        KKN-BBK Universitas Airlangga 2026
    </div>

</div>

<script>
    const passwordInput = document.getElementById('passwordInput');
    const togglePassword = document.getElementById('togglePassword');

    togglePassword.addEventListener('click', function () {
        const isHidden = passwordInput.type === 'password';

        passwordInput.type = isHidden ? 'text' : 'password';

        togglePassword.innerHTML = isHidden
            ? '<i class="bi bi-eye-slash"></i>'
            : '<i class="bi bi-eye"></i>';
    });
</script>

</body>
</html>