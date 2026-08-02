@extends('layouts.admin')

@section('title', 'Verifikasi Pemilih')

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="mb-4">
                <h1 class="page-heading">
                    Verifikasi Pemilih
                </h1>

                <p class="text-secondary mb-0">
                    Scan QR pemilih atau masukkan kode pemilih secara manual.
                </p>
            </div>

            @if (session('success') && !session('assignment'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">

                    <div class="text-center mb-4">

                        <div
                            class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center mx-auto mb-3"
                            style="width: 90px; height: 90px;"
                        >
                            <i class="bi bi-qr-code-scan fs-1"></i>
                        </div>

                        <h3 class="fw-bold">
                            Scan QR Pemilih
                        </h3>

                        <p class="text-secondary mb-0">
                            Arahkan scanner ke QR pada kartu pemilih.
                            Scanner akan mengisi kode secara otomatis.
                        </p>

                    </div>

                    <form
                        id="verificationForm"
                        action="{{ route('verification.verify') }}"
                        method="POST"
                    >
                        @csrf

                        <div class="mb-4">

                            <label
                                for="identity"
                                class="form-label fw-semibold"
                            >
                                Kode Pemilih / NIK
                            </label>

                            <input
                                type="text"
                                id="identity"
                                name="identity"
                                value="{{ old('identity') }}"
                                class="form-control form-control-lg text-center fw-bold"
                                placeholder="Scan QR atau ketik kode pemilih"
                                autocomplete="off"
                                autofocus
                                required
                            >

                            <div class="form-text">
                                Contoh:
                                <strong>KRJ-01-01-001</strong>
                            </div>

                        </div>

                        <button
                            class="btn btn-primary btn-lg w-100"
                            type="submit"
                            id="verificationButton"
                        >
                            <i class="bi bi-search me-1"></i>
                            Verifikasi Pemilih
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>


{{-- POPUP KONFIRMASI PEMILIH --}}

@if (session('verified_voter'))

@php
    $voter = session('verified_voter');
@endphp

<div
    class="modal fade"
    id="verifiedVoterModal"
    tabindex="-1"
    aria-labelledby="verifiedVoterModalLabel"
    aria-hidden="true"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
>
    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content border-0 shadow-lg rounded-4">

            <div class="modal-body p-4 p-md-5">

                <div class="text-center mb-4">

                    <div
                        class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center mx-auto mb-3"
                        style="width: 90px; height: 90px;"
                    >
                        <i class="bi bi-check-lg fs-1"></i>
                    </div>

                    <h2
                        class="fw-bold mb-2"
                        id="verifiedVoterModalLabel"
                    >
                        Pemilih Terverifikasi
                    </h2>

                    <p class="text-secondary mb-0">
                        Data pemilih ditemukan dan belum menggunakan hak pilih.
                    </p>

                </div>

                <div class="border rounded-4 p-4 mb-4">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <small class="text-secondary d-block mb-1">
                                Kode Pemilih
                            </small>

                            <div class="fw-bold fs-5">
                                {{ $voter['voter_code'] }}
                            </div>

                        </div>

                        <div class="col-md-6">

                            <small class="text-secondary d-block mb-1">
                                NIK
                            </small>

                            <div class="fw-semibold">
                                {{ $voter['nik'] ?: '-' }}
                            </div>

                        </div>

                        <div class="col-12">

                            <small class="text-secondary d-block mb-1">
                                Nama Pemilih
                            </small>

                            <div class="fw-bold fs-4">
                                {{ $voter['name'] }}
                            </div>

                        </div>

                        <div class="col-md-6">

                            <small class="text-secondary d-block mb-1">
                                Dusun
                            </small>

                            <div class="fw-semibold">
                                {{ $voter['dusun'] }}
                            </div>

                        </div>

                        <div class="col-6 col-md-3">

                            <small class="text-secondary d-block mb-1">
                                RW
                            </small>

                            <div class="fw-semibold">
                                {{ $voter['rw'] }}
                            </div>

                        </div>

                        <div class="col-6 col-md-3">

                            <small class="text-secondary d-block mb-1">
                                RT
                            </small>

                            <div class="fw-semibold">
                                {{ $voter['rt'] }}
                            </div>

                        </div>

                    </div>

                </div>

                @if ($voter['available_booth_count'] > 0)

                    <div class="alert alert-success mb-4">

                        <div class="d-flex align-items-start">

                            <i class="bi bi-display me-2 mt-1"></i>

                            <div>
                                Terdapat
                                <strong>
                                    {{ $voter['available_booth_count'] }} bilik
                                </strong>
                                yang tersedia.

                                Sistem akan memilih bilik kosong secara otomatis.
                            </div>

                        </div>

                    </div>

                @else

                    <div class="alert alert-warning mb-4">

                        <div class="d-flex align-items-start">

                            <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>

                            <div>
                                Saat ini belum ada bilik yang tersedia.

                                Tunggu hingga bilik selesai digunakan, lalu scan
                                ulang QR pemilih.
                            </div>

                        </div>

                    </div>

                @endif

                <div class="d-flex flex-column flex-md-row justify-content-end gap-2">

                    <form
                        action="{{ route('verification.cancel') }}"
                        method="POST"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="btn btn-outline-secondary btn-lg w-100"
                        >
                            Batal
                        </button>
                    </form>

                    @if ($voter['available_booth_count'] > 0)

                        <form
                            action="{{ route('verification.assign') }}"
                            method="POST"
                            id="assignToBoothForm"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="btn btn-success btn-lg w-100"
                                id="assignToBoothButton"
                            >
                                <i class="bi bi-send-fill me-2"></i>
                                Masukkan ke Bilik
                            </button>
                        </form>

                    @endif

                </div>

            </div>

        </div>

    </div>
</div>

@endif


{{-- POPUP BERHASIL DIKIRIM KE BILIK --}}

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
    data-bs-backdrop="static"
    data-bs-keyboard="false"
>
    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg rounded-4">

            <div class="modal-body text-center p-4 p-md-5">

                <div
                    class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center mx-auto mb-3"
                    style="width: 90px; height: 90px;"
                >
                    <i class="bi bi-check-lg fs-1"></i>
                </div>

                <h2
                    class="fw-bold"
                    id="assignmentModalLabel"
                >
                    Berhasil
                </h2>

                <p class="text-secondary">
                    Pemilih sudah dikirim ke bilik.
                </p>

                <div class="border rounded-4 p-4 text-start">

                    <div class="mb-3">

                        <small class="text-secondary d-block mb-1">
                            Nama Pemilih
                        </small>

                        <div class="fw-bold fs-5">
                            {{ $assignment['voter_name'] }}
                        </div>

                    </div>

                    <div class="mb-3">

                        <small class="text-secondary d-block mb-1">
                            Kode Pemilih
                        </small>

                        <div class="fw-semibold">
                            {{ $assignment['voter_code'] }}
                        </div>

                    </div>

                    <div>

                        <small class="text-secondary d-block mb-1">
                            Tujuan Bilik
                        </small>

                        <div class="fw-bold fs-3 text-success">
                            {{ $assignment['booth_name'] }}
                        </div>

                    </div>

                </div>

                <button
                    type="button"
                    class="btn btn-success btn-lg w-100 mt-4"
                    data-bs-dismiss="modal"
                    id="finishAssignmentButton"
                >
                    Selesai
                </button>

            </div>

        </div>

    </div>
</div>

@endif

@endsection


@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('identity');
    const form = document.getElementById('verificationForm');
    const verificationButton = document.getElementById(
        'verificationButton'
    );

    let scannerTimer;
    let formSubmitted = false;

    function submitVerificationForm() {
        const identity = input.value.trim();

        if (identity === '' || formSubmitted) {
            return;
        }

        formSubmitted = true;

        verificationButton.disabled = true;
        verificationButton.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2"></span>' +
            'Memverifikasi...';

        form.submit();
    }

    input.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            submitVerificationForm();
        }
    });

    input.addEventListener('input', function () {
        clearTimeout(scannerTimer);

        scannerTimer = setTimeout(function () {
            if (input.value.trim().length >= 5) {
                submitVerificationForm();
            }
        }, 300);
    });

    @if (!session('verified_voter') && !session('assignment'))
        input.focus();
    @endif
});


@if (session('verified_voter'))

document.addEventListener('DOMContentLoaded', function () {
    const modalElement = document.getElementById(
        'verifiedVoterModal'
    );

    const modal = new bootstrap.Modal(modalElement);

    modal.show();

    const assignForm = document.getElementById(
        'assignToBoothForm'
    );

    const assignButton = document.getElementById(
        'assignToBoothButton'
    );

    if (assignForm && assignButton) {
        assignForm.addEventListener('submit', function () {
            assignButton.disabled = true;

            assignButton.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2"></span>' +
                'Mengirim ke Bilik...';
        });
    }
});

@endif


@if (session('assignment'))

document.addEventListener('DOMContentLoaded', function () {
    const modalElement = document.getElementById(
        'assignmentModal'
    );

    const modal = new bootstrap.Modal(modalElement);

    modal.show();

    modalElement.addEventListener('hidden.bs.modal', function () {
        const input = document.getElementById('identity');

        input.value = '';
        input.focus();
    });
});

@endif
</script>

@endpush