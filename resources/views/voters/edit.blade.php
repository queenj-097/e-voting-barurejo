@extends('layouts.admin')

@section('title', 'Edit DPT')

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="mb-4">
                <h1 class="page-heading">
                    Edit Data DPT
                </h1>

                <p class="text-secondary mb-0">
                    Perbarui data pemilih tetap Desa Barurejo.
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
                        action="{{ route('voters.update', $voter) }}"
                        method="POST"
                    >
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label
                                for="dpt_number"
                                class="form-label fw-semibold"
                            >
                                Nomor DPT
                            </label>

                            <input
                                type="text"
                                name="dpt_number"
                                id="dpt_number"
                                value="{{ old('dpt_number', $voter->dpt_number) }}"
                                class="form-control @error('dpt_number') is-invalid @enderror"
                                required
                            >

                            @error('dpt_number')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label
                                for="nik"
                                class="form-label fw-semibold"
                            >
                                NIK
                            </label>

                            <input
                                type="text"
                                name="nik"
                                id="nik"
                                value="{{ old('nik', $voter->nik) }}"
                                class="form-control @error('nik') is-invalid @enderror"
                                maxlength="16"
                                inputmode="numeric"
                                required
                            >

                            @error('nik')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

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
                                value="{{ old('name', $voter->name) }}"
                                class="form-control @error('name') is-invalid @enderror"
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
                                        @selected(
                                            old('dusun_id', $voter->dusun_id) == $dusun->id
                                        )
                                    >
                                        {{ $dusun->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('dusun_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label
                                for="address"
                                class="form-label fw-semibold"
                            >
                                Alamat
                            </label>

                            <textarea
                                name="address"
                                id="address"
                                class="form-control @error('address') is-invalid @enderror"
                                rows="4"
                            >{{ old('address', $voter->address) }}</textarea>

                            @error('address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">

                            <a
                                href="{{ route('voters.index') }}"
                                class="btn btn-light border"
                            >
                                Batal
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary px-4"
                            >
                                <i class="bi bi-save me-1"></i>
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