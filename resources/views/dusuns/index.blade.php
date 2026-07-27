@extends('layouts.admin')

@section('title', 'Data Dusun')

@section('content')

<div class="container-fluid">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="page-heading mb-1">
                Data Dusun
            </h1>

            <p class="text-secondary mb-0">
                Kelola wilayah atau kelompok pemilihan yang digunakan pada data DPT.
            </p>
        </div>

        <a
            href="{{ route('dusuns.create') }}"
            class="btn btn-success mt-3 mt-md-0"
        >
            <i class="bi bi-plus-circle-fill me-1"></i>
            Tambah Dusun
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
            ></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-geo-alt-fill text-success me-2"></i>
                Daftar Dusun
            </h5>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 80px;">No.</th>
                        <th>Nama Dusun</th>
                        <th class="text-center">Jumlah DPT</th>
                        <th class="text-end pe-4" style="width: 190px;">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($dusuns as $dusun)
                        <tr>
                            <td class="ps-4">
                                {{ $dusuns->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $dusun->name }}
                                </div>
                            </td>

                            <td class="text-center">
                                <span class="badge text-bg-light border">
                                    {{ $dusun->voters_count }} pemilih
                                </span>
                            </td>

                            <td class="text-end pe-4">
                                <a
                                    href="{{ route('dusuns.edit', $dusun) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    <i class="bi bi-pencil-square"></i>
                                    Edit
                                </a>

                                <form
                                    action="{{ route('dusuns.destroy', $dusun) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm(
                                        'Hapus dusun {{ addslashes($dusun->name) }}?'
                                    )"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                    >
                                        <i class="bi bi-trash3"></i>
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="4"
                                class="text-center text-secondary py-5"
                            >
                                <i class="bi bi-geo-alt display-5 d-block mb-3"></i>
                                Belum ada data dusun.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        @if ($dusuns->hasPages())
            <div class="card-footer bg-white">
                {{ $dusuns->links() }}
            </div>
        @endif
    </div>

</div>

@endsection