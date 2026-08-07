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
        html,
        body {
            min-height: 100%;
        }

        body {
            min-height: 100vh;
            margin: 0;
            background: #f3f7f5;
            color: #203029;
        }

        .voting-header {
            background: linear-gradient(
                135deg,
                #0f5137,
                #198754
            );
            color: white;
            padding: 20px 24px;
        }

        .official-logo {
            width: 68px;
            height: 68px;
            object-fit: contain;
            background: white;
            border-radius: 16px;
            padding: 6px;
        }

        .voting-main {
            padding-top: 32px;
            padding-bottom: 32px;
        }

        .candidate-button {
            display: block;
            width: 100%;
            height: 100%;
            padding: 0;
            border: 0;
            border-radius: 22px;
            background: transparent;
            color: inherit;
            text-align: inherit;
        }

        .candidate-card {
            position: relative;
            height: 100%;
            overflow: hidden;
            border: 3px solid transparent;
            border-radius: 22px;
            background: white;
            cursor: pointer;
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease,
                border-color 0.2s ease;
        }

        .candidate-button:hover .candidate-card,
        .candidate-button:focus-visible .candidate-card {
            transform: translateY(-4px);
            border-color: #198754;
            box-shadow:
                0 0 0 5px rgba(25, 135, 84, 0.12),
                0 14px 35px rgba(0, 0, 0, 0.10);
        }

        .candidate-button:focus {
            outline: none;
        }

        .candidate-number {
            position: absolute;
            top: 18px;
            right: 18px;
            z-index: 2;

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
            box-shadow:
                0 8px 22px rgba(25, 135, 84, 0.28);
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

        .candidate-photo {
            width: 190px;
            height: 190px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid #e7eee9;
        }

        .candidate-photo-placeholder {
            width: 190px;
            height: 190px;
            border-radius: 50%;
            border: 5px solid #e7eee9;
            background: #e9ecef;
            color: #6c757d;
        }

        .candidate-name {
            font-size: 28px;
            font-weight: 800;
            line-height: 1.2;
        }

        .candidate-instruction {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;

            width: 100%;
            min-height: 54px;
            margin-top: 18px;
            padding: 10px 16px;

            border: 2px solid #198754;
            border-radius: 12px;
            color: #146c43;
            font-size: 17px;
            font-weight: 700;
        }

        .candidate-button:hover .candidate-instruction,
        .candidate-button:focus-visible .candidate-instruction {
            background: #198754;
            color: white;
        }

        /*
         * Konfirmasi pilihan fullscreen.
         */
        .confirmation-modal .modal-dialog {
            margin: 0;
        }

        .confirmation-modal .modal-content {
            min-height: 100vh;
            border: 0;
            border-radius: 0;
            background: #f3f7f5;
        }

        .confirmation-header {
            flex: 0 0 auto;
            background: linear-gradient(
                135deg,
                #0f5137,
                #198754
            );
            color: white;
            padding: 18px 24px;
        }

        .confirmation-body {
            display: flex;
            flex: 1;
            min-height: 0;
        }

        .confirmation-card {
            display: flex;
            flex-direction: column;
            justify-content: center;

            width: 100%;
            min-height: 100%;
            padding: 28px 48px;

            background: white;
            text-align: center;
        }

        .confirmation-icon {
            display: flex;
            align-items: center;
            justify-content: center;

            width: 74px;
            height: 74px;
            margin: 0 auto 14px;

            border-radius: 50%;
            background: #fff3cd;
            color: #856404;
            font-size: 34px;
        }

        .confirmation-photo,
        .confirmation-photo-placeholder {
            width: 250px;
            height: 250px;
            margin: 0 auto 14px;
            border-radius: 24px;
            border: 7px solid #e7eee9;
        }

        .confirmation-photo {
            object-fit: cover;
        }

        .confirmation-photo-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e9ecef;
            color: #6c757d;
            font-size: 24px;
        }

        .confirmation-number-label {
            color: #6c757d;
            font-size: 14px;
        }

        .confirmation-number {
            margin-bottom: 10px;
            color: #198754;
            font-size: 56px;
            font-weight: 800;
            line-height: 1;
        }

        .confirmation-name {
            margin-bottom: 18px;
            font-size: 38px;
            font-weight: 800;
            line-height: 1.15;
        }

        .confirmation-warning {
            max-width: 650px;
            margin: 0 auto 20px;
            font-size: 17px;
            line-height: 1.45;
        }

        .confirmation-actions {
            display: grid;
            grid-template-columns: 1fr 1.4fr;
            gap: 14px;
            width: 100%;
        }

        .confirmation-actions .btn {
            min-height: 72px;
            font-size: 23px;
            font-weight: 700;
        }

        .confirmation-actions form {
            display: flex;
            margin: 0;
        }

        .confirmation-actions form .btn {
            width: 100%;
        }

        @media (max-width: 991.98px) {
            .candidate-photo,
            .candidate-photo-placeholder {
                width: 170px;
                height: 170px;
            }

            .candidate-name {
                font-size: 24px;
            }
        }

        @media (max-width: 576px) {
            .voting-header {
                padding: 14px;
            }

            .official-logo {
                width: 56px;
                height: 56px;
            }

            .voting-main {
                padding-top: 22px;
                padding-bottom: 22px;
            }

            .candidate-photo,
            .candidate-photo-placeholder {
                width: 150px;
                height: 150px;
            }

            .candidate-number {
                min-width: 68px;
                min-height: 68px;
            }

            .candidate-number strong {
                font-size: 27px;
            }

            .candidate-name {
                font-size: 22px;
            }

            .confirmation-header {
                padding: 12px;
            }

            .confirmation-card {
                justify-content: flex-start;
                padding: 18px 14px;
                overflow-y: auto;
            }

            .confirmation-icon {
                width: 60px;
                height: 60px;
                margin-bottom: 10px;
                font-size: 28px;
            }

            .confirmation-photo,
            .confirmation-photo-placeholder {
                width: 170px;
                height: 170px;
                margin-bottom: 10px;
            }

            .confirmation-number {
                font-size: 44px;
            }

            .confirmation-name {
                font-size: 25px;
            }

            .confirmation-warning {
                font-size: 15px;
            }

            .confirmation-actions {
                grid-template-columns: 1fr;
            }

            .confirmation-actions .btn {
                min-height: 58px;
                font-size: 18px;
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

<main class="container voting-main">

    <div class="text-center mb-4">

        <h2 class="fw-bold">
            Silakan Pilih Kandidat
        </h2>

        <p class="text-secondary mb-1">
            Tekan foto atau kartu kandidat yang Anda pilih.
        </p>

        <small class="text-secondary">
            Pemilih terverifikasi:
            <strong>{{ $voter->name }}</strong>
        </small>

    </div>

    @if ($errors->any())
        <div class="alert alert-danger text-center">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <div class="row g-4 justify-content-center">

        @forelse ($candidates as $candidate)

            <div class="col-md-6 col-xl-4">

                <button
                    type="button"
                    class="candidate-button"
                    data-candidate-id="{{ $candidate->id }}"
                    data-candidate-number="{{ $candidate->number }}"
                    data-candidate-name="{{ $candidate->name }}"
                    data-candidate-photo="{{ $candidate->photo
                        ? asset('storage/' . $candidate->photo)
                        : '' }}"
                    aria-label="Pilih kandidat nomor {{ $candidate->number }}, {{ $candidate->name }}"
                >
                    <div class="candidate-card card border-0 shadow-sm">

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
                                        class="candidate-photo-placeholder d-flex align-items-center justify-content-center mx-auto mb-4"
                                    >
                                        <i class="bi bi-person-fill fs-1"></i>
                                    </div>
                                @endif

                                <h3 class="candidate-name mb-0">
                                    {{ $candidate->name }}
                                </h3>

                                <div class="candidate-instruction">
                                    <i class="bi bi-hand-index-thumb"></i>
                                    Tekan untuk Memilih
                                </div>

                            </div>

                        </div>

                    </div>
                </button>

            </div>

        @empty

            <div class="col-12">
                <div class="alert alert-warning text-center">
                    Belum ada kandidat yang tersedia.
                </div>
            </div>

        @endforelse

    </div>

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

                    <h2 class="fw-bold mb-1">
                        Apakah pilihan ini sudah benar?
                    </h2>

                    <p class="text-secondary mb-3">
                        Pastikan nomor dan nama kandidat sesuai.
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
                            <i class="bi bi-person-fill"></i>
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
                            id="backButton"
                            class="btn btn-outline-secondary"
                            data-bs-dismiss="modal"
                        >
                            <i class="bi bi-arrow-left me-2"></i>
                            Kembali
                        </button>

                        <form
                            id="confirmVoteForm"
                            action="{{ route('voting.store') }}"
                            method="POST"
                        >
                            @csrf

                            <input
                                type="hidden"
                                name="candidate_id"
                                id="confirmCandidateId"
                                value=""
                            >

                            <button
                                type="submit"
                                id="confirmVoteButton"
                                class="btn btn-success"
                            >
                                <i class="bi bi-check-circle-fill me-2"></i>
                                Ya, Pilih Kandidat Ini
                            </button>
                        </form>

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
        const candidateButtons =
            document.querySelectorAll('.candidate-button');

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

        const confirmVoteForm =
            document.getElementById('confirmVoteForm');

        const confirmCandidateId =
            document.getElementById('confirmCandidateId');

        const confirmVoteButton =
            document.getElementById('confirmVoteButton');

        if (
            !confirmationModalElement ||
            !confirmationNumber ||
            !confirmationName ||
            !confirmationPhoto ||
            !confirmationPhotoPlaceholder ||
            !confirmVoteForm ||
            !confirmCandidateId ||
            !confirmVoteButton
        ) {
            console.error(
                'Komponen halaman voting tidak lengkap.'
            );

            return;
        }

        const confirmationModal =
            new bootstrap.Modal(
                confirmationModalElement
            );

        let isSubmitting = false;

        candidateButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                if (isSubmitting) {
                    return;
                }

                const candidateId =
                    this.dataset.candidateId || '';

                const candidateNumber =
                    this.dataset.candidateNumber || '-';

                const candidateName =
                    this.dataset.candidateName || '-';

                const candidatePhoto =
                    this.dataset.candidatePhoto || '';

                confirmCandidateId.value =
                    candidateId;

                confirmationNumber.textContent =
                    candidateNumber;

                confirmationName.textContent =
                    candidateName;

                if (candidatePhoto !== '') {
                    confirmationPhoto.src =
                        candidatePhoto;

                    confirmationPhoto.classList.remove(
                        'd-none'
                    );

                    confirmationPhotoPlaceholder.classList.add(
                        'd-none'
                    );
                } else {
                    confirmationPhoto.src = '';

                    confirmationPhoto.classList.add(
                        'd-none'
                    );

                    confirmationPhotoPlaceholder.classList.remove(
                        'd-none'
                    );
                }

                confirmationModal.show();
            });
        });

        confirmationModalElement.addEventListener(
            'hidden.bs.modal',
            function () {
                if (isSubmitting) {
                    return;
                }

                confirmCandidateId.value = '';
                confirmationNumber.textContent = '-';
                confirmationName.textContent = '-';
                confirmationPhoto.src = '';

                confirmationPhoto.classList.add(
                    'd-none'
                );

                confirmationPhotoPlaceholder.classList.remove(
                    'd-none'
                );
            }
        );

        confirmVoteForm.addEventListener(
            'submit',
            function (event) {
                if (
                    confirmCandidateId.value === '' ||
                    isSubmitting
                ) {
                    event.preventDefault();
                    return;
                }

                isSubmitting = true;

                confirmVoteButton.disabled = true;

                confirmVoteButton.innerHTML =
                    '<span class="spinner-border ' +
                    'spinner-border-sm me-2" ' +
                    'aria-hidden="true"></span>' +
                    'Menyimpan Pilihan...';
            }
        );
    });
</script>

</body>
</html>