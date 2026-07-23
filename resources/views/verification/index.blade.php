@extends('layouts.admin')

@section('title', 'Verifikasi Pemilih')

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="mb-4">
                <h1 class="page-heading">Verifikasi Pemilih</h1>

                <p class="text-secondary mb-0">
                    Masukkan NIK atau nomor DPT untuk memeriksa hak pilih.
                </p>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">

                    <div class="text-center mb-4">
                        <div
                            class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center mx-auto mb-3"
                            style="width: 82px; height: 82px;"
                        >
                            <i class="bi bi-person-check-fill fs-1"></i>
                        </div>

                        <h4 class="fw-bold">Pemeriksaan Data DPT</h4>

                        <p class="text-secondary">
                            Pastikan data sesuai dengan identitas pemilih.
                        </p>
                    </div>

                    <form
                        action="{{ route('verification.verify') }}"
                        method="POST"
                    >
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                NIK atau Nomor DPT
                            </label>

                            <input
                                type="text"
                                name="identity"
                                value="{{ old('identity') }}"
                                class="form-control form-control-lg"
                                placeholder="Masukkan NIK atau nomor DPT"
                                autofocus
                                required
                            >
                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary btn-lg w-100"
                        >
                            <i class="bi bi-search me-1"></i>
                            Periksa Data Pemilih
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@if (session('assignment'))

    @php
        $assignment = session('assignment');
    @endphp

    <div
        class="modal fade"
        id="assignmentModal"
        tabindex="-1"
        aria-labelledby="assignmentModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">

                <div class="modal-body text-center p-4 p-md-5">

                    <div
                        class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center mx-auto mb-3"
                        style="width: 90px; height: 90px;"
                    >
                        <i class="bi bi-check-lg fs-1"></i>
                    </div>

                    <h2
                        class="fw-bold mb-2"
                        id="assignmentModalLabel"
                    >
                        Berhasil Dikirim
                    </h2>

                    <p class="text-secondary mb-4">
                        Silakan arahkan pemilih menuju bilik yang telah ditentukan.
                    </p>

                    <div class="border rounded-4 p-4 text-start mb-4">

                        <div class="mb-3">
                            <small class="text-secondary d-block">
                                Nama Pemilih
                            </small>

                            <div class="fw-bold fs-5">
                                {{ $assignment['voter_name'] }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <small class="text-secondary d-block">
                                Nomor DPT
                            </small>

                            <div class="fw-semibold">
                                {{ $assignment['dpt_number'] }}
                            </div>
                        </div>

                        <div>
                            <small class="text-secondary d-block">
                                Tujuan Bilik
                            </small>

                            <div class="fw-bold text-success fs-4">
                                <i class="bi bi-display me-1"></i>
                                {{ $assignment['booth_name'] }}
                            </div>
                        </div>

                    </div>

                    <button
                        type="button"
                        class="btn btn-success btn-lg w-100"
                        data-bs-dismiss="modal"
                    >
                        Selesai
                    </button>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modalElement =
                    document.getElementById('assignmentModal');

                if (modalElement) {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                }
            });
        </script>
    @endpush

@endif

@endsection