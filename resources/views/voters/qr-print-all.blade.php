@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <div>
            <h2 class="fw-bold mb-1">Cetak Semua QR DPT</h2>
            <p class="text-muted mb-0">
                Total {{ $voters->count() }} kartu pemilih.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('voters.index') }}"
               class="btn btn-outline-secondary">
                Kembali
            </a>

            <button type="button"
                    class="btn btn-primary"
                    onclick="window.print()">
                Cetak Semua
            </button>
        </div>
    </div>

    @if ($voters->isEmpty())
        <div class="alert alert-warning no-print">
            Data DPT belum tersedia.
        </div>
    @else
        <div class="qr-list">

            @foreach ($voters as $voter)
                <section class="qr-card">

                    <div class="card-title">
                        E-Voting Desa Barurejo
                    </div>

                    <div class="card-subtitle">
                        Kartu Pemilih
                    </div>

                    <img
                        src="{{ route('voters.qr.image', $voter) }}"
                        alt="QR {{ $voter->voter_code }}"
                        class="qr-image"
                    >

                    <div class="voter-code">
                        {{ $voter->voter_code }}
                    </div>

                    <div class="voter-name">
                        {{ strtoupper($voter->name) }}
                    </div>

                    <div class="voter-area">
                        {{ strtoupper($voter->dusun?->name ?? '-') }}
                        · RW {{ $voter->rw }}
                        · RT {{ $voter->rt }}
                    </div>

                </section>
            @endforeach

        </div>
    @endif

</div>

<style>
.qr-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.qr-card {
    border: 1px solid #dee2e6;
    border-radius: 12px;
    padding: 24px 16px;
    text-align: center;
    background: #fff;
}

.qr-image {
    width: 240px;
    height: 240px;
    max-width: 100%;
    object-fit: contain;
    display: block;
    margin: 16px auto;
}

.card-title {
    font-size: 18px;
    font-weight: 700;
}

.card-subtitle {
    font-size: 14px;
    color: #6c757d;
}

.voter-code {
    font-size: 20px;
    font-weight: 700;
    margin-top: 8px;
}

.voter-name {
    font-size: 16px;
    font-weight: 600;
    margin-top: 4px;
}

.voter-area {
    font-size: 13px;
    margin-top: 8px;
    color: #495057;
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

    .no-print,
    aside,
    header,
    nav {
        display: none !important;
    }

    .container-fluid {
        width: 80mm !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .qr-list {
        display: block;
        width: 80mm;
    }

    .qr-card {
        width: 80mm;
        min-height: 105mm;
        margin: 0;
        padding: 5mm 3mm;
        border: 0;
        border-radius: 0;
        box-sizing: border-box;
        page-break-after: always;
        break-after: page;
        text-align: center;
    }

    .qr-card:last-child {
        page-break-after: auto;
        break-after: auto;
    }

    .qr-image {
        width: 58mm !important;
        height: 58mm !important;
        max-width: none !important;
        margin: 3mm auto;
    }

    .card-title {
        font-size: 16pt;
        font-weight: 700;
    }

    .card-subtitle {
        font-size: 10pt;
        margin-top: 1mm;
    }

    .voter-code {
        font-size: 17pt;
        line-height: 1.15;
        margin-top: 2mm;
    }

    .voter-name {
        font-size: 14pt;
        line-height: 1.2;
        margin-top: 1mm;
    }

    .voter-area {
        font-size: 10pt;
        color: #000 !important;
        margin-top: 2mm;
    }
}
</style>
@endsection