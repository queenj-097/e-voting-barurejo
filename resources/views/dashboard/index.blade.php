@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-4">
        <div>
            <h1 class="page-heading mb-1">
                Dashboard
            </h1>

            <p class="text-secondary mb-0">
                Ringkasan pelaksanaan {{ $setting?->title ?? 'pemungutan suara elektronik' }}
oleh {{ $setting?->institution ?? 'Pemerintah Desa Barurejo' }}.
            </p>
        </div>

        <div class="mt-3 mt-lg-0 text-lg-end">

            @if($setting?->election_date)
                <div class="fw-semibold">
                    {{ $setting->election_date->translatedFormat('l, d F Y') }}
                </div>
            @else
                <div class="fw-semibold text-secondary">
                    Tanggal belum ditentukan
                </div>
            @endif

            <small class="text-secondary">
                Status:
                <span class="fw-semibold">
                    {{ ucfirst($setting?->status ?? 'Persiapan') }}
                </span>
            </small>

        </div>
    </div>

    {{-- Kartu statistik --}}
    <div class="row g-4 mb-4">

        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-secondary mb-2">
                                Total DPT
                            </p>

                            <h2 class="fw-bold mb-1">
                                {{ $totalVoters }}
                            </h2>

                            <small class="text-secondary">
                                Pemilih terdaftar
                            </small>
                        </div>

                        <div class="rounded-3 bg-primary-subtle text-primary p-3">
                            <i class="bi bi-people-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-secondary mb-2">
                                Sudah Memilih
                            </p>

                            <h2 class="fw-bold text-success mb-1">
                                {{ $votedVoters }}
                            </h2>

                            <small class="text-secondary">
                                {{ $participationPercentage }}% dari total DPT
                            </small>
                        </div>

                        <div class="rounded-3 bg-success-subtle text-success p-3">
                            <i class="bi bi-check-circle-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-secondary mb-2">
                                Belum Memilih
                            </p>

                            <h2 class="fw-bold text-danger mb-1">
                                {{ $notVotedVoters }}
                            </h2>

                            <small class="text-secondary">
                                Belum menggunakan hak pilih
                            </small>
                        </div>

                        <div class="rounded-3 bg-danger-subtle text-danger p-3">
                            <i class="bi bi-hourglass-split fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-secondary mb-2">
                                Surat Suara Masuk
                            </p>

                            <h2 class="fw-bold mb-1">
                                {{ $totalBallots }}
                            </h2>

                            <small class="text-secondary">
                                QR surat suara dibuat
                            </small>
                        </div>

                        <div class="rounded-3 bg-info-subtle text-info p-3">
                            <i class="bi bi-box2-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-secondary mb-2">
                                Sudah Dihitung
                            </p>

                            <h2 class="fw-bold text-success mb-1">
                                {{ $countedBallots }}
                            </h2>

                            <small class="text-secondary">
                                QR berhasil divalidasi
                            </small>
                        </div>

                        <div class="rounded-3 bg-success-subtle text-success p-3">
                            <i class="bi bi-clipboard2-check-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-secondary mb-2">
                                Belum Dihitung
                            </p>

                            <h2 class="fw-bold text-warning mb-1">
                                {{ $uncountedBallots }}
                            </h2>

                            <small class="text-secondary">
                                Menunggu validasi QR
                            </small>
                        </div>

                        <div class="rounded-3 bg-warning-subtle text-warning p-3">
                            <i class="bi bi-clock-history fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-4 mb-4">

        {{-- Pemenang sementara --}}
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-trophy-fill text-warning me-2"></i>
                        Perolehan Tertinggi Sementara
                    </h5>
                </div>

                <div class="card-body p-4 text-center">

                    @if ($temporaryWinner)

                        @if ($temporaryWinner->photo)
                            <img
                                src="{{ asset('storage/' . $temporaryWinner->photo) }}"
                                alt="Foto {{ $temporaryWinner->name }}"
                                width="130"
                                height="130"
                                class="rounded-circle border mb-3"
                                style="object-fit: cover;"
                            >
                        @else
                            <div
                                class="rounded-circle bg-secondary-subtle d-flex align-items-center justify-content-center mx-auto mb-3"
                                style="width: 130px; height: 130px;"
                            >
                                <i class="bi bi-person-fill fs-1 text-secondary"></i>
                            </div>
                        @endif

                        <div class="badge text-bg-success mb-2">
                            Nomor Urut {{ $temporaryWinner->number }}
                        </div>

                        <h3 class="fw-bold">
                            {{ $temporaryWinner->name }}
                        </h3>

                        <div class="display-6 fw-bold text-success">
                            {{ $temporaryWinner->counted_votes }}
                        </div>

                        <p class="text-secondary mb-0">
                            suara sah
                        </p>

                    @else

                        <div
                            class="rounded-circle bg-secondary-subtle d-flex align-items-center justify-content-center mx-auto mb-3"
                            style="width: 110px; height: 110px;"
                        >
                            <i class="bi bi-hourglass fs-1 text-secondary"></i>
                        </div>

                        <h5 class="fw-bold">
                            Belum Ada Suara Dihitung
                        </h5>

                        <p class="text-secondary mb-0">
                            Hasil sementara akan muncul setelah QR divalidasi.
                        </p>

                    @endif

                </div>
            </div>
        </div>

        {{-- Perolehan suara --}}
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        Perolehan Suara Sementara
                    </h5>

                    <a
                        href="{{ route('results.index') }}"
                        class="btn btn-sm btn-outline-success"
                    >
                        Lihat Rekapitulasi
                    </a>
                </div>

                <div class="card-body p-4">

                    @forelse ($candidates as $candidate)

                        @php
                            $percentage = $countedBallots > 0
                                ? round(($candidate->counted_votes / $countedBallots) * 100, 1)
                                : 0;
                        @endphp

                        <div class="mb-4">

                            <div class="d-flex align-items-center justify-content-between gap-3 mb-2">

                                <div class="d-flex align-items-center gap-3">

                                    @if ($candidate->photo)
                                        <img
                                            src="{{ asset('storage/' . $candidate->photo) }}"
                                            alt="{{ $candidate->name }}"
                                            width="48"
                                            height="48"
                                            class="rounded-circle border"
                                            style="object-fit: cover;"
                                        >
                                    @endif

                                    <div>
                                        <div class="fw-bold">
                                            No. {{ $candidate->number }} —
                                            {{ $candidate->name }}
                                        </div>

                                        <small class="text-secondary">
                                            {{ $percentage }}%
                                        </small>
                                    </div>

                                </div>

                                <div class="fw-bold">
                                    {{ $candidate->counted_votes }} suara
                                </div>

                            </div>

                            <div class="progress" style="height: 18px;">
                                <div
                                    class="progress-bar bg-success"
                                    role="progressbar"
                                    style="width: {{ $percentage }}%;"
                                    aria-valuenow="{{ $percentage }}"
                                    aria-valuemin="0"
                                    aria-valuemax="100"
                                >
                                    @if ($percentage >= 10)
                                        {{ $percentage }}%
                                    @endif
                                </div>
                            </div>

                        </div>

                    @empty

                        <div class="text-center py-5 text-secondary">
                            Belum ada kandidat.
                        </div>

                    @endforelse

                </div>
            </div>
        </div>

    </div>

    {{-- Monitor status bilik --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">
                        <i class="bi bi-display-fill text-success me-2"></i>
                        Status Bilik
                    </h5>

                    <small class="text-secondary">
                        Kondisi perangkat bilik diperbarui otomatis.
                    </small>
                </div>

                <span class="badge text-bg-light border">
                    Live
                </span>
            </div>
        </div>

        <div class="card-body p-4">
            <div
                id="boothMonitor"
                class="row g-3"
            >
                @forelse ($booths as $booth)

                    @php
                        $statusLabel = match ($booth->status) {
                            'assigned' => 'Pemilih Siap',
                            'voting' => 'Sedang Memilih',
                            'offline' => 'Tidak Aktif',
                            default => 'Tersedia',
                        };

                        $statusClass = match ($booth->status) {
                            'assigned' => 'warning',
                            'voting' => 'primary',
                            'offline' => 'danger',
                            default => 'success',
                        };

                        $statusIcon = match ($booth->status) {
                            'assigned' => 'bi-person-check-fill',
                            'voting' => 'bi-pencil-square',
                            'offline' => 'bi-x-circle-fill',
                            default => 'bi-check-circle-fill',
                        };
                    @endphp

                    <div
                        class="col-md-6 col-xl-4"
                        id="booth-card-{{ $booth->id }}"
                    >
                        <div class="border rounded-4 p-3 h-100">

                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="fw-bold mb-1 booth-name">
                                        {{ $booth->name }}
                                    </h5>

                                    <span
                                        class="badge text-bg-{{ $statusClass }} booth-status"
                                    >
                                        {{ $statusLabel }}
                                    </span>
                                </div>

                                <div
                                    class="rounded-3 bg-{{ $statusClass }}-subtle text-{{ $statusClass }} p-3 booth-icon"
                                >
                                    <i class="bi {{ $statusIcon }} fs-4"></i>
                                </div>
                            </div>

                            <div class="booth-voter">
                                @if ($booth->currentVoter)
                                    <small class="text-secondary">
                                        Pemilih
                                    </small>

                                    <div class="fw-bold">
                                        {{ $booth->currentVoter->name }}
                                    </div>

                                    <small class="text-secondary">
                                        {{ $booth->currentVoter->dpt_number }}
                                    </small>
                                @else
                                    <span class="text-secondary">
                                        Belum ada pemilih.
                                    </span>
                                @endif
                            </div>

                            <a
                                href="{{ route('booths.show', $booth) }}"
                                target="_blank"
                                class="btn btn-sm btn-outline-success w-100 mt-3"
                            >
                                <i class="bi bi-box-arrow-up-right me-1"></i>
                                Buka Bilik
                            </a>

                        </div>
                    </div>

                @empty

                    <div class="col-12">
                        <div class="text-center text-secondary py-4">
                            Belum ada bilik yang dibuat.
                        </div>
                    </div>

                @endforelse
            </div>
        </div>
    </div>

    {{-- Menu cepat --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <h5 class="fw-bold mb-3">
                Menu Cepat
            </h5>

            <div class="d-flex flex-wrap gap-2">

                <a
                    href="{{ route('voters.index') }}"
                    class="btn btn-outline-primary"
                >
                    <i class="bi bi-people-fill me-1"></i>
                    Data DPT
                </a>

                <a
                    href="{{ route('candidates.index') }}"
                    class="btn btn-outline-primary"
                >
                    <i class="bi bi-person-badge-fill me-1"></i>
                    Data Kandidat
                </a>

                <a
                    href="{{ route('verification.index') }}"
                    class="btn btn-outline-success"
                >
                    <i class="bi bi-person-check-fill me-1"></i>
                    Verifikasi Pemilih
                </a>

                <a
                    href="{{ route('scan.index') }}"
                    class="btn btn-outline-success"
                >
                    <i class="bi bi-qr-code-scan me-1"></i>
                    Scan QR
                </a>

                <a
                    href="{{ route('results.index') }}"
                    class="btn btn-outline-dark"
                >
                    <i class="bi bi-bar-chart-fill me-1"></i>
                    Rekapitulasi
                </a>

            </div>

        </div>
    </div>

    <div class="text-center text-secondary small mt-4 pb-2">
        E-Voting Desa Barurejo · Kecamatan Siliragung · Kabupaten Banyuwangi
        <br>
        KKN BBK Universitas Airlangga 2026
    </div>

</div>

<script>
    const boothStatusUrl = @json(route('booths.status'));

    function getBoothDisplay(status) {
        const displays = {
            idle: {
                label: 'Tersedia',
                color: 'success',
                icon: 'bi-check-circle-fill'
            },
            assigned: {
                label: 'Pemilih Siap',
                color: 'warning',
                icon: 'bi-person-check-fill'
            },
            voting: {
                label: 'Sedang Memilih',
                color: 'primary',
                icon: 'bi-pencil-square'
            },
            offline: {
                label: 'Tidak Aktif',
                color: 'danger',
                icon: 'bi-x-circle-fill'
            }
        };

        return displays[status] ?? displays.idle;
    }

    async function refreshBoothMonitor() {
        try {
            const response = await fetch(boothStatusUrl, {
                headers: {
                    'Accept': 'application/json'
                },
                cache: 'no-store'
            });

            if (!response.ok) {
                throw new Error('Gagal mengambil status bilik.');
            }

            const booths = await response.json();

            booths.forEach((booth) => {
                const card = document.getElementById(
                    `booth-card-${booth.id}`
                );

                if (!card) {
                    return;
                }

                const display = getBoothDisplay(booth.status);
                const badge = card.querySelector('.booth-status');
                const iconBox = card.querySelector('.booth-icon');
                const voterBox = card.querySelector('.booth-voter');

                badge.className =
                    `badge text-bg-${display.color} booth-status`;

                badge.textContent = display.label;

                iconBox.className =
                    `rounded-3 bg-${display.color}-subtle ` +
                    `text-${display.color} p-3 booth-icon`;

                iconBox.innerHTML =
                    `<i class="bi ${display.icon} fs-4"></i>`;

                if (booth.voter_name) {
                    voterBox.innerHTML = `
                        <small class="text-secondary">
                            Pemilih
                        </small>

                        <div class="fw-bold">
                            ${escapeHtml(booth.voter_name)}
                        </div>

                        <small class="text-secondary">
                            ${escapeHtml(booth.dpt_number ?? '-')}
                        </small>
                    `;
                } else {
                    voterBox.innerHTML = `
                        <span class="text-secondary">
                            Belum ada pemilih.
                        </span>
                    `;
                }
            });
        } catch (error) {
            console.error(error);
        }
    }

    function escapeHtml(value) {
        const element = document.createElement('div');
        element.textContent = String(value);
        return element.innerHTML;
    }

    setInterval(refreshBoothMonitor, 2000);
</script>

<script>
    setTimeout(function () {
        window.location.reload();
    }, 5000);
</script>

@endsection