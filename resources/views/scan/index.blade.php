@extends('layouts.admin')

@section('title', 'Scan QR')

@section('content')

<style>
    .scan-popup {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        text-align: center;
    }

    .scan-popup-success {
        background: rgba(25, 135, 84, 0.98);
        color: white;
    }

    .scan-popup-duplicate {
        background: rgba(255, 193, 7, 0.98);
        color: #212529;
    }

    .scan-popup-error {
        background: rgba(220, 53, 69, 0.98);
        color: white;
    }

    .scan-popup-card {
        width: 100%;
        max-width: 820px;
        max-height: calc(100vh - 48px);
        overflow-y: auto;
        padding: 36px 32px;
        border-radius: 28px;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.30);
        box-shadow: 0 24px 80px rgba(0, 0, 0, 0.28);
        backdrop-filter: blur(8px);
        animation: scanPopupIn 0.25s ease-out;
    }

    .scan-popup-icon {
        font-size: 74px;
        line-height: 1;
        margin-bottom: 16px;
    }

    .scan-popup-title {
        font-size: clamp(30px, 5vw, 48px);
        font-weight: 800;
        margin-bottom: 12px;
    }

    .scan-popup-message {
        font-size: clamp(17px, 2.5vw, 22px);
        margin-bottom: 22px;
    }

    .scan-result-box {
        max-width: 660px;
        margin: 0 auto 22px;
        padding: 24px;
        border-radius: 22px;
        background: rgba(255, 255, 255, 0.94);
        color: #203029;
    }

    .scan-result-photo {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 20px;
        border: 5px solid #e7eee9;
        margin-bottom: 14px;
    }

    .scan-result-photo-placeholder {
        width: 150px;
        height: 150px;
        margin: 0 auto 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 20px;
        border: 5px solid #e7eee9;
        background: #e9ecef;
        color: #6c757d;
    }

    .scan-result-number-label {
        font-size: 15px;
        color: #6c757d;
        margin-bottom: 2px;
    }

    .scan-result-number {
        font-size: 66px;
        font-weight: 800;
        line-height: 1;
        color: #198754;
        margin-bottom: 8px;
    }

    .scan-result-name {
        font-size: clamp(25px, 4vw, 36px);
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .scan-result-dusun {
        font-size: 20px;
        font-weight: 700;
        color: #495057;
        margin-bottom: 20px;
    }

    .scan-stat-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }

    .scan-stat-item {
        padding: 14px 10px;
        border-radius: 14px;
        background: #f3f7f5;
    }

    .scan-stat-label {
        display: block;
        color: #6c757d;
        font-size: 12px;
        margin-bottom: 4px;
    }

    .scan-stat-value {
        display: block;
        font-size: 20px;
        font-weight: 800;
    }

    .scan-popup-countdown {
        font-size: 16px;
        opacity: 0.95;
    }

    .scan-popup-countdown strong {
        display: inline-block;
        min-width: 28px;
        font-size: 28px;
    }

    @keyframes scanPopupIn {
        from {
            opacity: 0;
            transform: scale(0.92);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @media (max-width: 576px) {
        .scan-popup {
            padding: 12px;
        }

        .scan-popup-card {
            padding: 24px 16px;
        }

        .scan-result-box {
            padding: 18px 14px;
        }

        .scan-result-photo,
        .scan-result-photo-placeholder {
            width: 120px;
            height: 120px;
        }

        .scan-result-number {
            font-size: 54px;
        }

        .scan-stat-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-xl-8">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">

                <div>
                    <h1 class="page-heading mb-1">
                        Scan QR Surat Suara
                    </h1>

                    <p class="text-secondary mb-0">
                        Gunakan scanner USB untuk membaca QR surat suara.
                    </p>
                </div>

                <div class="mt-3 mt-md-0 text-md-end">
                    <span class="badge text-bg-success px-3 py-2">
                        <i class="bi bi-circle-fill me-1"></i>
                        Scanner Siap
                    </span>
                </div>

            </div>

            <div class="row g-4 mb-4">

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">

                            <p class="text-secondary mb-2">
                                Sudah Dihitung
                            </p>

                            <h2 class="fw-bold text-success mb-0">
                                {{ $countedBallots }}
                            </h2>

                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">

                            <p class="text-secondary mb-2">
                                Total Surat Suara
                            </p>

                            <h2 class="fw-bold mb-0">
                                {{ $totalBallots }}
                            </h2>

                        </div>
                    </div>
                </div>

            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="card border-0 shadow-sm">

                <div class="card-body p-4 p-md-5">

                    <div class="text-center mb-4">

                        <div
                            class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center mx-auto mb-3"
                            style="width: 100px; height: 100px;"
                        >
                            <i class="bi bi-qr-code-scan display-5"></i>
                        </div>

                        <h3 class="fw-bold">
                            Arahkan QR ke Scanner
                        </h3>

                        <p class="text-secondary mb-0">
                            Scanner akan mengisi token dan mengirimnya secara otomatis.
                        </p>

                    </div>

                    <form
                        action="{{ route('scan.store') }}"
                        method="POST"
                        id="scanForm"
                    >
                        @csrf

                        <div class="mb-4">

                            <label
                                for="tokenInput"
                                class="form-label fw-semibold"
                            >
                                Token QR
                            </label>

                            <input
                                type="text"
                                name="token"
                                id="tokenInput"
                                class="form-control form-control-lg text-center font-monospace"
                                placeholder="Menunggu hasil scan..."
                                autocomplete="off"
                                autofocus
                                required
                            >

                        </div>

                        <button
                            type="submit"
                            class="btn btn-success btn-lg w-100 py-3 fw-bold"
                            id="submitButton"
                        >
                            <i class="bi bi-check2-circle me-2"></i>
                            Validasi dan Hitung Suara
                        </button>

                    </form>

                </div>

            </div>

            <div class="alert alert-light border mt-4">

                <strong>Cara penggunaan:</strong>

                scanner USB bekerja seperti keyboard. Setelah QR terbaca,
                token otomatis masuk ke kolom lalu scanner mengirim tombol Enter.

            </div>

            <div class="card border-0 shadow-sm mt-4">

                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        QR Belum Dihitung
                    </h5>
                </div>

                <div class="card-body p-0">

                    @if ($uncountedBallots->isEmpty())

                        <div class="text-center text-secondary py-4">
                            Tidak ada QR yang dapat dicetak ulang.
                        </div>

                    @else

                        <div class="table-responsive">

                            <table class="table table-hover align-middle mb-0">

                                <thead class="table-light">
                                    <tr>
                                        <th width="80">ID</th>
                                        <th>Waktu Dibuat</th>
                                        <th width="180" class="text-center">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($uncountedBallots as $ballot)

                                        <tr>

                                            <td>
                                                #{{ $ballot->id }}
                                            </td>

                                            <td>
                                                {{ $ballot->created_at->format('d/m/Y H:i:s') }}
                                            </td>

                                            <td class="text-center">

                                                <a
                                                    href="{{ route('scan.ballots.reprint', $ballot) }}"
                                                    class="btn btn-outline-primary btn-sm"
                                                >
                                                    <i class="bi bi-printer me-1"></i>
                                                    Cetak Ulang
                                                </a>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @endif

                </div>

            </div>

        </div>
    </div>

</div>

@if (session('scan_message'))

    @php
        $scanStatus = session('scan_status');
        $scanResult = session('scan_result');

        $popupClass = match ($scanStatus) {
            'success' => 'scan-popup-success',
            'duplicate' => 'scan-popup-duplicate',
            default => 'scan-popup-error',
        };

        $iconClass = match ($scanStatus) {
            'success' => 'bi-check-circle-fill',
            'duplicate' => 'bi-exclamation-triangle-fill',
            default => 'bi-x-circle-fill',
        };

        $popupTitle = match ($scanStatus) {
            'success' => 'SUARA BERHASIL DIHITUNG',
            'duplicate' => 'QR SUDAH DIGUNAKAN',
            default => 'QR TIDAK VALID',
        };
    @endphp

    <div
        class="scan-popup {{ $popupClass }}"
        id="scanPopup"
        role="alert"
        aria-live="assertive"
    >
        <div class="scan-popup-card">

            <i class="bi {{ $iconClass }} scan-popup-icon"></i>

            <div class="scan-popup-title">
                {{ $popupTitle }}
            </div>

            <div class="scan-popup-message">
                {{ session('scan_message') }}
            </div>

            @if ($scanResult && !empty($scanResult['candidate_name']))

                <div class="scan-result-box">

                    @if (!empty($scanResult['candidate_photo']))

                        <img
                            src="{{ $scanResult['candidate_photo'] }}"
                            alt="Foto {{ $scanResult['candidate_name'] }}"
                            class="scan-result-photo"
                        >

                    @else

                        <div class="scan-result-photo-placeholder">
                            <i class="bi bi-person-fill fs-1"></i>
                        </div>

                    @endif

                    <div class="scan-result-number-label">
                        Nomor Kandidat
                    </div>

                    <div class="scan-result-number">
                        {{ $scanResult['candidate_number'] ?? '-' }}
                    </div>

                    <div class="scan-result-name">
                        {{ $scanResult['candidate_name'] }}
                    </div>

                    <div class="scan-result-dusun">
                        Dusun {{ $scanResult['dusuns'] ?? '-' }}
                    </div>

                    @if ($scanStatus === 'success')

                        <div class="scan-stat-grid">

                            <div class="scan-stat-item">

                                <span class="scan-stat-label">
                                    Suara Kandidat
                                </span>

                                <span class="scan-stat-value">
                                    {{ $scanResult['candidate_votes'] ?? 0 }}
                                </span>

                            </div>

                            <div class="scan-stat-item">

                                <span class="scan-stat-label">
                                    Total Suara Sah
                                </span>

                                <span class="scan-stat-value">
                                    {{ $scanResult['total_counted_ballots'] ?? 0 }}
                                </span>

                            </div>

                            <div class="scan-stat-item">

                                <span class="scan-stat-label">
                                    Waktu Dihitung
                                </span>

                                <span class="scan-stat-value" style="font-size: 14px;">
                                    {{ $scanResult['counted_at'] ?? '-' }}
                                </span>

                            </div>

                        </div>

                    @elseif ($scanStatus === 'duplicate')

                        <div class="alert alert-warning mb-0">
                            Surat suara untuk kandidat ini sudah dihitung pada
                            <strong>{{ $scanResult['counted_at'] ?? '-' }}</strong>.
                        </div>

                    @endif

                </div>

            @endif

            <div class="scan-popup-countdown">
                Kembali ke mode scan dalam
                <strong id="countdown">5</strong>
                detik
            </div>

        </div>
    </div>

@endif

<script>
    const tokenInput = document.getElementById('tokenInput');
    const scanForm = document.getElementById('scanForm');
    const submitButton = document.getElementById('submitButton');
    const scanPopup = document.getElementById('scanPopup');

    function focusScannerInput() {
        if (tokenInput && !scanPopup) {
            tokenInput.focus();
        }
    }

    function submitScanForm() {
        if (!tokenInput || !scanForm || !submitButton) {
            return;
        }

        const token = tokenInput.value.trim();

        if (!token || submitButton.disabled) {
            return;
        }

        submitButton.disabled = true;

        submitButton.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2"></span>' +
            'Memproses...';

        scanForm.submit();
    }

    document.addEventListener('DOMContentLoaded', function () {
        focusScannerInput();
    });

    document.addEventListener('click', function () {
        setTimeout(focusScannerInput, 50);
    });

    window.addEventListener('focus', focusScannerInput);

    tokenInput?.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            submitScanForm();
        }
    });

    if (scanPopup) {
        let seconds = 5;
        const countdown = document.getElementById('countdown');

        const timer = setInterval(function () {
            seconds -= 1;

            if (countdown) {
                countdown.textContent = seconds;
            }

            if (seconds <= 0) {
                clearInterval(timer);
                window.location.href = @json(route('scan.index'));
            }
        }, 1000);
    }
</script>

@endsection