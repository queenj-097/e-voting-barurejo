@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-clock-history text-success me-2"></i>
                Aktivitas Sistem
            </h2>

            <p class="text-secondary mb-0">
                Monitor seluruh aktivitas aplikasi E-Voting Desa Barurejo.
            </p>
        </div>

        <a
            href="{{ route('activity-logs.index') }}"
            class="btn btn-outline-success mt-3 mt-md-0"
        >
            <i class="bi bi-arrow-clockwise me-1"></i>
            Refresh
        </a>

    </div>


    <div class="row g-3 mb-4">

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small class="text-secondary">
                        Total Aktivitas
                    </small>

                    <h2 class="fw-bold text-success mt-2">
                        {{ $total }}
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small class="text-secondary">
                        Aktivitas Hari Ini
                    </small>

                    <h2 class="fw-bold text-primary mt-2">
                        {{ $today }}
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body d-flex align-items-center">

                    <i class="bi bi-info-circle-fill text-success fs-1 me-3"></i>

                    <div>

                        <div class="fw-semibold">
                            Audit Log Aktif
                        </div>

                        <small class="text-secondary">
                            Seluruh aktivitas sistem akan tercatat otomatis.
                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form class="row g-3">

                <div class="col-md-6">

                    <div class="input-group">

                        <span class="input-group-text bg-white">

                            <i class="bi bi-search"></i>

                        </span>

                        <input
                            type="text"
                            name="keyword"
                            class="form-control"
                            placeholder="Cari aktivitas..."
                            value="{{ request('keyword') }}"
                        >

                    </div>

                </div>

                <div class="col-md-4">

                    <select
                        name="action"
                        class="form-select"
                    >

                        <option value="">
                            Semua Aktivitas
                        </option>

                        @foreach([
                            'Login',
                            'Logout',
                            'Voting',
                            'Verifikasi',
                            'Scan QR',
                            'Status Bilik',
                            'Reset Pemilu'
                        ] as $action)

                            <option
                                value="{{ $action }}"
                                @selected(request('action')==$action)
                            >
                                {{ $action }}
                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-2">

                    <button class="btn btn-success w-100">

                        Filter

                    </button>

                </div>

            </form>

        </div>

    </div>


    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">

                <i class="bi bi-list-ul me-2 text-success"></i>

                Riwayat Aktivitas

            </h5>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th class="ps-4">🕒 Waktu</th>

                        <th>👤 Petugas</th>

                        <th>🏷 Aktivitas</th>

                        <th>📝 Deskripsi</th>

                    </tr>

                </thead>

                <tbody>

                @forelse($logs as $log)

                    @php

                        $badge='secondary';

                        switch($log->action){

                            case 'Login':
                                $badge='success';
                                break;

                            case 'Logout':
                                $badge='dark';
                                break;

                            case 'Voting':
                                $badge='primary';
                                break;

                            case 'Scan QR':
                                $badge='purple';
                                break;

                            case 'Verifikasi':
                                $badge='warning';
                                break;

                            case 'Reset Pemilu':
                                $badge='danger';
                                break;

                        }

                    @endphp

                    <tr>

                        <td class="ps-4">

                            {{ $log->created_at->format('d M Y') }}

                            <br>

                            <small class="text-secondary">

                                {{ $log->created_at->format('H:i:s') }}

                            </small>

                        </td>

                        <td>

                            <div class="fw-semibold">

                                {{ $log->user?->name ?? 'System' }}

                            </div>

                            <small class="text-secondary">

                                {{ ucfirst($log->user?->role ?? '-') }}

                            </small>

                        </td>

                        <td>

                            @if($badge=='purple')

                                <span
                                    class="badge"
                                    style="background:#6f42c1;"
                                >

                                    {{ $log->action }}

                                </span>

                            @else

                                <span class="badge bg-{{ $badge }}">

                                    {{ $log->action }}

                                </span>

                            @endif

                        </td>

                        <td>

                            {{ $log->description }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="4"
                            class="text-center py-5"
                        >

                            <i
                                class="bi bi-inbox fs-1 text-secondary"
                            ></i>

                            <h5 class="mt-3">

                                Belum ada aktivitas.

                            </h5>

                            <p class="text-secondary mb-0">

                                Aktivitas akan muncul setelah sistem digunakan.

                            </p>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        @if($logs->hasPages())

            <div class="card-footer bg-white">

                {{ $logs->links() }}

            </div>

        @endif

    </div>

</div>

@endsection