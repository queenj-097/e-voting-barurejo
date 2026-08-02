@extends('layouts.admin')

@section('title', 'Data DPT')

@section('content')

<style>
    .folder-card {
        border: 1px solid #dfe7e3;
        border-radius: 14px;
        overflow: hidden;
        background: #ffffff;
    }

    .folder-button {
        width: 100%;
        border: 0;
        background: #ffffff;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-align: left;
        color: #203029;
        transition: 0.2s ease;
    }

    .folder-button:hover {
        background: #f3f7f5;
    }

    .folder-button:not(.collapsed) {
        background: #e8f3ee;
        color: #146c43;
    }

    .folder-button::after {
        content: "\F282";
        font-family: "bootstrap-icons";
        font-size: 16px;
        transition: transform 0.2s ease;
    }

    .folder-button:not(.collapsed)::after {
        transform: rotate(180deg);
    }

    .folder-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
    }

    .folder-icon {
        font-size: 22px;
        color: #198754;
    }

    .folder-count {
        font-size: 12px;
        font-weight: 700;
        padding: 5px 10px;
        border-radius: 999px;
        background: #e9ecef;
        color: #495057;
        white-space: nowrap;
    }

    .dusun-folder {
        margin-bottom: 14px;
    }

    .rw-folder {
        margin: 12px 0;
        border-left: 4px solid #198754;
    }

    .rw-folder .folder-button {
        padding: 14px 16px;
    }

    .rt-folder {
        margin: 10px 0;
        border-left: 4px solid #6c757d;
    }

    .rt-folder .folder-button {
        padding: 12px 14px;
        background: #fafcfb;
    }

    .folder-content {
        padding: 14px;
        background: #f8faf9;
    }

    .rw-content {
        padding: 10px 14px 14px;
    }

    .rt-content {
        padding: 0;
        background: white;
    }

    .dpt-table th,
    .dpt-table td {
        white-space: nowrap;
        vertical-align: middle;
    }

    .dpt-actions {
        min-width: 285px;
    }

    .header-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 10px;
    }

    .empty-folder {
        padding: 54px 20px;
        text-align: center;
        color: #6c757d;
    }

    @media (max-width: 768px) {
        .header-actions {
            justify-content: flex-start;
            margin-top: 16px;
        }

        .folder-button {
            padding: 14px;
        }
    }
</style>

