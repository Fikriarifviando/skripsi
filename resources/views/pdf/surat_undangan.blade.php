<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Undangan</title>
    <style>
        @page {
            margin: 2cm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12pt;
            line-height: 1.3;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header img.logo {
            height: 70px;
            margin: 0 10px;
        }

        .header-text {
            margin: 0;
            padding: 0;
            line-height: 1.1;
        }

        .document-date {
            text-align: right;
            margin-right: 40px;
            margin-bottom: 10px;
        }

        .document-info {
            margin-bottom: 15px;
        }

        .document-info table {
            width: 100%;
        }

        .document-info td.label {
            width: 80px;
            vertical-align: top;
        }

        .document-info td.separator {
            width: 20px;
            text-align: center;
            vertical-align: top;
        }

        .recipient {
            margin-bottom: 20px;
        }

        .content {
            margin-bottom: 20px;
            text-align: justify;
        }

        .event-details {
            margin-left: 40px;
            margin-bottom: 20px;
        }

        .event-details table {
            width: 80%;
        }

        .event-details td.label {
            width: 80px;
            padding: 3px 0;
        }

        .event-details td.separator {
            width: 20px;
            text-align: center;
            padding: 3px 0;
        }

        .closing {
            margin-bottom: 20px;
            text-align: justify;
        }

        .signature {
            float: right;
            width: 40%;
            text-align: center;
        }

        .signature p {
            margin: 0;
            /* Remove default paragraph margins */
            padding: 1px 0;
            /* Add minimal padding instead */
            line-height: 1.2;
            /* Reduce line height */
        }

        .signature-img {
            height: 130px;
            /* atau 120px sesuai kebutuhan */
            margin: 4px 0;
        }


        .footer {
            margin-top: 30px;
            border-top: 1px solid #000;
            text-align: center;
            font-size: 8pt;
            padding-top: 5px;
        }

        hr {
            border: 1px solid #000;
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <table style="width: 100%">
            <tr>
                <td style="width: 15%; text-align: right;">
                    <img class="logo" src="{{ public_path('img/logo_surabaya.png') }}" alt="Logo Pemkot">
                </td>
                <td style="width: 70%; text-align: center;">
                    <div class="header-text" style="font-size: 12pt;">PEMERINTAH KOTA SURABAYA</div>
                    <div class="header-text" style="font-size: 14pt; font-weight: bold;">SMP NEGERI 33 SURABAYA</div>
                    <div class="header-text" style="font-size: 10pt;">Jalan Putat Gede Selatan No. 8 Surabaya, Jawa
                        Timur 60189</div>
                    <div class="header-text" style="font-size: 10pt;">Telepon (031) 73120886, Faksimile (031) 7310224
                    </div>
                    <div class="header-text" style="font-size: 10pt;">Laman smpn33-sby.sch.id, Pos-el
                        smpn_33sby@yahoo.co.id</div>
                </td>
                <td style="width: 15%; text-align: left;">
                    <img class="logo" src="{{ public_path('img/logo_SMP.png') }}" alt="Logo Sekolah">
                </td>
            </tr>
        </table>
        <hr>
    </div>

    <div class="document-date">
        Surabaya, {{ date('j F Y') }}
    </div>

    <div class="document-info">
        <table>
            <tr>
                <td class="label">Nomor</td>
                <td class="separator">:</td>
                <td>{{ $no_surat }}</td>
            </tr>
            <tr>
                <td class="label">Lampiran</td>
                <td class="separator">:</td>
                <td>-</td>
            </tr>
            <tr>
                <td class="label">Hal</td>
                <td class="separator">:</td>
                <td>Undangan {{ $metadata['acara'] }}</td>
            </tr>
        </table>
    </div>

    <div class="recipient">
        <p>
            Yth. {{ $metadata['kepada'] }}<br>
            di<br>
            {{ $metadata['kota'] }}
        </p>
    </div>

    <div class="content">
        <p>Dengan Hormat,</p>
        <p>Sehubungan dengan akan dilaksanakannya {{ strtoupper($metadata['acara']) }}, kami pihak sekolah mengundang
            Bapak/Ibu, untuk hadir di sekolah pada</p>
    </div>

    @php
        \Carbon\Carbon::setLocale('id');
    @endphp
    <div class="event-details">
        <table>
            <tr>
                <td class="label">Hari</td>
                <td class="separator">:</td>
                <td>{{ \Carbon\Carbon::parse($metadata['tanggal'])->isoFormat('dddd') }}</td>
            </tr>
            <tr>
                <td class="label">Tgl</td>
                <td class="separator">:</td>
                <td>{{ \Carbon\Carbon::parse($metadata['tanggal'])->isoFormat('D MMMM Y') }}</td>
            </tr>
            <tr>
                <td class="label">Waktu</td>
                <td class="separator">:</td>
                <td>{{ $metadata['waktu_mulai'] }} WIB - SELESAI</td>
            </tr>
            <tr>
                <td class="label">Tempat</td>
                <td class="separator">:</td>
                <td>{{ $metadata['tempat'] }}</td>
            </tr>
        </table>
    </div>

    <div class="closing">
        <p>Demikian Undangan ini kami sampaikan atas perhatian, kerjasama dan kehadirannya, kami ucapkan terima kasih.
        </p>
    </div>

    <div class="signature">
        <p>Hormat kami,</p>
        <p>Plt. Kepala SMP Negeri 33 Surabaya</p>

        @if (isset($ttd_path) && !empty($ttd_path))
            <img src="{{ $ttd_path }}" class="signature-img" alt="Tanda Tangan">
        @else
            <div style="height: 60px;"></div>
        @endif

        <p>Darto, M.Pd.</p>
        <p>Pembina (IV/a)</p>
        <p>NIP 197012062008011010</p>
    </div>
</body>

</html>
