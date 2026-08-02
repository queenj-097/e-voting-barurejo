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
                    ID DPT akan dibuat otomatis oleh sistem.
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
                            <label
                                for="name"
                                class="form-label fw-semibold"
                            >
                                Nama Lengkap
                            </label>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Nama lengkap pemilih"
                                autofocus
                                required
                            >

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label
                                for="gender"
                                class="form-label fw-semibold"
                            >
                                Jenis Kelamin
                            </label>

                            <select
                                name="gender"
                                id="gender"
                                class="form-select @error('gender') is-invalid @enderror"
                                required
                            >
                                <option value="">
                                    Pilih Jenis Kelamin
                                </option>

                                <option
                                    value="L"
                                    @selected(old('gender') === 'L')
                                >
                                    Laki-laki
                                </option>

                                <option
                                    value="P"
                                    @selected(old('gender') === 'P')
                                >
                                    Perempuan
                                </option>
                            </select>

                            @error('gender')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label
                                for="dusun_id"
                                class="form-label fw-semibold"
                            >
                                Dusun
                            </label>

                            <select
                                name="dusun_id"
                                id="dusun_id"
                                class="form-select @error('dusun_id') is-invalid @enderror"
                                required
                            >
                                <option value="">
                                    Pilih Dusun
                                </option>

                                @foreach ($dusuns as $dusun)
                                    <option
                                        value="{{ $dusun->id }}"
                                        @selected(old('dusun_id') == $dusun->id)
                                    >
                                        {{ $dusun->name }}
                                        @if ($dusun->code)
                                            ({{ $dusun->code }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>

                            @error('dusun_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label
                                    for="rw"
                                    class="form-label fw-semibold"
                                >
                                    RW
                                </label>

                                <input
                                    type="number"
                                    name="rw"
                                    id="rw"
                                    value="{{ old('rw') }}"
                                    class="form-control @error('rw') is-invalid @enderror"
                                    placeholder="Contoh: 1"
                                    min="1"
                                    max="999"
                                    inputmode="numeric"
                                    required
                                >

                                @error('rw')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <small class="text-secondary">
                                    Sistem akan menyimpan RW 1 sebagai 01.
                                </small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label
                                    for="rt"
                                    class="form-label fw-semibold"
                                >
                                    RT
                                </label>

                                <input
                                    type="number"
                                    name="rt"
                                    id="rt"
                                    value="{{ old('rt') }}"
                                    class="form-control @error('rt') is-invalid @enderror"
                                    placeholder="Contoh: 1"
                                    min="1"
                                    max="999"
                                    inputmode="numeric"
                                    required
                                >

                                @error('rt')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <small class="text-secondary">
                                    Sistem akan menyimpan RT 1 sebagai 01.
                                </small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label
                                for="nik"
                                class="form-label fw-semibold"
                            >
                                NIK
                                <span class="text-secondary fw-normal">
                                    (Opsional)
                                </span>
                            </label>

                            <input
                                type="text"
                                name="nik"
                                id="nik"
                                value="{{ old('nik') }}"
                                class="form-control @error('nik') is-invalid @enderror"
                                placeholder="Masukkan 16 digit NIK jika tersedia"
                                maxlength="16"
                                inputmode="numeric"
                                pattern="[0-9]{16}"
                            >

                            @error('nik')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <small class="text-secondary">
                                Kosongkan jika NIK tidak tersedia.
                            </small>
                        </div>

                        <div class="alert alert-info">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-info-circle me-2 mt-1"></i>

                                <div>
                                    <strong>
                                        ID DPT dibuat otomatis
                                    </strong>

                                    <div class="mt-1">
                                        Format ID DPT:
                                        <code>KODE DUSUN-RW-RT-NOMOR URUT</code>
                                    </div>

                                    <div>
                                        Contoh:
                                        <code>KRJ-01-01-001</code>
                                    </div>
                                </div>
                            </div>
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