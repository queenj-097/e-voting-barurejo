@extends('layouts.admin')

@section('title', 'Pengaturan Pemilihan')

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="mb-4">
                <h1 class="page-heading">
                    Pengaturan Pemilihan
                </h1>

                <p class="text-secondary mb-0">
                    Atur identitas dan status kegiatan pemungutan suara.
                </p>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                    ></button>
                </div>
            @endif

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

            {{-- Form pengaturan --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-gear-fill text-success me-2"></i>
                        Informasi Kegiatan
                    </h5>
                </div>

                <div class="card-body p-4">

                    <form
                        action="{{ route('settings.update') }}"
                        method="POST"
                    >
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Nama Kegiatan
                            </label>

                            <input
                                type="text"
                                name="title"
                                value="{{ old('title', $setting->title) }}"
                                class="form-control"
                                placeholder="Contoh: Pemilihan Kepala Desa Barurejo"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Instansi/Penyelenggara
                            </label>

                            <input
                                type="text"
                                name="institution"
                                value="{{ old('institution', $setting->institution) }}"
                                class="form-control"
                                placeholder="Contoh: Pemerintah Desa Barurejo"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Lokasi
                            </label>

                            <input
                                type="text"
                                name="location"
                                value="{{ old('location', $setting->location) }}"
                                class="form-control"
                                placeholder="Contoh: Balai Desa Barurejo"
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Tanggal Pemungutan
                            </label>

                            <input
                                type="date"
                                name="election_date"
                                value="{{ old(
                                    'election_date',
                                    optional($setting->election_date)->format('Y-m-d')
                                ) }}"
                                class="form-control"
                            >
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Status
                            </label>

                            <select
                                name="status"
                                class="form-select"
                                required
                            >
                                <option
                                    value="persiapan"
                                    @selected(
                                        old('status', $setting->status)
                                        === 'persiapan'
                                    )
                                >
                                    Persiapan
                                </option>

                                <option
                                    value="berlangsung"
                                    @selected(
                                        old('status', $setting->status)
                                        === 'berlangsung'
                                    )
                                >
                                    Berlangsung
                                </option>

                                <option
                                    value="selesai"
                                    @selected(
                                        old('status', $setting->status)
                                        === 'selesai'
                                    )
                                >
                                    Selesai
                                </option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button
                                type="submit"
                                class="btn btn-primary px-4"
                            >
                                <i class="bi bi-save me-1"></i>
                                Simpan Pengaturan
                            </button>
                        </div>

                    </form>

                </div>
            </div>

            {{-- Zona berbahaya --}}
            <div class="card border-danger shadow-sm mt-4">
                <div class="card-header bg-danger-subtle border-danger py-3">
                    <h5 class="text-danger fw-bold mb-0">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Zona Berbahaya
                    </h5>
                </div>

                <div class="card-body p-4">

                    <h5 class="fw-bold">
                        Reset Data Pemilihan
                    </h5>

                    <p class="text-secondary">
                        Tindakan ini akan menghapus seluruh surat suara dan
                        mengembalikan status semua pemilih serta bilik ke kondisi awal.
                        Data kandidat dan data pemilih tetap tersimpan.
                    </p>

                    <div class="alert alert-warning">
                        <strong>Data yang akan direset:</strong>

                        <ul class="mb-0 mt-2">
                            <li>Seluruh surat suara dan token QR</li>
                            <li>Status sudah memilih seluruh pemilih</li>
                            <li>Waktu pemungutan suara</li>
                            <li>Status seluruh bilik</li>
                            <li>Hasil rekapitulasi suara</li>
                        </ul>
                    </div>

                    <button
                        type="button"
                        class="btn btn-outline-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#resetElectionModal"
                    >
                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                        Reset Data Pemilihan
                    </button>

                </div>
            </div>

            {{-- Modal konfirmasi reset --}}
            <div
                class="modal fade"
                id="resetElectionModal"
                tabindex="-1"
                aria-labelledby="resetElectionModalLabel"
                aria-hidden="true"
            >
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">

                        <div class="modal-header border-0 pb-0">
                            <h5
                                class="modal-title fw-bold text-danger"
                                id="resetElectionModalLabel"
                            >
                                <i class="bi bi-exclamation-octagon-fill me-2"></i>
                                Konfirmasi Reset Pemilihan
                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Tutup"
                            ></button>
                        </div>

                        <div class="modal-body p-4">

                            <div class="alert alert-danger">
                                <strong>Perhatian!</strong>
                                Tindakan ini tidak dapat dibatalkan. Seluruh surat suara
                                dan hasil penghitungan akan dihapus secara permanen.
                            </div>

                            <p class="mb-2">
                                Untuk melanjutkan, ketik:
                            </p>

                            <div
                                class="border rounded-3 bg-light text-danger fw-bold
                                    text-center py-3 mb-3"
                            >
                                RESET PEMILIHAN
                            </div>

                            <label
                                for="resetConfirmationInput"
                                class="form-label fw-semibold"
                            >
                                Konfirmasi
                            </label>

                            <input
                                type="text"
                                id="resetConfirmationInput"
                                class="form-control"
                                placeholder="Ketik RESET PEMILIHAN"
                                autocomplete="off"
                            >

                            <small
                                id="resetConfirmationHelp"
                                class="text-secondary"
                            >
                                Tombol reset akan aktif setelah teks sesuai.
                            </small>

                        </div>

                        <div class="modal-footer border-0 pt-0">

                            <button
                                type="button"
                                class="btn btn-light border"
                                data-bs-dismiss="modal"
                            >
                                Batal
                            </button>

                            <form
                                action="{{ route('settings.reset-election') }}"
                                method="POST"
                                id="resetElectionForm"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-danger"
                                    id="resetElectionButton"
                                    disabled
                                >
                                    <i class="bi bi-trash3-fill me-1"></i>
                                    Ya, Reset Pemilihan
                                </button>
                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const confirmationInput =
            document.getElementById('resetConfirmationInput');

        const resetButton =
            document.getElementById('resetElectionButton');

        const resetModal =
            document.getElementById('resetElectionModal');

        const requiredText = 'RESET PEMILIHAN';

        if (confirmationInput && resetButton) {
            confirmationInput.addEventListener('input', function () {
                const isValid =
                    confirmationInput.value.trim() === requiredText;

                resetButton.disabled = !isValid;

                confirmationInput.classList.toggle(
                    'is-valid',
                    isValid
                );

                confirmationInput.classList.toggle(
                    'is-invalid',
                    confirmationInput.value.length > 0 && !isValid
                );
            });
        }

        if (resetModal) {
            resetModal.addEventListener('hidden.bs.modal', function () {
                confirmationInput.value = '';
                confirmationInput.classList.remove(
                    'is-valid',
                    'is-invalid'
                );

                resetButton.disabled = true;
            });

            resetModal.addEventListener('shown.bs.modal', function () {
                confirmationInput.focus();
            });
        }
    });
</script>   
    
@endsection