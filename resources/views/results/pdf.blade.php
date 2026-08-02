<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <title>Rekapitulasi E-Voting</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
        }

        .header {
            text-align: center;
            margin-bottom: 24px;
        }

        .header h1 {
            margin: 0 0 6px;
            font-size: 20px;
        }

        .header p {
            margin: 3px 0;
        }

        .summary {
            width: 100%;
            margin-bottom: 22px;
            border-collapse: collapse;
        }

        .summary td {
            width: 25%;
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        .summary .label {
            font-size: 10px;
            color: #666;
            margin-bottom: 5px;
        }

        .summary .value {
            font-size: 18px;
            font-weight: bold;
        }

        table.results {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        table.results th,
        table.results td {
            border: 1px solid #999;
            padding: 8px;
        }

        table.results th {
            background: #eeeeee;
        }

        .dusun-row td {
            background: #dddddd;
            font-weight: bold;
            font-size: 13px;
            padding: 9px;
        }

        .text-center {
            text-align: center;
        }

        .signature {
            width: 100%;
            margin-top: 55px;
        }

        .signature td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .signature-space {
            height: 70px;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            color: #777;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>
            REKAPITULASI HASIL PEMUNGUTAN SUARA
        </h1>

        <p>
            <strong>
                {{ $setting?->title ?? 'Pemungutan Suara Elektronik' }}
            </strong>
        </p>

        <p>
            {{ $setting?->institution ?? 'Desa Barurejo' }}
        </p>

        @if ($setting?->location)
            <p>
                Lokasi: {{ $setting->location }}
            </p>
        @endif

        @if ($setting?->election_date)
            <p>
                Tanggal:
                {{ $setting->election_date->translatedFormat('d F Y') }}
            </p>
        @endif
    </div>

    <table class="summary">
        <tr>
            <td>
                <div class="label">Total DPT</div>
                <div class="value">{{ $totalVoters }}</div>
            </td>

            <td>
                <div class="label">Sudah Memilih</div>
                <div class="value">{{ $votedVoters }}</div>
            </td>

            <td>
                <div class="label">Suara Sah</div>
                <div class="value">{{ $countedBallots }}</div>
            </td>

            <td>
                <div class="label">Partisipasi</div>
                <div class="value">{{ $participationPercentage }}%</div>
            </td>
        </tr>
    </table>

    <h3>Perolehan Suara Kandidat per Dusun</h3>

    <table class="results">
        <thead>
            <tr>
                <th class="text-center" width="10%">
                    No.
                </th>

                <th>
                    Nama Kandidat
                </th>

                <th class="text-center" width="20%">
                    Jumlah Suara
                </th>

                <th class="text-center" width="20%">
                    Persentase
                </th>
            </tr>
        </thead>

        <tbody>
            @forelse ($candidatesByDusun as $dusun => $dusunData)

                <tr class="dusun-row">
                    <td colspan="4">
                        Dusun {{ $dusun }}
                        — Total Suara Sah: {{ $dusunData['total_votes'] }}
                    </td>
                </tr>

                @foreach ($dusunData['candidates'] as $candidate)
                    <tr>
                        <td class="text-center">
                            {{ $candidate->number }}
                        </td>

                        <td>
                            {{ $candidate->name }}
                        </td>

                        <td class="text-center">
                            {{ $candidate->counted_votes }}
                        </td>

                        <td class="text-center">
                            {{ number_format(
                                $candidate->percentage,
                                2,
                                ',',
                                '.'
                            ) }}%
                        </td>
                    </tr>
                @endforeach

            @empty

                <tr>
                    <td colspan="4" class="text-center">
                        Belum ada kandidat.
                    </td>
                </tr>

            @endforelse
        </tbody>
    </table>

    <p style="margin-top: 18px;">
        Total surat suara dibuat: {{ $totalBallots }}<br>
        Surat suara belum dihitung: {{ $uncountedBallots }}
    </p>

    <table class="signature">
        <tr>
            <td>
                Mengetahui,<br>
                Ketua Panitia Pemilihan

                <div class="signature-space"></div>

                (________________________)
            </td>

            <td>
                {{ $setting?->location ?? 'Desa Barurejo' }},
                {{ now()->translatedFormat('d F Y') }}<br>
                Administrator Sistem

                <div class="signature-space"></div>

                (________________________)
            </td>
        </tr>
    </table>

    <div class="footer">
        Dokumen dibuat otomatis oleh Sistem E-Voting Desa Barurejo.
    </div>

</body>
</html>