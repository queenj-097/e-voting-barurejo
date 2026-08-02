@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">QR Pemilih</h2>
            <p class="text-muted mb-0">
                QR identitas pemilih tetap Desa Barurejo.
            </p>
        </div>

        <a href="{{ route('voters.index') }}"
           class="btn btn-outline-secondary">
            Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body text-center py-5">

            <div id="qr-print-area">

                <h4 class="fw-bold mb-1">
                    E-Voting Desa Barurejo
                </h4>

                <p class="text-muted mb-4">
                    Kartu Pemilih
                </p>

                <img
                    src="{{ route('voters.qr.image', $voter) }}"
                    alt="QR {{ $voter->voter_code }}"
                    class="qr-image mb-3"
                >

                <h3 class="fw-bold mb-2">
                    {{ $voter->voter_code }}
                </h3>

                <h5 class="mb-3">
                    {{ strtoupper($voter->name) }}
                </h5>

                <div class="text-muted">
                    {{ strtoupper($voter->dusun?->name ?? '-') }}
                    · RW {{ $voter->rw }}
                    · RT {{ $voter->rt }}
                </div>

                <div class="text-muted">
                    ---
                </div>

            </div>

            <div class="mt-4">
                <button
                    type="button"
                    class="btn btn-primary px-4"
                    onclick="window.print()">
                    Cetak
                </button>
            </div>

        </div>
    </div>

</div>

<style>
.qr-image {
    width: 500px;
    height: 500px;
    max-width: 100%;
    object-fit: contain;
}

@media print {
    @page {
        size: 80mm auto;
        margin: 0;
    }

    html,
    body {
        width: 80mm;
        margin: 0 !important;
        padding: 0 !important;
    }

    body * {
        visibility: hidden;
    }

    #qr-print-area,
    #qr-print-area * {
        visibility: visible;
    }

    #qr-print-area {
        position: absolute;
        top: 0;
        left: 0;
        width: 80mm;
        margin: 0;
        padding: 5mm 3mm;
        box-sizing: border-box;
        text-align: center;
    }

    #qr-print-area .qr-image {
        width: 58mm !important;
        height: 58mm !important;
        max-width: none !important;
        display: block;
        margin: 0 auto 3mm;
    }

    #qr-print-area h4 {
        font-size: 16pt;
        margin-bottom: 1mm;
    }

    #qr-print-area p {
        font-size: 11pt;
        margin-bottom: 3mm !important;
    }

    #qr-print-area h3 {
        font-size: 18pt;
        line-height: 1.2;
        margin-bottom: 2mm !important;
    }

    #qr-print-area h5 {
        font-size: 15pt;
        line-height: 1.2;
        margin-bottom: 2mm !important;
    }

    #qr-print-area .text-muted {
        font-size: 11pt;
        color: #000 !important;
    }
}
</style>
@endsection