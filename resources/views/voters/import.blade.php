@extends('layouts.admin')

@section('content')
<div class="container">

    <div class="card">

        <div class="card-header">
            <h4>Import Data DPT</h4>
        </div>

        <div class="card-body">

            @if (session('error'))
                <div class="alert alert-danger">
                    <div class="fw-bold mb-1">
                        Import gagal
                    </div>

                    <div>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <form action="{{ route('voters.import.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <div class="mb-3">

                    <label class="form-label">
                        File Excel
                    </label>

                    <input
                        type="file"
                        name="file"
                        class="form-control"
                        accept=".xlsx,.xls"
                        required>

                </div>

                <button class="btn btn-primary">

                    Import

                </button>

                <a href="{{ route('voters.index') }}"
                   class="btn btn-secondary">

                    Kembali

                </a>

            </form>

        </div>

    </div>

</div>
@endsection