<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Pemilihan Barurejo</title>

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
            position: relative;
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
            transform: translateY(-4px);
        }

        .candidate-photo {
            width: 190px;
            height: 190px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid #e7eee9;
        }

        .candidate-number {
            position: absolute;
            top: 18px;
            right: 18px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-width: 82px;
            min-height: 82px;
            padding: 10px;
            border-radius: 18px;
            background: #198754;
            color: white;
            box-shadow: 0 8px 22px rgba(25, 135, 84, 0.28);
        }

        .candidate-number small {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.6px;
            text-transform: uppercase;
        }

        .candidate-number strong {
            font-size: 32px;
            line-height: 1;
        }

        .candidate-info {
            text-align: left;
        }

        .candidate-info-title {
            font-size: 13px;
            font-weight: 800;
            color: #198754;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .candidate-info-content {
            color: #5e6d65;
            white-space: pre-line;
            margin-bottom: 0;
        }

        .mission-box {
            max-height: 145px;
            overflow-y: auto;
            padding-right: 6px;
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

        /*
         * Konfirmasi pilihan satu layar.
         */
        .confirmation-modal .modal-content {
            min-height: 100vh;
            border: 0;
            border-radius: 0;
            background: #f3f7f5;
        }

        .confirmation-header {
            background: linear-gradient(135deg, #0f5137, #198754);
            color: white;
            padding: 22px;
        }

        .confirmation-body {
            display: flex;
            flex: 1;
            padding: 0;
        }

        .confirmation-card {
            width: 100%;
            height: 100%;
            max-width: none;
            background: white;
            border-radius: 0;
            padding: 40px 60px;
            box-shadow: none;
            text-align: center;

            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .confirmation-icon {
            width: 92px;
            height: 92px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: #fff3cd;
            color: #856404;
            font-size: 42px;
        }

        .confirmation-photo {
            width: 320px;
            height: 320px;
            object-fit: cover;
            border-radius: 28px;
            border: 8px solid #e7eee9;
            margin-bottom: 24px;
        }   

        .confirmation-photo-placeholder {
            width: 320px;
            height: 320px;
        }

        .confirmation-number {
            font-size: 90px;
            font-weight: 800;
        }

        .confirmation-number {
            font-size: 64px;
            font-weight: 800;
            line-height: 1;
            color: #198754;
            margin-bottom: 14px;
        }

        .confirmation-name {
            font-size: 48px;
            font-weight: 800;
        }

        .confirmation-warning {
            max-width: 620px;
            margin: 0 auto 28px;
            font-size: 20px;
            line-height: 1.5;
        }

        .confirmation-actions {
            display: grid;
            grid-template-columns: 1fr 1.4fr;
            gap: 16px;
        }

        .confirmation-actions .btn {
            min-height: 90px;
            font-size: 28px;
            font-weight: 700;
        }

        @media (max-width: 576px) {
            .candidate-photo {
                width: 160px;
                height: 160px;
            }

            .candidate-number {
                min-width: 72px;
                min-height: 72px;
            }

            .candidate-number strong {
                font-size: 28px;
            }

            .confirmation-card {
                padding: 24px 18px;
                border-radius: 22px;
            }

            .confirmation-photo,
            .confirmation-photo-placeholder {
                width: 190px;
                height: 190px;
            }

            .confirmation-number {
                font-size: 54px;
            }

            .confirmation-name {
                font-size: 27px;
            }

            .confirmation-warning {
                font-size: 18px;
            }

            .confirmation-actions {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<header class="voting-header">
    <div class="container">

        <div
            class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-3 text-center"
        >
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

        <h2 class="fw-bold">
            Silakan Pilih Kandidat
        </h2>

        <p class="text-secondary mb-1">
            Tekan salah satu kandidat, lalu periksa kembali pilihan Anda.
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
        id="votingForm"
        action="{{ route('voting.store') }}"
        method="POST"
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
                        data-number="{{ $candidate->number }}"
                        data-name="{{ $candidate->name }}"
                        data-photo="{{ $candidate->photo
                            ? asset('storage/' . $candidate->photo)
                            : '' }}"
                        required
                    >

                    <label
                        for="candidate-{{ $candidate->id }}"
                        class="candidate-card card border-0 shadow-sm h-100"
                    >
                        <div class="card-body p-4">

                            <div class="candidate-number">
                                <small>Nomor</small>

                                <strong>
                                    {{ $candidate->number }}
                                </strong>
                            </div>

                            <div class="text-center">

                                @if ($candidate->photo)
                                    <img
                                        src="{{ asset('storage/' . $candidate->photo) }}"
                                        alt="Foto {{ $candidate->name }}"
                                        class="candidate-photo mb-4"
                                    >
                                @else
                                    <div
                                        class="candidate-photo bg-secondary-subtle d-flex align-items-center justify-content-center mx-auto mb-4 text-secondary"
                                    >
                                        Belum ada foto
                                    </div>
                                @endif

                                <h3 class="fw-bold mb-4">
                                    {{ $candidate->name }}
                                </h3>

                            </div>

                            <div
                                class="btn btn-outline-success w-100 select-button"
                            >
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
                    type="button"
                    id="openConfirmationButton"
                    class="btn btn-success btn-lg px-5 py-3 fw-bold submit-choice"
                >
                    <i class="bi bi-check-circle me-2"></i>
                    Pilih Kandidat Terlebih Dahulu
                </button>

            </div>

        @endif

    </form>

</main>

<div
    class="modal fade confirmation-modal"
    id="confirmationModal"
    tabindex="-1"
    aria-labelledby="confirmationModalLabel"
    aria-hidden="true"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
>
    <div class="modal-dialog modal-fullscreen">

        <div class="modal-content">

            <div class="confirmation-header">

                <div class="container text-center">

                    <div class="small text-white-50 mb-1">
                        KONFIRMASI PILIHAN
                    </div>

                    <h2
                        class="fw-bold mb-0"
                        id="confirmationModalLabel"
                    >
                        Periksa Pilihan Anda
                    </h2>

                </div>

            </div>

            <div class="confirmation-body">

                <div class="confirmation-card">

                    <div class="confirmation-icon">
                        <i class="bi bi-question-lg"></i>
                    </div>

                    <h2 class="fw-bold mb-2">
                        Apakah pilihan ini sudah benar?
                    </h2>

                    <p class="text-secondary fs-5 mb-4">
                        Pastikan nomor dan nama kandidat sesuai dengan pilihan Anda.
                    </p>

                    <div id="confirmationPhotoContainer">

                        <img
                            id="confirmationPhoto"
                            src=""
                            alt="Foto kandidat terpilih"
                            class="confirmation-photo d-none"
                        >

                        <div
                            id="confirmationPhotoPlaceholder"
                            class="confirmation-photo-placeholder"
                        >
                            Belum ada foto
                        </div>

                    </div>

                    <div class="confirmation-number-label">
                        Nomor Urut
                    </div>

                    <div
                        id="confirmationNumber"
                        class="confirmation-number"
                    >
                        -
                    </div>

                    <div
                        id="confirmationName"
                        class="confirmation-name"
                    >
                        -
                    </div>

                    <div class="alert alert-warning confirmation-warning">

                        <i class="bi bi-exclamation-triangle-fill me-2"></i>

                        Setelah tombol
                        <strong>Ya, Pilih Kandidat Ini</strong>
                        ditekan, pilihan tidak dapat diubah.

                    </div>

                    <div class="confirmation-actions">

                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            data-bs-dismiss="modal"
                        >
                            <i class="bi bi-arrow-left me-2"></i>
                            Kembali
                        </button>

                        <button
                            type="button"
                            id="confirmVoteButton"
                            class="btn btn-success"
                        >
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Ya, Pilih Kandidat Ini
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const votingForm =
            document.getElementById('votingForm');

        const radios =
            document.querySelectorAll('.candidate-radio');

        const openConfirmationButton =
            document.getElementById('openConfirmationButton');

        const confirmVoteButton =
            document.getElementById('confirmVoteButton');

        const confirmationModalElement =
            document.getElementById('confirmationModal');

        const confirmationNumber =
            document.getElementById('confirmationNumber');

        const confirmationName =
            document.getElementById('confirmationName');

        const confirmationPhoto =
            document.getElementById('confirmationPhoto');

        const confirmationPhotoPlaceholder =
            document.getElementById(
                'confirmationPhotoPlaceholder'
            );

        const confirmationModal =
            new bootstrap.Modal(confirmationModalElement);

        let selectedCandidate = null;
        let isSubmitting = false;

        radios.forEach(function (radio) {
            radio.addEventListener('change', function () {
                selectedCandidate = this;

                openConfirmationButton.classList.add('active');

                openConfirmationButton.innerHTML =
                    '<i class="bi bi-check-circle me-2"></i>' +
                    'Periksa Pilihan';
            });
        });

        openConfirmationButton.addEventListener(
            'click',
            function () {
                if (!selectedCandidate) {
                    return;
                }

                confirmationNumber.textContent =
                    selectedCandidate.dataset.number;

                confirmationName.textContent =
                    selectedCandidate.dataset.name;

                const photoUrl =
                    selectedCandidate.dataset.photo;

                if (photoUrl) {
                    confirmationPhoto.src = photoUrl;

                    confirmationPhoto.classList.remove('d-none');

                    confirmationPhotoPlaceholder.classList.add(
                        'd-none'
                    );
                } else {
                    confirmationPhoto.src = '';

                    confirmationPhoto.classList.add('d-none');

                    confirmationPhotoPlaceholder.classList.remove(
                        'd-none'
                    );
                }

                confirmationModal.show();
            }
        );

        confirmVoteButton.addEventListener(
            'click',
            function () {
                if (!selectedCandidate || isSubmitting) {
                    return;
                }

                isSubmitting = true;

                confirmVoteButton.disabled = true;

                confirmVoteButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span>' +
                    'Menyimpan Pilihan...';

                votingForm.submit();
            }
        );
    });
</script>

</body>
</html>