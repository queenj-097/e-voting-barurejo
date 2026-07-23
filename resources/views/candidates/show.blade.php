<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Kandidat</title>

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

        .candidate-number {
            width: 90px;
            height: 90px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
            border-radius: 50%;
            background-color: #0d6efd;
            color: white;
            font-size: 36px;
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

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">

                    <div class="text-center mb-4">

                        @if ($candidate->photo)
                            <img
                                src="{{ asset('storage/' . $candidate->photo) }}"
                                alt="Foto {{ $candidate->name }}"
                                width="180"
                                height="180"
                                class="rounded-circle border border-3 mb-3"
                                style="object-fit: cover;"
                            >
                        @else
                            <div
                                class="rounded-circle bg-secondary-subtle d-flex align-items-center justify-content-center mx-auto mb-3"
                                style="width:180px;height:180px;"
                            >
                                Belum ada foto
                            </div>
                        @endif

                        <div class="candidate-number">
                            {{ $candidate->number }}
                        </div>

                        <h1 class="fw-bold mt-3 mb-1">
                            {{ $candidate->name }}
                        </h1>

                        <p class="text-secondary">
                            Kandidat Nomor Urut {{ $candidate->number }}
                        </p>

                    </div>

                    <hr>

                    <div class="mb-4">
                        <h5 class="fw-bold">Visi</h5>

                        <p class="text-secondary mb-0">
                            {{ $candidate->vision ?: 'Visi belum ditambahkan.' }}
                        </p>
                    </div>

                    <div class="mb-4">
                        <h5 class="fw-bold">Misi</h5>

                        <p class="text-secondary mb-0">
                            {{ $candidate->mission ?: 'Misi belum ditambahkan.' }}
                        </p>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a
                            href="{{ route('candidates.index') }}"
                            class="btn btn-light border"
                        >
                            Kembali
                        </a>

                        <a
                            href="{{ route('candidates.edit', $candidate) }}"
                            class="btn btn-warning"
                        >
                            Edit Kandidat
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>