@extends('layouts.admin')

@section('title', 'Hasil Verifikasi')

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">

                    <div class="text-center mb-4">
                        <div
                            class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center mx-auto mb-3"
                            style="width: 82px; height: 82px;"
                        >
                            <i class="bi bi-check-lg fs-1"></i>
                        </div>

                        <h2 class="fw-bold">
                            Pemilih Terverifikasi
                        </h2>

                        <p class="text-secondary mb-0">
                            Data ditemukan dan pemilih belum menggunakan hak pilih.
                        </p>
                    </div>

                    <div class="border rounded-3 p-4 mb-4">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <small class="text-secondary">
                                    Nomor DPT
                                </small>

                                <div class="fw-semibold">
                                    {{ $voter->dpt_number }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-secondary">
                                    NIK
                                </small>

                                <div class="fw-semibold">
                                    {{ $voter->nik }}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <small class="text-secondary">
                                    Nama
                                </small>

                                <div class="fw-semibold">
                                    {{ $voter->name }}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <small class="text-secondary">
                                    Alamat
                                </small>

                                <div class="fw-semibold">
                                    {{ $voter->address ?: '-' }}
                                </div>
                            </div>

                        </div>
                    </div>

                    <form
                        action="{{ route('verification.assign-booth') }}"
                        method="POST"
                    >
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Pilih Bilik
                            </label>

                            <select
                                name="booth_id"
                                class="form-select form-select-lg"
                                required
                            >
                                <option value="">
                                    -- Pilih bilik yang tersedia --
                                </option>

                                @foreach ($booths as $booth)
                                    <option
                                        value="{{ $booth->id }}"
                                        @disabled(!$booth->isAvailable())
                                    >
                                        {{ $booth->name }}
                                        —
                                        @if ($booth->isAvailable())
                                            Tersedia
                                        @elseif ($booth->status === 'assigned')
                                            Menunggu pemilih
                                        @elseif ($booth->status === 'voting')
                                            Sedang digunakan
                                        @else
                                            Tidak aktif
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-2">

                            <a
                                href="{{ route('verification.index') }}"
                                class="btn btn-light border"
                            >
                                Batal
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary px-4"
                            >
                                <i class="bi bi-send-fill me-1"></i>
                                Kirim ke Bilik
                            </button>

                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

@endsection