<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pemilihan Kepala Desa Barurejo</title>

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
            background: #f3f7f5;
            color: #203029;
        }

        .voting-header {
            background: linear-gradient(135deg, #0f5137, #198754);
            color: white;
            padding: 24px;
        }

        .official-logo {
            width: 72px;
            height: 72px;
            object-fit: contain;
            background: white;
            border-radius: 16px;
            padding: 6px;
        }

        .candidate-card {
            border: 3px solid transparent;
            border-radius: 22px;
            cursor: pointer;
            transition: 0.2s ease;
            overflow: hidden;
        }

        .candidate-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 35px rgba(0, 0, 0, 0.10);
        }

        .candidate-radio:checked + .candidate-card {
            border-color: #198754;
            box-shadow: 0 0 0 5px rgba(25, 135, 84, 0.14);
        }

        .candidate-photo {
            width: 190px;
            height: 190px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid #e7eee9;
        }

        .candidate-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background: #198754;
            color: white;
            font-size: 24px;
            font-weight: 800;
        }

        .candidate-radio:checked + .candidate-card {
            border: 4px solid #198754;
            box-shadow: 0 0 0 6px rgba(25, 135, 84, 0.15);
            transform: translateY(-4px);
        }

        .candidate-radio:checked + .candidate-card .select-button {
            background: #198754;
            color: white;
            border-color: #198754;
        }

        .candidate-radio:checked + .candidate-card .select-button::before {
            content: "✓ ";
        }

        .submit-choice {
            opacity: 0.55;
            pointer-events: none;
            transition: 0.2s;
        }

        .submit-choice.active {
            opacity: 1;
            pointer-events: auto;
        }
    </style>
</head>

<body>

<header class="voting-header">
    <div class="container">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-3 text-center">

            <img
                src="{{ asset('images/logos/logo-bwi.png') }}"
                alt="Logo Kabupaten Banyuwangi"
                class="official-logo"
            >

            <div>
                <div class="small text-white-50">
                    PEMUNGUTAN SUARA ELEKTRONIK
                </div>

                <h1 class="fw-bold mb-1">
                    Desa Barurejo
                </h1>

                <div>
                    Kecamatan Siliragung · Kabupaten Banyuwangi
                </div>
            </div>

        </div>
    </div>
</header>

<main class="container py-5">

    <div class="text-center mb-5">
        <h2 class="fw-bold">Silakan Pilih Kandidat</h2>

        <p class="text-secondary mb-1">
            Pilihan yang sudah dikirim tidak dapat diubah.
        </p>

        <small class="text-secondary">
            Pemilih terverifikasi: {{ $voter->name }}
        </small>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger text-center">
            {{ $errors->first() }}
        </div>
    @endif

    <form
        action="{{ route('voting.store') }}"
        method="POST"
        onsubmit="return confirm('Apakah Anda yakin dengan pilihan ini? Pilihan tidak dapat diubah.')"
    >
        @csrf

        <div class="row g-4 justify-content-center">

            @forelse ($candidates as $candidate)
                <div class="col-md-6 col-xl-4">

                    <input
                        type="radio"
                        name="candidate_id"
                        value="{{ $candidate->id }}"
                        id="candidate-{{ $candidate->id }}"
                        class="candidate-radio d-none"
                        required
                    >

                    <label
                        for="candidate-{{ $candidate->id }}"
                        class="candidate-card card border-0 shadow-sm h-100"
                    >
                        <div class="card-body text-center p-4">

                            @if ($candidate->photo)
                                <img
                                    src="{{ asset('storage/' . $candidate->photo) }}"
                                    alt="Foto {{ $candidate->name }}"
                                    class="candidate-photo mb-4"
                                >
                            @else
                                <div
                                    class="candidate-photo bg-secondary-subtle d-flex align-items-center justify-content-center mx-auto mb-4"
                                >
                                    Belum ada foto
                                </div>
                            @endif

                            <div class="candidate-number mb-3">
                                {{ $candidate->number }}
                            </div>

                            <h3 class="fw-bold mb-3">
                                {{ $candidate->name }}
                            </h3>

                            <p class="text-secondary">
                                {{ $candidate->vision ?: 'Visi belum ditambahkan.' }}
                            </p>

                            <div class="btn btn-outline-success w-100 mt-2 select-button">
                                Pilih Kandidat Ini
                            </div>

                        </div>
                    </label>

                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        Belum ada kandidat yang tersedia.
                    </div>
                </div>
            @endforelse

        </div>

        @if ($candidates->isNotEmpty())
            <div class="text-center mt-5">
                <button
                    type="submit"
                    id="submitChoice"
                    class="btn btn-success btn-lg px-5 py-3 fw-bold submit-choice"
                >
                    <i class="bi bi-send-check me-2"></i>
                    Kirim Pilihan
                </button>
            </div>
        @endif

    </form>

</main>

<script>
    const radios = document.querySelectorAll('.candidate-radio');
    const submitButton = document.getElementById('submitChoice');

    radios.forEach((radio) => {
        radio.addEventListener('change', () => {
            submitButton.classList.add('active');
            submitButton.innerHTML =
                '<i class="bi bi-send-check me-2"></i>Kirim Pilihan Terpilih';
        });
    });
</script>

</body>
</html>