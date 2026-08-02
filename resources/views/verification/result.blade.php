@extends('layouts.admin')

@section('title', 'Hasil Verifikasi')

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            @if (session('error'))
                <div class="alert alert-danger mb-4">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <div class="card border-0 shadow-sm">

                <div class="card-body p-4 p-md-5">

                    <div class="text-center mb-4">

                        <div
                            class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center mx-auto mb-3"
                            style="width: 90px; height: 90px;"
                        >
                            <i class="bi bi-check-lg fs-1"></i>
                        </div>

                        <h2 class="fw-bold">
                            Pemilih Terverifikasi
                        </h2>

                        <p class="text-secondary mb-0">
                            Data pemilih ditemukan dan belum menggunakan hak pilih.
                        </p>

                    </div>

                    <div class="border rounded-4 p-4 mb-4">

                        <div class="row g-4">

                            <div class="col-md-6">

                                <small class="text-secondary">
                                    Kode Pemilih
                                </small>

                                <div class="fw-bold fs-5">
                                    {{ $voter->voter_code }}
                                </div>

                            </div>

                            <div class="col-md-6">

                                <small class="text-secondary">
                                    NIK
                                </small>

                                <div class="fw-semibold">
                                    {{ $voter->nik ?? '-' }}
                                </div>

                            </div>

                            <div class="col-12">

                                <small class="text-secondary">
                                    Nama Pemilih
                                </small>

                                <div class="fw-bold fs-4">
                                    {{ $voter->name }}
                                </div>

                            </div>

                            <div class="col-md-6">

                                <small class="text-secondary">
                                    Dusun
                                </small>

                                <div class="fw-semibold">
                                    {{ $voter->dusun?->name ?? '-' }}
                                </div>

                            </div>

                            <div class="col-md-3">

                                <small class="text-secondary">
                                    RW
                                </small>

                                <div class="fw-semibold">
                                    {{ $voter->rw }}
                                </div>

                            </div>

                            <div class="col-md-3">

                                <small class="text-secondary">
                                    RT
                                </small>

                                <div class="fw-semibold">
                                    {{ $voter->rt }}
                                </div>

                            </div>

                        </div>

                    </div>

                    @if ($availableBoothCount > 0)

                        <div class="alert alert-success mb-4">

                            <i class="bi bi-display me-2"></i>

                            Terdapat
                            <strong>
                                {{ $availableBoothCount }} bilik
                            </strong>
                            yang tersedia.

                            Sistem akan memilih bilik kosong secara otomatis.

                        </div>

                        <form
                            action="{{ route('verification.assign-booth') }}"
                            method="POST"
                        >
                            @csrf

                            <div class="d-flex justify-content-end gap-2">

                                <a
                                    href="{{ route('verification.cancel') }}"
                                    class="btn btn-outline-secondary"
                                >
                                    Batal
                                </a>

                                <button
                                    type="submit"
                                    class="btn btn-success px-4"
                                >
                                    <i class="bi bi-send-fill me-1"></i>
                                    Masukkan ke Bilik
                                </button>

                            </div>

                        </form>

                    @else

                        <div class="alert alert-warning mb-4">

                            <div class="d-flex">

                                <i class="bi bi-hourglass-split fs-4 me-3"></i>

                                <div>
                                    <div class="fw-bold mb-1">
                                        Semua Bilik Sedang Digunakan
                                    </div>

                                    <div>
                                        Silakan tunggu hingga salah satu bilik selesai digunakan.
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="d-flex justify-content-end gap-2">

                            <a
                                href="{{ route('verification.cancel') }}"
                                class="btn btn-outline-secondary"
                            >
                                Batal
                            </a>

                            <a
                                href="{{ route('verification.result') }}"
                                class="btn btn-primary"
                            >
                                <i class="bi bi-arrow-clockwise me-1"></i>
                                Periksa Lagi
                            </a>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection