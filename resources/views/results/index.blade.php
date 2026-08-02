@extends('layouts.admin')

@section('title', 'Hasil Suara')

@section('content')

<div class="container-fluid">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="page-heading mb-1">
                Rekapitulasi
            </h1>

            <p class="text-secondary mb-0">
                Rekap hasil pemungutan suara elektronik.
            </p>
        </div>

        <a
            href="{{ route('results.pdf') }}"
            class="btn btn-danger mt-3 mt-md-0"
        >
            <i class="bi bi-file-earmark-pdf-fill me-1"></i>
            Download PDF
        </a>
    </div>

    <div class="row g-4 mb-4">

        <div class="col-md-4">
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

        <div class="col-md-4">
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

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <p class="text-secondary mb-2">
                        Belum Dihitung
                    </p>

                    <h2 class="fw-bold text-warning mb-0">
                        {{ $uncountedBallots }}
                    </h2>
                </div>
            </div>
        </div>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                Perolehan Suara Kandidat per Dusun
            </h5>
        </div>

        <div class="card-body">

            @forelse ($candidatesByDusun as $dusunName => $dusunData)

                <div class="border rounded-4 p-4 mb-4">

                    <div
                        class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4"
                    >
                        <div>
                            <h4 class="fw-bold mb-1">
                                Dusun {{ $dusunName }}
                            </h4>

                            <div class="text-secondary">
                                Total suara sah:
                                <strong>
                                    {{ $dusunData['total_votes'] }}
                                </strong>
                            </div>
                        </div>
                    </div>

                    @forelse ($dusunData['candidates'] as $candidate)

                        <div class="mb-4">

                            <div
                                class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-2 gap-2"
                            >
                                <div>
                                    <strong>
                                        No. {{ $candidate->number }}
                                        —
                                        {{ $candidate->name }}
                                    </strong>
                                </div>

                                <div class="text-md-end">
                                    <strong>
                                        {{ $candidate->counted_votes }}
                                    </strong>
                                    suara

                                    <span class="text-secondary ms-1">
                                        ({{ number_format(
                                            $candidate->percentage,
                                            2,
                                            ',',
                                            '.'
                                        ) }}%)
                                    </span>
                                </div>
                            </div>

                            <div
                                class="progress"
                                style="height: 24px;"
                            >
                                <div
                                    class="progress-bar bg-success"
                                    role="progressbar"
                                    style="width: {{ min(
                                        max($candidate->percentage, 0),
                                        100
                                    ) }}%;"
                                    aria-valuenow="{{ $candidate->percentage }}"
                                    aria-valuemin="0"
                                    aria-valuemax="100"
                                >
                                    {{ number_format(
                                        $candidate->percentage,
                                        2,
                                        ',',
                                        '.'
                                    ) }}%
                                </div>
                            </div>

                        </div>

                    @empty

                        <div class="text-center text-secondary py-3">
                            Belum ada kandidat pada dusun ini.
                        </div>

                    @endforelse

                </div>

            @empty

                <div class="text-center text-secondary py-5">
                    <i class="bi bi-bar-chart fs-1 d-block mb-2"></i>
                    Belum ada kandidat.
                </div>

            @endforelse

        </div>
    </div>

</div>

@endsection