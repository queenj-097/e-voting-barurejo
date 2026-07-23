@extends('layouts.admin')

@section('title', 'Data Kandidat')

@section('content')

<div class="container py-5">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="page-title mb-1">Data Kandidat</h1>
            <p class="text-secondary mb-0">
                Kelola data kandidat yang mengikuti pemilihan.
            </p>
        </div>

        <a
            href="{{ route('candidates.create') }}"
            class="btn btn-primary mt-3 mt-md-0 px-4"
        >
            + Tambah Kandidat
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
            <h5 class="mb-0">Daftar Kandidat</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No. Urut</th>
                            <th>Foto</th>
                            <th>Nama Kandidat</th>
                            <th>Visi</th>
                            <th>Misi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($candidates as $candidate)
                            <tr>
                                <td class="ps-4">
                                    <span class="candidate-number">
                                        {{ $candidate->number }}
                                    </span>
                                </td>

                                <td>
                                    @if ($candidate->photo)
                                        <img
                                            src="{{ asset('storage/' . $candidate->photo) }}"
                                            alt="Foto {{ $candidate->name }}"
                                            width="72"
                                            height="72"
                                            class="rounded-circle border"
                                            style="object-fit: cover;"
                                        >
                                    @else
                                        <div
                                            class="rounded-circle bg-secondary-subtle d-flex align-items-center justify-content-center text-secondary fw-semibold"
                                            style="width: 72px; height: 72px;"
                                        >
                                            Foto
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <strong>{{ $candidate->name }}</strong>
                                </td>

                                <td>
                                    {{ $candidate->vision ?: '-' }}
                                </td>

                                <td>
                                    {{ $candidate->mission ?: '-' }}
                                </td>

                                <td class="text-center">
                                    <a
                                        href="{{ route('candidates.show', $candidate) }}"
                                        class="btn btn-sm btn-outline-info"
                                    >
                                        Detail
                                    </a>

                                    <a
                                        href="{{ route('candidates.edit', $candidate) }}"
                                        class="btn btn-sm btn-outline-warning"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('candidates.destroy', $candidate) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus kandidat ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                        >
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
                                    Belum ada data kandidat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection