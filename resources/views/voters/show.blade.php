@extends('layouts.admin')

@section('title', 'Detail DPT')

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="mb-4">
                <h1 class="page-heading">
                    Detail Data DPT
                </h1>

                <p class="text-secondary mb-0">
                    Informasi pemilih tetap Desa Barurejo.
                </p>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">

                    <div class="d-flex align-items-center gap-3 mb-4">

                        <div
                            class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center"
                            style="width: 72px; height: 72px;"
                        >
                            <i class="bi bi-person-fill fs-2"></i>
                        </div>

                        <div>
                            <h2 class="fw-bold mb-1">
                                {{ $voter->name }}
                            </h2>

                            <p class="text-secondary mb-0">
                                Nomor DPT: {{ $voter->dpt_number }}
                            </p>
                        </div>

                    </div>

                    <hr>

                    <div class="row g-4 mt-1">

                        <div class="col-md-6">
                            <small class="text-secondary d-block mb-1">
                                Nomor DPT
                            </small>

                            <div class="fw-semibold">
                                {{ $voter->dpt_number }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-secondary d-block mb-1">
                                NIK
                            </small>

                            <div class="fw-semibold">
                                {{ $voter->nik }}
                            </div>
                        </div>

                        <div class="col-md-12">
                            <small class="text-secondary d-block mb-1">
                                Alamat
                            </small>

                            <div class="fw-semibold">
                                {{ $voter->address ?: '-' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-secondary d-block mb-1">
                                Status Pemilihan
                            </small>

                            @if ($voter->has_voted)
                                <span class="badge rounded-pill text-bg-success">
                                    Sudah Memilih
                                </span>
                            @else
                                <span class="badge rounded-pill text-bg-secondary">
                                    Belum Memilih
                                </span>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <small class="text-secondary d-block mb-1">
                                Waktu Memilih
                            </small>

                            <div class="fw-semibold">
                                {{ $voter->voted_at
                                    ? $voter->voted_at->format('d-m-Y H:i')
                                    : '-' }}
                            </div>
                        </div>

                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-5">

                        <a
                            href="{{ route('voters.index') }}"
                            class="btn btn-light border"
                        >
                            <i class="bi bi-arrow-left me-1"></i>
                            Kembali
                        </a>

                        <a
                            href="{{ route('voters.edit', $voter) }}"
                            class="btn btn-warning"
                        >
                            <i class="bi bi-pencil-square me-1"></i>
                            Edit Data
                        </a>

                    </div>

                </div>
            </div>

        </div>
    </div>

</div>

@endsection