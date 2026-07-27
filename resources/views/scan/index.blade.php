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
        background: rgba(25, 135, 84, 0.97);
        color: white;
    }

    .scan-popup-duplicate {
        background: rgba(255, 193, 7, 0.97);
        color: #212529;
    }

    .scan-popup-error {
        background: rgba(220, 53, 69, 0.97);
        color: white;
    }

    .scan-popup-card {
        width: 100%;
        max-width: 680px;
        padding: 48px 32px;
        border-radius: 28px;
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.28);
        box-shadow: 0 24px 80px rgba(0, 0, 0, 0.28);
        backdrop-filter: blur(8px);
        animation: scanPopupIn 0.25s ease-out;
    }

    .scan-popup-icon {
        font-size: 92px;
        line-height: 1;
        margin-bottom: 24px;
    }

    .scan-popup-title {
        font-size: clamp(32px, 6vw, 54px);
        font-weight: 800;
        margin-bottom: 16px;
    }

    .scan-popup-message {
        font-size: clamp(18px, 3vw, 26px);
        margin-bottom: 28px;
    }

    .scan-popup-countdown {
        font-size: 16px;
        opacity: 0.9;
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

        </div>
    </div>

</div>

@if (session('scan_message'))

    @php
        $scanStatus = session('scan_status');

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

            <div class="scan-popup-countdown">
                Kembali ke mode scan dalam
                <strong id="countdown">3</strong>
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
        let seconds = 3;
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