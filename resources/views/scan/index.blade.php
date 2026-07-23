@extends('layouts.admin')

@section('title', 'Scan QR')

@section('content')

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

            @if (session('scan_message'))

                @php
                    $scanStatus = session('scan_status');

                    $alertClass = match ($scanStatus) {
                        'success' => 'success',
                        'duplicate' => 'warning',
                        default => 'danger',
                    };

                    $iconClass = match ($scanStatus) {
                        'success' => 'bi-check-circle-fill',
                        'duplicate' => 'bi-exclamation-triangle-fill',
                        default => 'bi-x-circle-fill',
                    };
                @endphp

                <div
                    class="alert alert-{{ $alertClass }} text-center py-4 shadow-sm"
                    id="scanResult"
                >
                    <i class="bi {{ $iconClass }} display-4 d-block mb-2"></i>

                    <h3 class="fw-bold mb-2">
                        @if ($scanStatus === 'success')
                            Suara Sah
                        @elseif ($scanStatus === 'duplicate')
                            QR Sudah Digunakan
                        @else
                            Scan Gagal
                        @endif
                    </h3>

                    <div class="fs-5">
                        {{ session('scan_message') }}
                    </div>

                    <small class="d-block mt-3">
                        Kembali ke mode scan dalam
                        <strong id="countdown">3</strong>
                        detik.
                    </small>
                </div>

            @endif

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
                            <label class="form-label fw-semibold">
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
                scanner USB biasanya bekerja seperti keyboard. Setelah QR terbaca,
                token otomatis masuk ke kolom lalu scanner mengirim tombol Enter.
            </div>

        </div>
    </div>

</div>

<script>
    const tokenInput = document.getElementById('tokenInput');
    const scanForm = document.getElementById('scanForm');
    const submitButton = document.getElementById('submitButton');
    const scanResult = document.getElementById('scanResult');

    function focusScannerInput() {
        if (tokenInput) {
            tokenInput.focus();
        }
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

            const token = tokenInput.value.trim();

            if (!token) {
                return;
            }

            submitButton.disabled = true;
            submitButton.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2"></span>' +
                'Memproses...';

            scanForm.submit();
        }
    });

    if (scanResult) {
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