<div class="container-fluid">

    <div
        class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center mb-4 gap-3"
    >
        <div>
            <h1 class="page-heading mb-1">
                Data DPT
            </h1>

            <p class="text-secondary mb-0">
                Kelola data pemilih tetap Desa Barurejo.
            </p>
        </div>

        <div class="header-actions">

            <a
                href="{{ route('voters.create') }}"
                class="btn btn-primary px-4"
            >
                <i class="bi bi-plus-lg me-1"></i>
                Tambah DPT
            </a>

            <a
                href="{{ route('voters.import') }}"
                class="btn btn-success"
            >
                <i class="bi bi-file-earmark-excel me-1"></i>
                Import Excel
            </a>

            <a
                href="{{ route('voters.qr.print-all') }}"
                class="btn btn-primary"
            >
                <i class="bi bi-printer me-1"></i>
                Cetak Semua QR
            </a>

            <form
                action="{{ route('voters.destroyAll') }}"
                method="POST"
                onsubmit="return confirm('Yakin ingin menghapus seluruh data DPT? Data tidak dapat dikembalikan.')"
            >
                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="btn btn-danger"
                >
                    <i class="bi bi-trash3 me-1"></i>
                    Hapus Semua DPT
                </button>
            </form>

        </div>
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

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle-fill me-2"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div
                class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3"
            >
                <div>
                    <h5 class="mb-1">
                        Daftar Pemilih Tetap
                    </h5>

                    <div class="small text-secondary">
                        Total {{ $totalVoters ?? 0 }} pemilih
                    </div>
                </div>

                <form
                    action="{{ route('voters.index') }}"
                    method="GET"
                >
                    <div class="input-group">

                        <input
                            type="text"
                            name="search"
                            value="{{ $search ?? '' }}"
                            class="form-control"
                            placeholder="Cari ID DPT, nama, NIK, dusun, RW, atau RT"
                        >

                        <button
                            type="submit"
                            class="btn btn-outline-primary"
                        >
                            <i class="bi bi-search"></i>
                        </button>

                        @if (!empty($search))
                            <a
                                href="{{ route('voters.index') }}"
                                class="btn btn-outline-secondary"
                            >
                                Reset
                            </a>
                        @endif

                    </div>
                </form>

            </div>

        </div>

        <div class="card-body">

            {{-- MODE FOLDER: DUSUN -> RW -> RT --}}

            @if (empty($search))

                @if (
                    isset($groupedVoters)
                    && $groupedVoters
                    && $groupedVoters->isNotEmpty()
                )

                    <div
                        class="accordion"
                        id="dusunAccordion"
                    >

                        @foreach (
                            $groupedVoters
                            as $dusunName => $rwGroups
                        )

                            @php
                                $dusunIndex = $loop->index;

                                $dusunCount = $rwGroups
                                    ->flatMap(
                                        fn ($rtGroups) =>
                                            $rtGroups->flatten()
                                    )
                                    ->count();

                                $dusunId =
                                    'dusun-collapse-' .
                                    $dusunIndex;
                            @endphp

                            <div class="folder-card dusun-folder">

                                <h2 class="m-0">

                                    <button
                                        type="button"
                                        class="folder-button collapsed"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#{{ $dusunId }}"
                                        aria-expanded="false"
                                        aria-controls="{{ $dusunId }}"
                                    >
                                        <span class="folder-title">

                                            <i
                                                class="bi bi-folder-fill folder-icon"
                                            ></i>

                                            Dusun {{ $dusunName }}

                                        </span>

                                        <span class="folder-count">
                                            {{ $dusunCount }} pemilih
                                        </span>
                                    </button>

                                </h2>

                                <div
                                    id="{{ $dusunId }}"
                                    class="collapse"
                                    data-bs-parent="#dusunAccordion"
                                >

                                    <div class="folder-content">

                                        @foreach (
                                            $rwGroups
                                            as $rw => $rtGroups
                                        )

                                            @php
                                                $rwIndex =
                                                    $loop->index;

                                                $rwCount =
                                                    $rtGroups
                                                        ->flatten()
                                                        ->count();

                                                $rwId =
                                                    'rw-collapse-' .
                                                    $dusunIndex .
                                                    '-' .
                                                    $rwIndex;

                                                $rwAccordionId =
                                                    'rw-accordion-' .
                                                    $dusunIndex;
                                            @endphp

                                            <div
                                                class="folder-card rw-folder"
                                            >

                                                <h3 class="m-0">

                                                    <button
                                                        type="button"
                                                        class="folder-button collapsed"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#{{ $rwId }}"
                                                        aria-expanded="false"
                                                        aria-controls="{{ $rwId }}"
                                                    >
                                                        <span
                                                            class="folder-title"
                                                        >

                                                            <i
                                                                class="bi bi-folder2-open folder-icon"
                                                            ></i>

                                                            RW {{ $rw }}

                                                        </span>

                                                        <span
                                                            class="folder-count"
                                                        >
                                                            {{ $rwCount }}
                                                            pemilih
                                                        </span>
                                                    </button>

                                                </h3>

                                                <div
                                                    id="{{ $rwId }}"
                                                    class="collapse"
                                                >

                                                    <div
                                                        class="rw-content"
                                                        id="{{ $rwAccordionId }}"
                                                    >

                                                        @foreach (
                                                            $rtGroups
                                                            as $rt => $rtVoters
                                                        )

                                                            @php
                                                                $rtIndex =
                                                                    $loop->index;

                                                                $rtId =
                                                                    'rt-collapse-' .
                                                                    $dusunIndex .
                                                                    '-' .
                                                                    $rwIndex .
                                                                    '-' .
                                                                    $rtIndex;
                                                            @endphp

                                                            <div
                                                                class="folder-card rt-folder"
                                                            >

                                                                <h4
                                                                    class="m-0"
                                                                >

                                                                    <button
                                                                        type="button"
                                                                        class="folder-button collapsed"
                                                                        data-bs-toggle="collapse"
                                                                        data-bs-target="#{{ $rtId }}"
                                                                        aria-expanded="false"
                                                                        aria-controls="{{ $rtId }}"
                                                                    >
                                                                        <span
                                                                            class="folder-title"
                                                                        >

                                                                            <i
                                                                                class="bi bi-folder2 folder-icon"
                                                                            ></i>

                                                                            RT {{ $rt }}

                                                                        </span>

                                                                        <span
                                                                            class="folder-count"
                                                                        >
                                                                            {{ $rtVoters->count() }}
                                                                            pemilih
                                                                        </span>
                                                                    </button>

                                                                </h4>

                                                                <div
                                                                    id="{{ $rtId }}"
                                                                    class="collapse"
                                                                >

                                                                    <div
                                                                        class="rt-content"
                                                                    >

                                                                        <div
                                                                            class="table-responsive"
                                                                        >

                                                                            <table
                                                                                class="table table-hover align-middle mb-0 dpt-table"
                                                                            >

                                                                                <thead
                                                                                    class="table-light"
                                                                                >
                                                                                    <tr>
                                                                                        <th
                                                                                            class="ps-4"
                                                                                        >
                                                                                            ID DPT
                                                                                        </th>

                                                                                        <th>
                                                                                            Nama
                                                                                        </th>

                                                                                        <th>
                                                                                            JK
                                                                                        </th>

                                                                                        <th>
                                                                                            NIK
                                                                                        </th>

                                                                                        <th>
                                                                                            Status
                                                                                        </th>

                                                                                        <th
                                                                                            class="text-center dpt-actions"
                                                                                        >
                                                                                            Aksi
                                                                                        </th>
                                                                                    </tr>
                                                                                </thead>

                                                                                <tbody>

                                                                                    @foreach (
                                                                                        $rtVoters
                                                                                        as $voter
                                                                                    )

                                                                                        <tr>

                                                                                            <td
                                                                                                class="ps-4"
                                                                                            >
                                                                                                <strong>
                                                                                                    {{ $voter->voter_code }}
                                                                                                </strong>
                                                                                            </td>

                                                                                            <td>
                                                                                                {{ $voter->name }}
                                                                                            </td>

                                                                                            <td>

                                                                                                @if (
                                                                                                    $voter->gender
                                                                                                    === 'L'
                                                                                                )
                                                                                                    <span
                                                                                                        class="badge text-bg-primary"
                                                                                                    >
                                                                                                        L
                                                                                                    </span>
                                                                                                @elseif (
                                                                                                    $voter->gender
                                                                                                    === 'P'
                                                                                                )
                                                                                                    <span
                                                                                                        class="badge text-bg-danger"
                                                                                                    >
                                                                                                        P
                                                                                                    </span>
                                                                                                @else
                                                                                                    -
                                                                                                @endif

                                                                                            </td>

                                                                                            <td>
                                                                                                {{ $voter->nik ?: '-' }}
                                                                                            </td>

                                                                                            <td>

                                                                                                @if (
                                                                                                    $voter->has_voted
                                                                                                )
                                                                                                    <span
                                                                                                        class="badge rounded-pill text-bg-success"
                                                                                                    >
                                                                                                        Sudah Memilih
                                                                                                    </span>
                                                                                                @else
                                                                                                    <span
                                                                                                        class="badge rounded-pill text-bg-secondary"
                                                                                                    >
                                                                                                        Belum Memilih
                                                                                                    </span>
                                                                                                @endif

                                                                                            </td>

                                                                                            <td
                                                                                                class="text-center"
                                                                                            >

                                                                                                <a
                                                                                                    href="{{ route('voters.show', $voter) }}"
                                                                                                    class="btn btn-sm btn-outline-info"
                                                                                                >
                                                                                                    <i
                                                                                                        class="bi bi-eye"
                                                                                                    ></i>
                                                                                                    Detail
                                                                                                </a>

                                                                                                <a
                                                                                                    href="{{ route('voters.qr', $voter) }}"
                                                                                                    class="btn btn-sm btn-success"
                                                                                                >
                                                                                                    <i
                                                                                                        class="bi bi-qr-code"
                                                                                                    ></i>
                                                                                                    QR
                                                                                                </a>

                                                                                                @unless (
                                                                                                    $voter->has_voted
                                                                                                )

                                                                                                    <a
                                                                                                        href="{{ route('voters.edit', $voter) }}"
                                                                                                        class="btn btn-sm btn-outline-warning"
                                                                                                    >
                                                                                                        <i
                                                                                                            class="bi bi-pencil-square"
                                                                                                        ></i>
                                                                                                        Edit
                                                                                                    </a>

                                                                                                    <form
                                                                                                        action="{{ route('voters.destroy', $voter) }}"
                                                                                                        method="POST"
                                                                                                        class="d-inline"
                                                                                                        onsubmit="return confirm('Yakin ingin menghapus data DPT ini?')"
                                                                                                    >
                                                                                                        @csrf
                                                                                                        @method('DELETE')

                                                                                                        <button
                                                                                                            type="submit"
                                                                                                            class="btn btn-sm btn-outline-danger"
                                                                                                        >
                                                                                                            <i
                                                                                                                class="bi bi-trash"
                                                                                                            ></i>
                                                                                                            Hapus
                                                                                                        </button>

                                                                                                    </form>

                                                                                                @endunless

                                                                                            </td>

                                                                                        </tr>

                                                                                    @endforeach

                                                                                </tbody>

                                                                            </table>

                                                                        </div>

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        @endforeach

                                                    </div>

                                                </div>

                                            </div>

                                        @endforeach

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="empty-folder">

                        <i
                            class="bi bi-folder-x display-4 d-block mb-3"
                        ></i>

                        Belum ada data DPT.

                    </div>

                @endif

            {{-- MODE HASIL PENCARIAN --}}

            @else

                <div class="mb-3">

                    <div class="alert alert-info mb-0">

                        Hasil pencarian untuk:

                        <strong>
                            {{ $search }}
                        </strong>

                        @if ($voters)
                            — ditemukan
                            {{ $voters->total() }}
                            data
                        @endif

                    </div>

                </div>

                <div class="table-responsive">

                    <table
                        class="table table-hover align-middle mb-0 dpt-table"
                    >

                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">
                                    ID DPT
                                </th>

                                <th>
                                    Nama
                                </th>

                                <th>
                                    JK
                                </th>

                                <th>
                                    Dusun
                                </th>

                                <th>
                                    RT/RW
                                </th>

                                <th>
                                    NIK
                                </th>

                                <th>
                                    Status
                                </th>

                                <th
                                    class="text-center dpt-actions"
                                >
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($voters as $voter)

                                <tr>

                                    <td class="ps-4">
                                        <strong>
                                            {{ $voter->voter_code }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $voter->name }}
                                    </td>

                                    <td>

                                        @if ($voter->gender === 'L')
                                            <span
                                                class="badge text-bg-primary"
                                            >
                                                L
                                            </span>
                                        @elseif ($voter->gender === 'P')
                                            <span
                                                class="badge text-bg-danger"
                                            >
                                                P
                                            </span>
                                        @else
                                            -
                                        @endif

                                    </td>

                                    <td>

                                        @if ($voter->dusun)

                                            <span
                                                class="badge text-bg-primary"
                                            >
                                                {{ $voter->dusun->name }}
                                            </span>

                                            <div
                                                class="small text-secondary"
                                            >
                                                {{ $voter->dusun->code }}
                                            </div>

                                        @else

                                            <span class="text-secondary">
                                                -
                                            </span>

                                        @endif

                                    </td>

                                    <td>
                                        RT {{ $voter->rt }}
                                        /
                                        RW {{ $voter->rw }}
                                    </td>

                                    <td>
                                        {{ $voter->nik ?: '-' }}
                                    </td>

                                    <td>

                                        @if ($voter->has_voted)

                                            <span
                                                class="badge rounded-pill text-bg-success"
                                            >
                                                Sudah Memilih
                                            </span>

                                        @else

                                            <span
                                                class="badge rounded-pill text-bg-secondary"
                                            >
                                                Belum Memilih
                                            </span>

                                        @endif

                                    </td>

                                    <td class="text-center">

                                        <a
                                            href="{{ route('voters.show', $voter) }}"
                                            class="btn btn-sm btn-outline-info"
                                        >
                                            <i class="bi bi-eye"></i>
                                            Detail
                                        </a>

                                        <a
                                            href="{{ route('voters.qr', $voter) }}"
                                            class="btn btn-sm btn-success"
                                        >
                                            <i class="bi bi-qr-code"></i>
                                            QR
                                        </a>

                                        @unless ($voter->has_voted)

                                            <a
                                                href="{{ route('voters.edit', $voter) }}"
                                                class="btn btn-sm btn-outline-warning"
                                            >
                                                <i
                                                    class="bi bi-pencil-square"
                                                ></i>
                                                Edit
                                            </a>

                                            <form
                                                action="{{ route('voters.destroy', $voter) }}"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus data DPT ini?')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                >
                                                    <i
                                                        class="bi bi-trash"
                                                    ></i>
                                                    Hapus
                                                </button>

                                            </form>

                                        @endunless

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td
                                        colspan="8"
                                        class="text-center py-5 text-secondary"
                                    >
                                        <i
                                            class="bi bi-search fs-1 d-block mb-2"
                                        ></i>

                                        Data tidak ditemukan.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                @if ($voters && $voters->hasPages())

                    <div class="border-top p-3">

                        {{ $voters->links(
                            'pagination::bootstrap-5'
                        ) }}

                    </div>

                @endif

            @endif

        </div>

    </div>

</div>

@endsection