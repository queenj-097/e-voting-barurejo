<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Kandidat</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background-color: #f4f6f9;
        }

        .navbar-brand {
            font-weight: 700;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a
            class="navbar-brand"
            href="{{ route('candidates.index') }}"
        >
            E-Voting Desa Barurejo
        </a>
    </div>
</nav>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="mb-4">
                <h1 class="fw-bold mb-1">Edit Kandidat</h1>

                <p class="text-secondary mb-0">
                    Perbarui data calon kepala desa.
                </p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Data belum dapat disimpan.</strong>

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
                        @php
                            $selectedDusuns = old(
                                'dusun_ids',
                                $candidate->dusuns->pluck('id')->toArray()
                            );
                        @endphp

                        action="{{ route('candidates.update', $candidate) }}"
                        method="POST"
                        enctype="multipart/form-data"
                    >
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Nomor Urut
                            </label>

                            <input
                                type="number"
                                name="number"
                                value="{{ old('number', $candidate->number) }}"
                                class="form-control"
                                min="1"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Nama Kandidat
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $candidate->name) }}"
                                class="form-control"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Foto Kandidat
                            </label>

                            @if ($candidate->photo)
                                <div class="mb-2">
                                    <img
                                        src="{{ asset('storage/' . $candidate->photo) }}"
                                        alt="Foto {{ $candidate->name }}"
                                        width="120"
                                        class="rounded border"
                                    >
                                </div>
                            @endif

                            <input
                                type="file"
                                name="photo"
                                class="form-control"
                                accept=".jpg,.jpeg,.png"
                            >

                            <small class="text-secondary">
                                Kosongkan kalau foto tidak ingin diganti.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Visi
                            </label>

                            <textarea
                                name="vision"
                                class="form-control"
                                rows="4"
                            >{{ old('vision', $candidate->vision) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Misi
                            </label>

                            <textarea
                                name="mission"
                                class="form-control"
                                rows="5"
                            >{{ old('mission', $candidate->mission) }}</textarea>
                        </div>

                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Dusun Kandidat
                            </label>

                            <p class="text-secondary small mb-3">
                                Pilih dusun yang dapat memilih kandidat ini.
                            </p>

                            <div class="row">

                                @forelse ($dusuns as $dusun)

                                    <div class="col-md-6 mb-2">

                                        <div class="form-check border rounded p-3">

                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="dusun_ids[]"
                                                value="{{ $dusun->id }}"
                                                id="dusun{{ $dusun->id }}"
                                                @checked(in_array($dusun->id, $selectedDusuns))
                                            >

                                            <label
                                                class="form-check-label fw-semibold"
                                                for="dusun{{ $dusun->id }}"
                                            >
                                                {{ $dusun->name }}
                                            </label>

                                        </div>

                                    </div>

                                @empty

                                    <div class="col-12">
                                        <div class="alert alert-warning mb-0">
                                            Belum ada data dusun.
                                        </div>
                                    </div>

                                @endforelse

                            </div>

                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a
                                href="{{ route('candidates.index') }}"
                                class="btn btn-light border"
                            >
                                Batal
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary px-4"
                            >
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>