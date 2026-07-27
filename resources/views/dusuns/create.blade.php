@extends('layouts.admin')

@section('title', 'Tambah Dusun')

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="mb-4">
                <h1 class="page-heading mb-1">
                    Tambah Dusun
                </h1>

                <p class="text-secondary mb-0">
                    Tambahkan wilayah atau kelompok pemilihan baru.
                </p>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    <form
                        action="{{ route('dusuns.store') }}"
                        method="POST"
                    >
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Nama Dusun
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="form-control form-control-lg
                                    @error('name') is-invalid @enderror"
                                placeholder="Contoh: Dusun Krajan"
                                autofocus
                                required
                            >

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a
                                href="{{ route('dusuns.index') }}"
                                class="btn btn-light border"
                            >
                                Batal
                            </a>

                            <button
                                type="submit"
                                class="btn btn-success px-4"
                            >
                                <i class="bi bi-save-fill me-1"></i>
                                Simpan Dusun
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

@endsection