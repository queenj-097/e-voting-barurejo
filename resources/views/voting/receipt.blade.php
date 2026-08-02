<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>QR Surat Suara</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            margin: 0;
            background: #f3f7f5;
            font-family: Arial, Helvetica, sans-serif;
        }

        .wrapper {
            padding: 40px 20px;
        }

        .receipt-paper {
            width: 100%;
            max-width: 80mm;
            margin: auto;
            background: white;
            padding: 10mm 7mm;
            text-align: center;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .12);
        }

        .logo {
            width: 70px;
            margin-bottom: 12px;
        }

        .title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .subtitle {
            font-size: 13px;
            color: #666;
            margin-bottom: 15px;
        }

        hr {
            border: none;
            border-top: 1px dashed #999;
            margin: 18px 0;
        }

        .qr-wrapper {
            width: 52mm;
            height: 52mm;
            margin: auto;
        }

        .qr-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .small-text {
            font-size: 12px;
            color: #666;
        }

        .action-buttons {
            margin-top: 25px;
        }

        @page {
        size: 58mm auto;
        margin: 0;
    }

    @media print {
        html,
        body {
            width: 58mm;
            margin: 0 !important;
            padding: 0 !important;
            background: white;
        }

        .wrapper {
            width: 58mm;
            margin: 0;
            padding: 0;
        }

        .receipt-paper {
            box-sizing: border-box;
            width: 58mm;
            max-width: 58mm;
            margin: 0;
            padding: 5mm 4mm;
            border-radius: 0;
            box-shadow: none;
            text-align: center;
            overflow: hidden;
        }

        .logo {
            width: 16mm;
            margin: 0 auto 3mm;
            display: block;
        }

        .title {
            font-size: 17px;
        }

        .subtitle {
            font-size: 11px;
        }

        .qr-wrapper {
            width: 44mm;
            height: 44mm;
            margin: 0 auto;
        }

        .qr-wrapper img {
            display: block;
            width: 44mm;
            height: 44mm;
            margin: 0 auto;
        }

        .small-text {
            font-size: 10px;
        }

        .action-buttons {
            display: none !important;
        }
    }
    </style>
</head>

<body>

<div class="wrapper">

    <div class="receipt-paper">

        <img
            src="{{ asset('images/logos/logo-bwi.png') }}"
            alt="Logo Kabupaten Banyuwangi"
            class="logo"
        >

        <div class="title">
            E-VOTING
        </div>

        <div class="fw-bold">
            DESA BARUREJO
        </div>

        <div class="subtitle">
            Kecamatan Siliragung<br>
            Kabupaten Banyuwangi
        </div>

        <hr>

        <h5 class="fw-bold text-success">
            SUARA BERHASIL DIREKAM
        </h5>

        <p class="small-text">
            Cetak QR ini lalu masukkan ke kotak suara.
        </p>

        <div class="qr-wrapper">
            <img
                src="{{ $qrDataUri }}"
                alt="QR Surat Suara"
            >
        </div>

        <hr>

        <div class="small-text">
            QR hanya dapat dihitung satu kali.
        </div>
        <div class="small-text">
            ---
        </div>

    </div>

    <div class="text-center action-buttons">

        <button
            type="button"
            class="btn btn-success btn-lg px-4"
            onclick="printAndReturn()"
        >
            Cetak QR
        </button>

        <a
            href="{{ $returnUrl }}"
            class="btn btn-outline-secondary btn-lg px-4 ms-2"
        >
            Selesai dan Kembali ke Bilik
        </a>

    </div>

</div>

<script>
    const returnUrl = @json($returnUrl);

    window.addEventListener('load', function () {
        setTimeout(function () {
            window.print();
        }, 300);
    });

    window.addEventListener('afterprint', function () {
        window.location.href = returnUrl;
    });
</script>

</body>
</html>