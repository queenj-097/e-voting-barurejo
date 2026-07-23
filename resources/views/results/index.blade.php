@extends('layouts.admin')

@section('title', 'Hasil Suara')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h1 class="page-heading">Hasil Suara</h1>
        <p class="text-secondary mb-0">
            Rekap hasil pemungutan suara elektronik Desa Barurejo.
        </p>
    </div>

    <div class="row g-4 mb-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <p class="text-secondary mb-2">Total Surat Suara</p>
                    <h2 class="fw-bold mb-0">{{ $totalBallots }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <p class="text-secondary mb-2">Sudah Dihitung</p>
                    <h2 class="fw-bold text-success mb-0">
                        {{ $countedBallots }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <p class="text-secondary mb-2">Belum Dihitung</p>
                    <h2 class="fw-bold text-warning mb-0">
                        {{ $uncountedBallots }}
                    </h2>
                </div>
            </div>
        </div>

    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Perolehan Suara Kandidat</h5>
        </div>

        <div class="card-body">

            @forelse ($candidates as $candidate)

                @php
                    $percentage = $countedBallots > 0
                        ? round(($candidate->counted_votes / $countedBallots) * 100, 1)
                        : 0;
                @endphp

                <div class="mb-4">

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <strong>
                                No. {{ $candidate->number }} — {{ $candidate->name }}
                            </strong>
                        </div>

                        <div>
                            <strong>{{ $candidate->counted_votes }}</strong>
                            suara
                        </div>
                    </div>

                    <div class="progress" style="height: 24px;">
                        <div
                            class="progress-bar bg-success"
                            role="progressbar"
                            style="width: {{ $percentage }}%;"
                        >
                            {{ $percentage }}%
                        </div>
                    </div>

                </div>

            @empty
                <p class="text-center text-secondary mb-0">
                    Belum ada kandidat.
                </p>
            @endforelse

        </div>
    </div>

</div>

@endsection