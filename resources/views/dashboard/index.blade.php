@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-4">
        <div>
            <h1 class="page-heading mb-1">
                Dashboard
            </h1>

            <p class="text-secondary mb-0">
                Ringkasan pelaksanaan
                {{ $setting?->title ?? 'pemungutan suara elektronik' }}
                oleh
                {{ $setting?->institution ?? 'Pemerintah Desa Barurejo' }}.
            </p>
        </div>

        <div class="mt-3 mt-lg-0 text-lg-end">
            @if ($setting?->election_date)
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
                    {{ ucfirst($setting?->status ?? 'persiapan') }}
                </span>
            </small>

            <div>
                <small class="text-success">
                    <i class="bi bi-circle-fill me-1" style="font-size: 7px;"></i>
                    Data diperbarui otomatis
                </small>
            </div>
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

                            <h2
                                id="totalVoters"
                                class="fw-bold mb-1"
                            >
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

                            <h2
                                id="voted"
                                class="fw-bold text-success mb-1"
                            >
                                {{ $votedVoters }}
                            </h2>

                            <small class="text-secondary">
                                <span id="participationPercentage">
                                    {{ $participationPercentage }}
                                </span>% dari total DPT
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

                            <h2
                                id="notVoted"
                                class="fw-bold text-danger mb-1"
                            >
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

                            <h2
                                id="ballots"
                                class="fw-bold mb-1"
                            >
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

                            <h2
                                id="counted"
                                class="fw-bold text-success mb-1"
                            >
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

                            <h2
                                id="uncounted"
                                class="fw-bold text-warning mb-1"
                            >
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


    {{-- Hasil sementara --}}
    <div
        id="generalResultsContainer"
        class="{{ $candidateScope === 'grouped' ? 'd-none' : '' }}"
    >
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

                    <div
                        id="temporaryWinnerCard"
                        class="card-body p-4 text-center"
                    >
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

                        @if (auth()->user()->role === 'admin')
                            <a
                                href="{{ route('results.index') }}"
                                class="btn btn-sm btn-outline-success"
                            >
                                Lihat Rekapitulasi
                            </a>
                        @endif
                    </div>

                    <div
                        id="candidateResults"
                        class="card-body p-4"
                    >
                        @forelse ($candidates as $candidate)

                            @php
                                $percentage = $countedBallots > 0
                                    ? round(
                                        ($candidate->counted_votes / $countedBallots) * 100,
                                        1
                                    )
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
                                        @else
                                            <div
                                                class="rounded-circle bg-secondary-subtle d-flex align-items-center justify-content-center"
                                                style="width: 48px; height: 48px;"
                                            >
                                                <i class="bi bi-person-fill text-secondary"></i>
                                            </div>
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
    </div>

    {{-- Hasil sementara per kelompok --}}
    <div
        id="groupedResultsContainer"
        class="{{ $candidateScope === 'grouped' ? '' : 'd-none' }}"
    >
        <div
            id="groupedResultsGrid"
            class="row g-4 mb-4 justify-content-center"
        >
            @forelse ($groupedResults as $group)
                @php
                    $isCenteredLastCard =
                        $loop->last && $groupedResults->count() % 2 === 1;

                    $winner = $group['temporary_winner'];
                    $groupCandidates = $group['candidates'];
                @endphp

                <div
                    class="col-12 col-xl-6 {{ $isCenteredLastCard ? 'mx-auto' : '' }}"
                    id="election-group-card-{{ $group['id'] }}"
                >
                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-header bg-white py-3">
                            <div class="d-flex flex-column flex-md-row justify-content-between gap-2">
                                <div>
                                    <h5 class="fw-bold mb-1">
                                        {{ $group['name'] }}
                                    </h5>

                                    <div class="text-secondary">
                                        <i class="bi bi-geo-alt-fill text-success me-1"></i>
                                        {{ $group['dusun_text'] }}
                                    </div>
                                </div>

                                <div class="text-md-end">
                                    <span class="badge text-bg-light border">
                                        {{ $group['counted_ballots'] }} suara dihitung
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">

                            <div class="border rounded-4 p-4 text-center mb-4 group-winner">
                                <h6 class="text-uppercase text-secondary fw-bold mb-3">
                                    <i class="bi bi-trophy-fill text-warning me-2"></i>
                                    Perolehan Tertinggi Sementara
                                </h6>

                                @if ($winner)

                                    @if ($winner['photo_url'])
                                        <img
                                            src="{{ $winner['photo_url'] }}"
                                            alt="Foto {{ $winner['name'] }}"
                                            width="110"
                                            height="110"
                                            class="rounded-circle border mb-3"
                                            style="object-fit: cover;"
                                        >
                                    @else
                                        <div
                                            class="rounded-circle bg-secondary-subtle d-flex align-items-center justify-content-center mx-auto mb-3"
                                            style="width: 110px; height: 110px;"
                                        >
                                            <i class="bi bi-person-fill fs-1 text-secondary"></i>
                                        </div>
                                    @endif

                                    <div class="badge text-bg-success mb-2">
                                        Nomor Urut {{ $winner['number'] }}
                                    </div>

                                    <h4 class="fw-bold mb-1">
                                        {{ $winner['name'] }}
                                    </h4>

                                    <div class="fs-2 fw-bold text-success">
                                        {{ $winner['counted_votes'] }}
                                    </div>

                                    <div class="text-secondary">
                                        suara sah
                                    </div>

                                @else

                                    <div
                                        class="rounded-circle bg-secondary-subtle d-flex align-items-center justify-content-center mx-auto mb-3"
                                        style="width: 90px; height: 90px;"
                                    >
                                        <i class="bi bi-hourglass fs-1 text-secondary"></i>
                                    </div>

                                    <h6 class="fw-bold">
                                        Belum Ada Suara Dihitung
                                    </h6>

                                    <p class="text-secondary mb-0">
                                        Hasil akan muncul setelah QR divalidasi.
                                    </p>

                                @endif
                            </div>

                            <div>
                                <h6 class="fw-bold mb-3">
                                    Perolehan Suara Sementara
                                </h6>

                                <div class="group-candidate-results">
                                    @forelse ($groupCandidates as $candidate)
                                        <div class="mb-4">

                                            <div class="d-flex align-items-center justify-content-between gap-3 mb-2">

                                                <div class="d-flex align-items-center gap-3">

                                                    @if ($candidate['photo_url'])
                                                        <img
                                                            src="{{ $candidate['photo_url'] }}"
                                                            alt="{{ $candidate['name'] }}"
                                                            width="46"
                                                            height="46"
                                                            class="rounded-circle border"
                                                            style="object-fit: cover;"
                                                        >
                                                    @else
                                                        <div
                                                            class="rounded-circle bg-secondary-subtle d-flex align-items-center justify-content-center"
                                                            style="width: 46px; height: 46px;"
                                                        >
                                                            <i class="bi bi-person-fill text-secondary"></i>
                                                        </div>
                                                    @endif

                                                    <div>
                                                        <div class="fw-bold">
                                                            No. {{ $candidate['number'] }} —
                                                            {{ $candidate['name'] }}
                                                        </div>

                                                        <small class="text-secondary">
                                                            {{ $candidate['percentage'] }}%
                                                        </small>
                                                    </div>

                                                </div>

                                                <div class="fw-bold text-nowrap">
                                                    {{ $candidate['counted_votes'] }} suara
                                                </div>

                                            </div>

                                            <div class="progress" style="height: 18px;">
                                                <div
                                                    class="progress-bar bg-success"
                                                    role="progressbar"
                                                    style="width: {{ $candidate['percentage'] }}%;"
                                                >
                                                    @if ($candidate['percentage'] >= 10)
                                                        {{ $candidate['percentage'] }}%
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                    @empty
                                        <div class="text-center py-4 text-secondary">
                                            Belum ada kandidat dalam kelompok ini.
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        Belum ada kelompok pemilihan yang tersedia.
                    </div>
                </div>
            @endforelse
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
                                    <h5 class="fw-bold mb-1">
                                        {{ $booth->name }}
                                    </h5>

                                    <span class="badge text-bg-{{ $statusClass }} booth-status">
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

                @if (auth()->user()->role === 'admin')
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
                        href="{{ route('results.index') }}"
                        class="btn btn-outline-dark"
                    >
                        <i class="bi bi-bar-chart-fill me-1"></i>
                        Rekapitulasi
                    </a>
                @endif

                @if (in_array(auth()->user()->role, ['admin', 'verifikator'], true))
                    <a
                        href="{{ route('verification.index') }}"
                        class="btn btn-outline-success"
                    >
                        <i class="bi bi-person-check-fill me-1"></i>
                        Verifikasi Pemilih
                    </a>
                @endif

                @if (in_array(auth()->user()->role, ['admin', 'scanner'], true))
                    <a
                        href="{{ route('scan.index') }}"
                        class="btn btn-outline-success"
                    >
                        <i class="bi bi-qr-code-scan me-1"></i>
                        Scan QR
                    </a>
                @endif

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
    const dashboardLiveUrl = @json(route('dashboard.live'));
    const boothStatusUrl = @json(route('booths.status'));

    function escapeHtml(value) {
        const element = document.createElement('div');
        element.textContent = String(value ?? '');
        return element.innerHTML;
    }

    function setText(id, value) {
        const element = document.getElementById(id);

        if (element) {
            element.textContent = value;
        }
    }


    function renderTemporaryWinner(winner) {
        const container = document.getElementById('temporaryWinnerCard');

        if (!container) {
            return;
        }

        if (!winner) {
            container.innerHTML = `
                <div
                    class="rounded-circle bg-secondary-subtle d-flex
                    align-items-center justify-content-center mx-auto mb-3"
                    style="width:110px;height:110px;"
                >
                    <i class="bi bi-hourglass fs-1 text-secondary"></i>
                </div>

                <h5 class="fw-bold">
                    Belum Ada Suara Dihitung
                </h5>

                <p class="text-secondary mb-0">
                    Hasil sementara akan muncul setelah QR divalidasi.
                </p>
            `;

            return;
        }

        const photo = winner.photo_url
            ? `
                <img
                    src="${escapeHtml(winner.photo_url)}"
                    alt="Foto ${escapeHtml(winner.name)}"
                    width="130"
                    height="130"
                    class="rounded-circle border mb-3"
                    style="object-fit:cover;"
                >
            `
            : `
                <div
                    class="rounded-circle bg-secondary-subtle d-flex
                    align-items-center justify-content-center mx-auto mb-3"
                    style="width:130px;height:130px;"
                >
                    <i class="bi bi-person-fill fs-1 text-secondary"></i>
                </div>
            `;

        container.innerHTML = `
            ${photo}

            <div class="badge text-bg-success mb-2">
                Nomor Urut ${escapeHtml(winner.number)}
            </div>

            <h3 class="fw-bold">
                ${escapeHtml(winner.name)}
            </h3>

            <div class="display-6 fw-bold text-success">
                ${escapeHtml(winner.counted_votes)}
            </div>

            <p class="text-secondary mb-0">
                suara sah
            </p>
        `;
    }

    function renderCandidateResults(candidates) {
        const container = document.getElementById('candidateResults');

        if (!container) {
            return;
        }

        if (!candidates.length) {
            container.innerHTML = `
                <div class="text-center py-5 text-secondary">
                    Belum ada kandidat.
                </div>
            `;

            return;
        }

        container.innerHTML = candidates.map((candidate) => {
            return renderCandidateResultItem(candidate);
        }).join('');
    }

    function renderCandidateResultItem(candidate) {
        const photo = candidate.photo_url
            ? `
                <img
                    src="${escapeHtml(candidate.photo_url)}"
                    alt="${escapeHtml(candidate.name)}"
                    width="48"
                    height="48"
                    class="rounded-circle border"
                    style="object-fit:cover;"
                >
            `
            : `
                <div
                    class="rounded-circle bg-secondary-subtle d-flex
                    align-items-center justify-content-center flex-shrink-0"
                    style="width:48px;height:48px;"
                >
                    <i class="bi bi-person-fill text-secondary"></i>
                </div>
            `;

        const percentage = Number(candidate.percentage ?? 0);
        const progressText = percentage >= 10
            ? `${escapeHtml(percentage)}%`
            : '';

        return `
            <div class="mb-4">

                <div class="d-flex align-items-center justify-content-between gap-3 mb-2">

                    <div class="d-flex align-items-center gap-3">
                        ${photo}

                        <div>
                            <div class="fw-bold">
                                No. ${escapeHtml(candidate.number)} —
                                ${escapeHtml(candidate.name)}
                            </div>

                            <small class="text-secondary">
                                ${escapeHtml(percentage)}%
                            </small>
                        </div>
                    </div>

                    <div class="fw-bold text-nowrap">
                        ${escapeHtml(candidate.counted_votes)} suara
                    </div>

                </div>

                <div class="progress" style="height:18px;">
                    <div
                        class="progress-bar bg-success"
                        role="progressbar"
                        style="width:${percentage}%;"
                    >
                        ${progressText}
                    </div>
                </div>

            </div>
        `;
    }

    function renderGroupedWinner(winner) {
        if (!winner) {
            return `
                <h6 class="text-uppercase text-secondary fw-bold mb-3">
                    <i class="bi bi-trophy-fill text-warning me-2"></i>
                    Perolehan Tertinggi Sementara
                </h6>

                <div
                    class="rounded-circle bg-secondary-subtle d-flex
                    align-items-center justify-content-center mx-auto mb-3"
                    style="width:90px;height:90px;"
                >
                    <i class="bi bi-hourglass fs-1 text-secondary"></i>
                </div>

                <h6 class="fw-bold">
                    Belum Ada Suara Dihitung
                </h6>

                <p class="text-secondary mb-0">
                    Hasil akan muncul setelah QR divalidasi.
                </p>
            `;
        }

        const photo = winner.photo_url
            ? `
                <img
                    src="${escapeHtml(winner.photo_url)}"
                    alt="Foto ${escapeHtml(winner.name)}"
                    width="110"
                    height="110"
                    class="rounded-circle border mb-3"
                    style="object-fit:cover;"
                >
            `
            : `
                <div
                    class="rounded-circle bg-secondary-subtle d-flex
                    align-items-center justify-content-center mx-auto mb-3"
                    style="width:110px;height:110px;"
                >
                    <i class="bi bi-person-fill fs-1 text-secondary"></i>
                </div>
            `;

        return `
            <h6 class="text-uppercase text-secondary fw-bold mb-3">
                <i class="bi bi-trophy-fill text-warning me-2"></i>
                Perolehan Tertinggi Sementara
            </h6>

            ${photo}

            <div class="badge text-bg-success mb-2">
                Nomor Urut ${escapeHtml(winner.number)}
            </div>

            <h4 class="fw-bold mb-1">
                ${escapeHtml(winner.name)}
            </h4>

            <div class="fs-2 fw-bold text-success">
                ${escapeHtml(winner.counted_votes)}
            </div>

            <div class="text-secondary">
                suara sah
            </div>
        `;
    }

    function renderGroupedResults(groups) {
        const grid = document.getElementById('groupedResultsGrid');

        if (!grid) {
            return;
        }

        if (!groups.length) {
            grid.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        Belum ada kelompok pemilihan yang tersedia.
                    </div>
                </div>
            `;

            return;
        }

        const hasOddCount = groups.length % 2 === 1;

        grid.innerHTML = groups.map((group, index) => {
            const isCenteredLastCard =
                hasOddCount && index === groups.length - 1;

            const candidatesHtml = (group.candidates ?? []).length
                ? group.candidates
                    .map((candidate) => renderCandidateResultItem(candidate))
                    .join('')
                : `
                    <div class="text-center py-4 text-secondary">
                        Belum ada kandidat dalam kelompok ini.
                    </div>
                `;

            return `
                <div
                    class="col-12 col-xl-6 ${isCenteredLastCard ? 'mx-auto' : ''}"
                    id="election-group-card-${escapeHtml(group.id)}"
                >
                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-header bg-white py-3">
                            <div class="d-flex flex-column flex-md-row justify-content-between gap-2">
                                <div>
                                    <h5 class="fw-bold mb-1">
                                        ${escapeHtml(group.name)}
                                    </h5>

                                    <div class="text-secondary">
                                        <i class="bi bi-geo-alt-fill text-success me-1"></i>
                                        ${escapeHtml(group.dusun_text)}
                                    </div>
                                </div>

                                <div class="text-md-end">
                                    <span class="badge text-bg-light border">
                                        ${escapeHtml(group.counted_ballots)} suara dihitung
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">

                            <div class="border rounded-4 p-4 text-center mb-4 group-winner">
                                ${renderGroupedWinner(group.temporary_winner)}
                            </div>

                            <div>
                                <h6 class="fw-bold mb-3">
                                    Perolehan Suara Sementara
                                </h6>

                                <div class="group-candidate-results">
                                    ${candidatesHtml}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    function showResultMode(candidateScope) {
        const generalContainer =
            document.getElementById('generalResultsContainer');

        const groupedContainer =
            document.getElementById('groupedResultsContainer');

        const isGrouped = candidateScope === 'grouped';

        generalContainer?.classList.toggle('d-none', isGrouped);
        groupedContainer?.classList.toggle('d-none', !isGrouped);
    }

    async function refreshDashboard() {
        try {
            const response = await fetch(dashboardLiveUrl, {
                headers: {
                    'Accept': 'application/json'
                },
                cache: 'no-store'
            });

            if (!response.ok) {
                throw new Error('Gagal mengambil data dashboard.');
            }

            const data = await response.json();

            setText('totalVoters', data.total_voters);
            setText('voted', data.voted);
            setText('notVoted', data.not_voted);
            setText('ballots', data.ballots);
            setText('counted', data.counted);
            setText('uncounted', data.uncounted);
            setText(
                'participationPercentage',
                data.participation_percentage
            );

            showResultMode(data.candidate_scope);

            if (data.candidate_scope === 'grouped') {
                renderGroupedResults(data.groups ?? []);
            } else {
                renderTemporaryWinner(data.temporary_winner);
                renderCandidateResults(data.candidates ?? []);
            }
        } catch (error) {
            console.error(error);
        }
    }

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

                if (badge) {
                    badge.className =
                        `badge text-bg-${display.color} booth-status`;

                    badge.textContent = display.label;
                }

                if (iconBox) {
                    iconBox.className =
                        `rounded-3 bg-${display.color}-subtle ` +
                        `text-${display.color} p-3 booth-icon`;

                    iconBox.innerHTML =
                        `<i class="bi ${display.icon} fs-4"></i>`;
                }

                if (voterBox) {
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
                }
            });
        } catch (error) {
            console.error(error);
        }
    }

    refreshDashboard();
    refreshBoothMonitor();

    setInterval(refreshDashboard, 3000);
    setInterval(refreshBoothMonitor, 2000);
</script>

@endsection