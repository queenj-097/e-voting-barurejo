@extends('layouts.admin')

@section('title', 'Edit Dusun')

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="mb-4">
                <h1 class="page-heading mb-1">
                    Edit Dusun
                </h1>

                <p class="text-secondary mb-0">
                    Perbarui nama wilayah atau kelompok pemilihan.
                </p>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    <form
                        action="{{ route('dusuns.update', $dusun) }}"
                        method="POST"
                    >
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Nama Dusun
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $dusun->name) }}"
                                class="form-control form-control-lg
                                    @error('name') is-invalid @enderror"
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
                                class="btn btn-primary px-4"
                            >
                                <i class="bi bi-save-fill me-1"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

@endsection