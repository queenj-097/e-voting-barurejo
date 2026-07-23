@extends('layouts.admin')

@section('title', 'Data DPT')

@section('content')

<div class="container-fluid">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="page-heading">Data DPT</h1>

            <p class="text-secondary mb-0">
                Kelola data pemilih tetap Desa Barurejo.
            </p>
        </div>

        <a
            href="{{ route('voters.create') }}"
            class="btn btn-primary mt-3 mt-md-0 px-4"
        >
            <i class="bi bi-plus-lg me-1"></i>
            Tambah DPT
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

                <h5 class="mb-0">
                    Daftar Pemilih Tetap
                </h5>

                <form
                    action="{{ route('voters.index') }}"
                    method="GET"
                    class="d-flex"
                >
                    <div class="input-group">
                        <input
                            type="text"
                            name="search"
                            value="{{ $search ?? '' }}"
                            class="form-control"
                            placeholder="Cari NIK, nama, atau nomor DPT"
                        >

                        <button
                            type="submit"
                            class="btn btn-outline-primary"
                        >
                            <i class="bi bi-search"></i>
                        </button>

                        @if (!empty($search))
                            <a
                                href="{{ route('voters.index') }}"
                                class="btn btn-outline-secondary"
                            >
                                Reset
                            </a>
                        @endif
                    </div>
                </form>

            </div>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No. DPT</th>
                            <th>NIK</th>
                            <th>Nama Pemilih</th>
                            <th>Alamat</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($voters as $voter)

                            <tr>
                                <td class="ps-4">
                                    <strong>
                                        {{ $voter->dpt_number }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $voter->nik }}
                                </td>

                                <td>
                                    {{ $voter->name }}
                                </td>

                                <td>
                                    {{ $voter->address ?: '-' }}
                                </td>

                                <td>
                                    @if ($voter->has_voted)
                                        <span class="badge rounded-pill text-bg-success">
                                            Sudah Memilih
                                        </span>
                                    @else
                                        <span class="badge rounded-pill text-bg-secondary">
                                            Belum Memilih
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">

                                    <a
                                        href="{{ route('voters.show', $voter) }}"
                                        class="btn btn-sm btn-outline-info"
                                    >
                                        <i class="bi bi-eye"></i>
                                        Detail
                                    </a>

                                    <a
                                        href="{{ route('voters.edit', $voter) }}"
                                        class="btn btn-sm btn-outline-warning"
                                    >
                                        <i class="bi bi-pencil-square"></i>
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('voters.destroy', $voter) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus data DPT ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                        >
                                            <i class="bi bi-trash"></i>
                                            Hapus
                                        </button>
                                    </form>

                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="6"
                                    class="text-center py-5 text-secondary"
                                >
                                    <i class="bi bi-people fs-1 d-block mb-2"></i>
                                    Belum ada data DPT.
                                </td>
                            </tr>

                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>

        @if ($voters->hasPages())
            <div class="card-footer bg-white">
                {{ $voters->links() }}
            </div>
        @endif

    </div>

</div>

@endsection