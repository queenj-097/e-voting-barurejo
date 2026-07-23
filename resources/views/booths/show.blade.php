<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $booth->name }}</title>

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
            background: linear-gradient(135deg, #0f5137, #198754);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }

        .booth-card {
            width: min(92%, 720px);
            border-radius: 28px;
            border: 0;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
        }

        .booth-icon {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            background: #d1e7dd;
            color: #198754;
            font-size: 50px;
        }
    </style>

    <meta http-equiv="refresh" content="2">
</head>

<body>

<div class="card booth-card">
    <div class="card-body text-center p-4 p-md-5">

        <img
            src="{{ asset('images/logos/logo-bwi.png') }}"
            alt="Logo Kabupaten Banyuwangi"
            width="78"
            class="mb-3"
        >

        <h1 class="fw-bold mb-1">
            {{ $booth->name }}
        </h1>

        <p class="text-secondary mb-4">
            E-Voting Desa Barurejo
        </p>

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($booth->status === 'assigned' && $booth->currentVoter)

            <div class="booth-icon">
                <i class="bi bi-person-check-fill"></i>
            </div>

            <h2 class="fw-bold">
                Pemilih Siap
            </h2>

            <p class="text-secondary">
                Pemilih telah dikirim oleh petugas.
            </p>

            <div class="border rounded-4 p-4 my-4 text-start">
                <div class="mb-3">
                    <small class="text-secondary">Nama Pemilih</small>
                    <div class="fw-bold fs-5">
                        {{ $booth->currentVoter->name }}
                    </div>
                </div>

                <div>
                    <small class="text-secondary">Nomor DPT</small>
                    <div class="fw-semibold">
                        {{ $booth->currentVoter->dpt_number }}
                    </div>
                </div>
            </div>

            <form
                action="{{ route('booths.start', $booth) }}"
                method="POST"
            >
                @csrf

                <button
                    type="submit"
                    class="btn btn-success btn-lg w-100 py-3 fw-bold"
                >
                    <i class="bi bi-play-circle-fill me-2"></i>
                    Mulai Memilih
                </button>
            </form>

        @elseif ($booth->status === 'voting')

            <div class="booth-icon">
                <i class="bi bi-hourglass-split"></i>
            </div>

            <h2 class="fw-bold">
                Sedang Digunakan
            </h2>

            <p class="text-secondary mb-0">
                Proses pemilihan sedang berlangsung.
            </p>

        @elseif ($booth->status === 'offline')

            <div class="booth-icon bg-danger-subtle text-danger">
                <i class="bi bi-x-circle-fill"></i>
            </div>

            <h2 class="fw-bold">
                Bilik Tidak Aktif
            </h2>

            <p class="text-secondary mb-0">
                Hubungi petugas pemilihan.
            </p>

        @else

            <div class="booth-icon">
                <i class="bi bi-hourglass"></i>
            </div>

            <h2 class="fw-bold">
                Menunggu Pemilih
            </h2>

            <p class="text-secondary">
                Bilik siap digunakan.
            </p>

            <div class="spinner-border text-success mt-3" role="status">
                <span class="visually-hidden">Menunggu...</span>
            </div>

        @endif

    </div>
</div>

</body>
</html>