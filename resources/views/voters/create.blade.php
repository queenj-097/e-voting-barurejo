@extends('layouts.admin')

@section('title', 'Tambah DPT')

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="mb-4">
                <h1 class="page-heading">
                    Tambah Data DPT
                </h1>

                <p class="text-secondary mb-0">
                    Masukkan data pemilih tetap Desa Barurejo.
                </p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>
                        Data belum dapat disimpan.
                    </strong>

                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    <form
                        action="{{ route('voters.store') }}"
                        method="POST"
                    >
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Nomor DPT
                            </label>

                            <input
                                type="text"
                                name="dpt_number"
                                value="{{ old('dpt_number') }}"
                                class="form-control"
                                placeholder="Contoh: DPT-0001"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                NIK
                            </label>

                            <input
                                type="text"
                                name="nik"
                                value="{{ old('nik') }}"
                                class="form-control"
                                placeholder="Masukkan 16 digit NIK"
                                maxlength="16"
                                inputmode="numeric"
                                required
                            >

                            <small class="text-secondary">
                                Masukkan NIK tanpa spasi atau tanda baca.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Nama Lengkap
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="form-control"
                                placeholder="Nama lengkap pemilih"
                                required
                            >
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Alamat
                            </label>

                            <textarea
                                name="address"
                                class="form-control"
                                rows="4"
                                placeholder="Dusun, RT/RW, Desa Barurejo"
                            >{{ old('address') }}</textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">

                            <a
                                href="{{ route('voters.index') }}"
                                class="btn btn-light border"
                            >
                                <i class="bi bi-arrow-left me-1"></i>
                                Kembali
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary px-4"
                            >
                                <i class="bi bi-save me-1"></i>
                                Simpan Data DPT
                            </button>

                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

@endsection