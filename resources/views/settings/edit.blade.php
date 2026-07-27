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
                        aria-label="Tutup"
                    ></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>
                        Data belum dapat diproses.
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
                            <label
                                for="title"
                                class="form-label fw-semibold"
                            >
                                Nama Kegiatan
                            </label>

                            <input
                                type="text"
                                name="title"
                                id="title"
                                value="{{ old('title', $setting->title) }}"
                                class="form-control @error('title') is-invalid @enderror"
                                placeholder="Contoh: Pemilihan Kepala Desa Barurejo"
                                required
                            >

                            @error('title')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label
                                for="institution"
                                class="form-label fw-semibold"
                            >
                                Instansi/Penyelenggara
                            </label>

                            <input
                                type="text"
                                name="institution"
                                id="institution"
                                value="{{ old('institution', $setting->institution) }}"
                                class="form-control @error('institution') is-invalid @enderror"
                                placeholder="Contoh: Pemerintah Desa Barurejo"
                                required
                            >

                            @error('institution')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label
                                for="location"
                                class="form-label fw-semibold"
                            >
                                Lokasi
                            </label>

                            <input
                                type="text"
                                name="location"
                                id="location"
                                value="{{ old('location', $setting->location) }}"
                                class="form-control @error('location') is-invalid @enderror"
                                placeholder="Contoh: Balai Desa Barurejo"
                            >

                            @error('location')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label
                                for="election_date"
                                class="form-label fw-semibold"
                            >
                                Tanggal Pemungutan
                            </label>

                            <input
                                type="date"
                                name="election_date"
                                id="election_date"
                                value="{{ old(
                                    'election_date',
                                    optional($setting->election_date)->format('Y-m-d')
                                ) }}"
                                class="form-control @error('election_date') is-invalid @enderror"
                            >

                            @error('election_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label
                                for="status"
                                class="form-label fw-semibold"
                            >
                                Status
                            </label>

                            <select
                                name="status"
                                id="status"
                                class="form-select @error('status') is-invalid @enderror"
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

                            @error('status')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Mode Kandidat
                            </label>

                            <p class="text-secondary small mb-3">
                                Tentukan apakah seluruh pemilih melihat kandidat
                                yang sama atau kandidat dibedakan berdasarkan dusun.
                            </p>

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label
                                        class="border rounded-3 p-3 w-100 h-100"
                                        for="candidateScopeGeneral"
                                        style="cursor: pointer;"
                                    >
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="radio"
                                                name="candidate_scope"
                                                id="candidateScopeGeneral"
                                                value="general"
                                                @checked(
                                                    old(
                                                        'candidate_scope',
                                                        $setting->candidate_scope ?? 'general'
                                                    ) === 'general'
                                                )
                                            >

                                            <span class="form-check-label fw-semibold">
                                                Kandidat Sama untuk Semua
                                            </span>
                                        </div>

                                        <small class="text-secondary d-block mt-2">
                                            Cocok untuk Pilkades atau pemilihan
                                            yang kandidatnya sama bagi seluruh pemilih.
                                        </small>
                                    </label>
                                </div>

                                <div class="col-md-6">
                                    <label
                                        class="border rounded-3 p-3 w-100 h-100"
                                        for="candidateScopeGrouped"
                                        style="cursor: pointer;"
                                    >
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="radio"
                                                name="candidate_scope"
                                                id="candidateScopeGrouped"
                                                value="grouped"
                                                @checked(
                                                    old(
                                                        'candidate_scope',
                                                        $setting->candidate_scope ?? 'general'
                                                    ) === 'grouped'
                                                )
                                            >

                                            <span class="form-check-label fw-semibold">
                                                Kandidat Berdasarkan Dusun
                                            </span>
                                        </div>

                                        <small class="text-secondary d-block mt-2">
                                            Setiap pemilih hanya melihat kandidat
                                            yang terhubung dengan dusunnya.
                                        </small>
                                    </label>
                                </div>

                            </div>

                            @error('candidate_scope')
                                <div class="text-danger small mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
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

            {{-- Backup database --}}
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-database-fill-down text-primary me-2"></i>
                        Backup Database
                    </h5>
                </div>

                <div class="card-body p-4">

                    <h5 class="fw-bold">
                        Unduh Salinan Database
                    </h5>

                    <p class="text-secondary mb-3">
                        Unduh salinan seluruh data aplikasi, termasuk DPT,
                        kandidat, dusun, surat suara, pengaturan, pengguna,
                        dan riwayat aktivitas.
                    </p>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Simpan file backup di tempat yang aman sebelum melakukan
                        reset atau perubahan besar pada sistem.
                    </div>

                    <a
                        href="{{ route('settings.database-backup') }}"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-download me-1"></i>
                        Download Backup Database
                    </a>

                </div>
            </div>

            {{-- Reset sistem --}}
            <div class="card border-danger shadow-sm mt-4">
                <div class="card-header bg-danger-subtle border-danger py-3">
                    <h5 class="text-danger fw-bold mb-0">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Reset Sistem
                    </h5>
                </div>

                <div class="card-body p-4">

                    {{-- Reset aktivasi --}}
                    <div class="pb-4 border-bottom">
                        <h5 class="fw-bold">
                            Reset Aktivasi
                        </h5>

                        <p class="text-secondary mb-3">
                            Menghapus seluruh riwayat aktivasi pemilih dan
                            mengembalikan semua bilik ke kondisi siap digunakan.
                            Data DPT, kandidat, dusun, surat suara, dan hasil
                            pemilihan tidak dihapus.
                        </p>

                        <button
                            type="button"
                            class="btn btn-outline-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#resetActivationsModal"
                        >
                            <i class="bi bi-arrow-repeat me-1"></i>
                            Reset Aktivasi
                        </button>
                    </div>

                    {{-- Reset pemilihan --}}
                    <div class="py-4 border-bottom">
                        <h5 class="fw-bold">
                            Reset Data Pemilihan
                        </h5>

                        <p class="text-secondary mb-3">
                            Menghapus seluruh surat suara dan hasil penghitungan,
                            mengembalikan semua pemilih menjadi belum memilih,
                            serta mengosongkan seluruh bilik. Data DPT, kandidat,
                            dusun, dan pengaturan tetap disimpan.
                        </p>

                        <button
                            type="button"
                            class="btn btn-outline-warning"
                            data-bs-toggle="modal"
                            data-bs-target="#resetElectionModal"
                        >
                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                            Reset Data Pemilihan
                        </button>
                    </div>

                    {{-- Reset seluruh sistem --}}
                    <div class="pt-4">
                        <h5 class="fw-bold text-danger">
                            Reset Seluruh Sistem
                        </h5>

                        <p class="text-secondary mb-3">
                            Menghapus seluruh DPT, kandidat, dusun, surat suara,
                            hasil penghitungan, pengaturan pemilihan, aktivasi,
                            dan riwayat aktivitas. Akun admin, verifikator,
                            scanner, serta perangkat bilik tetap dipertahankan.
                        </p>

                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-octagon-fill me-2"></i>
                            <strong>Tindakan ini tidak dapat dibatalkan.</strong>
                            Pastikan data penting telah dicadangkan.
                        </div>

                        <button
                            type="button"
                            class="btn btn-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#resetSystemModal"
                        >
                            <i class="bi bi-trash3-fill me-1"></i>
                            Reset Seluruh Sistem
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

{{-- Modal reset aktivasi --}}
<div
    class="modal fade"
    id="resetActivationsModal"
    tabindex="-1"
    aria-labelledby="resetActivationsModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header">
                <h5
                    class="modal-title fw-bold text-primary"
                    id="resetActivationsModalLabel"
                >
                    <i class="bi bi-arrow-repeat me-2"></i>
                    Konfirmasi Reset Aktivasi
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Tutup"
                ></button>
            </div>

            <div class="modal-body">
                <p class="mb-0">
                    Seluruh riwayat aktivasi akan dihapus dan semua bilik
                    dikembalikan ke kondisi siap digunakan. Data DPT,
                    kandidat, serta suara tidak akan dihapus.
                </p>
            </div>

            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-light border"
                    data-bs-dismiss="modal"
                >
                    Batal
                </button>

                <form
                    action="{{ route('settings.reset-activations') }}"
                    method="POST"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-arrow-repeat me-1"></i>
                        Ya, Reset Aktivasi
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

{{-- Modal reset data pemilihan --}}
<div
    class="modal fade"
    id="resetElectionModal"
    tabindex="-1"
    aria-labelledby="resetElectionModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header">
                <h5
                    class="modal-title fw-bold text-warning"
                    id="resetElectionModalLabel"
                >
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Konfirmasi Reset Pemilihan
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Tutup"
                ></button>
            </div>

            <div class="modal-body">

                <div class="alert alert-warning">
                    Seluruh surat suara dan hasil penghitungan akan dihapus.
                    Data DPT, kandidat, dusun, dan pengaturan tetap tersimpan.
                </div>

                <p class="mb-2">
                    Untuk melanjutkan, ketik:
                </p>

                <div
                    class="border rounded-3 bg-light fw-bold text-center
                           py-3 mb-3"
                >
                    RESET PEMILIHAN
                </div>

                <label
                    for="resetElectionInput"
                    class="form-label fw-semibold"
                >
                    Konfirmasi
                </label>

                <input
                    type="text"
                    id="resetElectionInput"
                    class="form-control"
                    placeholder="Ketik RESET PEMILIHAN"
                    autocomplete="off"
                >

            </div>

            <div class="modal-footer">
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
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-warning"
                        id="resetElectionButton"
                        disabled
                    >
                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                        Ya, Reset Pemilihan
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

{{-- Modal reset seluruh sistem --}}
<div
    class="modal fade"
    id="resetSystemModal"
    tabindex="-1"
    aria-labelledby="resetSystemModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header">
                <h5
                    class="modal-title fw-bold text-danger"
                    id="resetSystemModalLabel"
                >
                    <i class="bi bi-exclamation-octagon-fill me-2"></i>
                    Konfirmasi Reset Seluruh Sistem
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Tutup"
                ></button>
            </div>

            <div class="modal-body">

                <div class="alert alert-danger">
                    <strong>Perhatian!</strong>
                    Seluruh data DPT, kandidat, dusun, surat suara,
                    pengaturan, dan riwayat aktivitas akan dihapus permanen.
                </div>

                <p class="mb-2">
                    Untuk melanjutkan, ketik:
                </p>

                <div
                    class="border rounded-3 bg-light text-danger fw-bold
                           text-center py-3 mb-3"
                >
                    RESET SELURUH SISTEM
                </div>

                <label
                    for="resetSystemInput"
                    class="form-label fw-semibold"
                >
                    Konfirmasi
                </label>

                <input
                    type="text"
                    name="reset_confirmation"
                    id="resetSystemInput"
                    form="resetSystemForm"
                    class="form-control"
                    placeholder="Ketik RESET SELURUH SISTEM"
                    autocomplete="off"
                >

                <small class="text-secondary">
                    Tombol reset akan aktif setelah teks sesuai.
                </small>

            </div>

            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-light border"
                    data-bs-dismiss="modal"
                >
                    Batal
                </button>

                <form
                    action="{{ route('settings.reset-system') }}"
                    method="POST"
                    id="resetSystemForm"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-danger"
                        id="resetSystemButton"
                        disabled
                    >
                        <i class="bi bi-trash3-fill me-1"></i>
                        Ya, Reset Seluruh Sistem
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        setupTypedConfirmation({
            modalId: 'resetElectionModal',
            inputId: 'resetElectionInput',
            buttonId: 'resetElectionButton',
            requiredText: 'RESET PEMILIHAN'
        });

        setupTypedConfirmation({
            modalId: 'resetSystemModal',
            inputId: 'resetSystemInput',
            buttonId: 'resetSystemButton',
            requiredText: 'RESET SELURUH SISTEM'
        });
    });

    function setupTypedConfirmation(config) {
        const modal = document.getElementById(config.modalId);
        const input = document.getElementById(config.inputId);
        const button = document.getElementById(config.buttonId);

        if (!modal || !input || !button) {
            return;
        }

        function resetConfirmationState() {
            input.value = '';
            input.classList.remove('is-valid', 'is-invalid');
            button.disabled = true;
        }

        input.addEventListener('input', function () {
            const value = input.value.trim();
            const isValid = value === config.requiredText;

            button.disabled = !isValid;

            input.classList.toggle('is-valid', isValid);

            input.classList.toggle(
                'is-invalid',
                value.length > 0 && !isValid
            );
        });

        modal.addEventListener('shown.bs.modal', function () {
            input.focus();
        });

        modal.addEventListener('hidden.bs.modal', function () {
            resetConfirmationState();
        });
    }
</script>

@endsection