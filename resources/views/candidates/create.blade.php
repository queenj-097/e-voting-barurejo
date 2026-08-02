<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Kandidat</title>

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
                <h1 class="fw-bold mb-1">Tambah Kandidat</h1>
                <p class="text-secondary mb-0">
                    Masukkan data kandidat.
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
                        action="{{ route('candidates.store') }}"
                        method="POST"
                        enctype="multipart/form-data"
                    >
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Nomor Urut
                            </label>

                            <input
                                type="number"
                                name="number"
                                value="{{ old('number') }}"
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
                                value="{{ old('name') }}"
                                class="form-control"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Foto Kandidat
                            </label>

                            <input
                                type="file"
                                name="photo"
                                class="form-control"
                                accept=".jpg,.jpeg,.png"
                            >

                            <small class="text-secondary">
                                Format JPG, JPEG, atau PNG. Maksimal 10 MB.
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
                            >{{ old('vision') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Misi
                            </label>

                            <textarea
                                name="mission"
                                class="form-control"
                                rows="5"
                            >{{ old('mission') }}</textarea>
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
                                                @checked(
                                                    in_array(
                                                        $dusun->id,
                                                        old('dusun_ids', [])
                                                    )
                                                )
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
                                Kembali
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary px-4"
                            >
                                Simpan Kandidat
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