@extends('layouts.admin')

@section('title', 'Aktivitas Sistem')

@section('content')

<style>
    .activity-actions {
        min-width: 105px;
    }

    .activity-description {
        min-width: 280px;
    }

    .delete-all-box {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px;
    }

    .pagination {
        margin-bottom: 0;
    }

    .pagination svg {
        width: 16px !important;
        height: 16px !important;
    }
</style>

<div class="container-fluid">

    <div
        class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4"
    >
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

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Tutup"
            ></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle-fill me-2"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Tutup"
            ></button>
        </div>
    @endif

    <div class="row g-3 mb-4">

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <small class="text-secondary">
                        Total Aktivitas
                    </small>

                    <h2 class="fw-bold text-success mt-2 mb-0">
                        {{ $total }}
                    </h2>

                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <small class="text-secondary">
                        Aktivitas Hari Ini
                    </small>

                    <h2 class="fw-bold text-primary mt-2 mb-0">
                        {{ $today }}
                    </h2>

                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">

                <div class="card-body d-flex align-items-center">

                    <i
                        class="bi bi-info-circle-fill text-success fs-1 me-3"
                    ></i>

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

    <div class="card border-0 shadow-sm mb-3">

        <div class="card-body">

            <form
                action="{{ route('activity-logs.index') }}"
                method="GET"
                class="row g-3"
            >

                <div class="col-md-6">

                    <div class="input-group">

                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>

                        <input
                            type="text"
                            name="keyword"
                            class="form-control"
                            placeholder="Cari aktivitas atau nama petugas..."
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

                        @foreach ([
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
                                @selected(request('action') === $action)
                            >
                                {{ $action }}
                            </option>

                        @endforeach
                    </select>

                </div>

                <div class="col-md-2">

                    <button
                        type="submit"
                        class="btn btn-success w-100"
                    >
                        <i class="bi bi-funnel me-1"></i>
                        Filter
                    </button>

                </div>

            </form>

        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">

        <div class="card-body py-3">

            <div class="delete-all-box">

                <form
                    action="{{ route('activity-logs.destroy-all') }}"
                    method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus seluruh riwayat aktivitas? Data yang dihapus tidak dapat dikembalikan.')"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-danger"
                        @disabled($total === 0)
                    >
                        <i class="bi bi-trash3-fill me-1"></i>
                        Hapus Semua Aktivitas
                    </button>
                </form>

                <small class="text-secondary">
                    Seluruh catatan aktivitas akan dihapus secara permanen.
                </small>

            </div>

        </div>
    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0">
                <i class="bi bi-list-ul me-2 text-success"></i>
                Riwayat Aktivitas
            </h5>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>
                        <th class="ps-4">
                            <i class="bi bi-clock me-1"></i>
                            Waktu
                        </th>

                        <th>
                            <i class="bi bi-person-fill me-1"></i>
                            Petugas
                        </th>

                        <th>
                            <i class="bi bi-tag-fill me-1"></i>
                            Aktivitas
                        </th>

                        <th class="activity-description">
                            <i class="bi bi-file-text-fill me-1"></i>
                            Deskripsi
                        </th>

                        <th class="text-center activity-actions">
                            Aksi
                        </th>
                    </tr>

                </thead>

                <tbody>

                    @forelse ($logs as $log)

                        @php
                            $badge = match ($log->action) {
                                'Login' => 'success',
                                'Logout' => 'dark',
                                'Voting' => 'primary',
                                'Verifikasi' => 'warning',
                                'Reset Pemilu' => 'danger',
                                'Status Bilik' => 'secondary',
                                default => 'secondary',
                            };
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

                                @if ($log->action === 'Scan QR')

                                    <span
                                        class="badge"
                                        style="background: #6f42c1;"
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

                            <td class="text-center">

                                <form
                                    action="{{ route('activity-logs.destroy', $log) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus aktivitas ini?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                    >
                                        <i class="bi bi-trash me-1"></i>
                                        Hapus
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="text-center py-5"
                            >
                                <i
                                    class="bi bi-inbox fs-1 text-secondary"
                                ></i>

                                <h5 class="mt-3">
                                    Belum ada aktivitas
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

        @if ($logs->hasPages())

            <div class="card-footer bg-white">

                {{ $logs->links('pagination::bootstrap-5') }}

            </div>
            
        @endif

    </div>

</div>

@endsection