<!-- File: resources/views/pdf/surat_pengumuman.blade.php -->
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pengumuman</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.2;
            margin: 0;
            padding: 20px;
            font-size: 12pt;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        
        .header-table {
            width: 100%;
        }
        
        .header-table td {
            vertical-align: middle;
        }

        .logo {
            height: 70px;
            margin: 0 10px;
        }

        .letterhead {
            font-size: 14pt;
            font-weight: bold;
            margin: 2px 0;
            line-height: 1.1;
        }
        
        .sub-letterhead {
            font-size: 12pt;
            font-weight: bold;
            margin: 2px 0;
            line-height: 1.1;
        }

        .address {
            font-size: 10pt;
            margin-bottom: 5px;
            line-height: 1.1;
        }

        .divider {
            border-bottom: 2px solid #000;
            margin-top: 5px;
            margin-bottom: 10px;
        }

        .letter-info {
            margin-bottom: 10px;
        }

        .letter-date {
            text-align: right;
            margin-bottom: 10px;
        }

        .letter-header {
            width: 100%;
            border-collapse: collapse;
        }

        .letter-header td {
            padding: 2px 0;
            vertical-align: top;
        }

        .letter-content {
            text-align: justify;
            margin-bottom: 10px;
            line-height: 1.2;
        }

        .letter-content p {
            margin: 5px 0;
        }

        .closing {
            text-align: right;
            margin-top: 20px;
        }

        .signature {
            margin-top: 30px;
            margin-bottom: 10px;
            max-height: 60px;
        }

        .signature-name {
            font-weight: bold;
            margin-bottom: 0;
        }

        .signature-position {
            margin-top: 0;
            margin-bottom: 0;
        }

        .signature-id {
            margin-top: 5px;
        }

        ol {
            margin-left: 20px;
            padding-left: 10px;
        }

        li {
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="header">
        <table class="header-table">
            <tr>
                <td style="width: 15%; text-align: right;">
                    <img class="logo" src="{{ public_path('img/logo_surabaya.png') }}" alt="Logo Pemkot">
                </td>
                <td style="width: 70%; text-align: center;">
                    <div class="letterhead">PEMERINTAH KOTA SURABAYA</div>
                    <div class="letterhead">DINAS PENDIDIKAN</div>
                    <div class="sub-letterhead">SMP NEGERI 33 SURABAYA</div>
                    <div class="address">
                        Jalan Putat Gede Selatan No. 8 Surabaya, Jawa Timur 60189<br>
                        Telepon: (031) 73120886, Faksimile (031) 7310224<br>
                        Laman: smpn33-sby.sch.id, Pos-el: smpn_33sby@yahoo.co.id
                    </div>
                </td>
                <td style="width: 15%; text-align: left;">
                    <img class="logo" src="{{ public_path('img/logo_SMP.png') }}" alt="Logo Sekolah">
                </td>
            </tr>
        </table>
        <div class="divider"></div>
    </div>

    <div class="letter-date">
        Surabaya, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}
    </div>

    <table class="letter-header">
        <tr>
            <td width="100">Nomor</td>
            <td width="10">:</td>
            <td>{{ $no_surat ?? '-' }}</td>
        </tr>
        <tr>
            <td>Sifat</td>
            <td>:</td>
            <td>Biasa</td>
        </tr>
        <tr>
            <td>Lampiran</td>
            <td>:</td>
            <td>-</td>
        </tr>
        <tr>
            <td>Perihal</td>
            <td>:</td>
            <td><strong>{{ $metadata['perihal'] ?? 'Pemberitahuan' }}</strong></td>
        </tr>
    </table>

    <div class="letter-content" style="margin-top: 15px;">
        <p>
            Yth. {{ $metadata['pembuka'] ?? 'Wali Murid' }}<br>
            Di -<br>
            <strong>SURABAYA</strong>
        </p>

        <div style="text-align: justify;">
            {!! nl2br($metadata['body'] ?? '') !!}
        </div>
    </div>

    <div class="closing">
        <p>
            Demikian pemberitahuan ini kami sampaikan, atas perhatian dan kerjasamanya kami sampaikan terimakasih.
        </p>

        <div style="margin-top: 20px;">
            <p>{{ $kepala_sekolah->jabatan ?? 'Plt. Kepala Sekolah' }}</p>

            @if (!empty($ttd_path))
                <img src="{{ $ttd_path }}" alt="Tanda Tangan" class="signature">
            @else
                <div style="height: 50px;"></div>
            @endif

            <p class="signature-name">{{ $kepala_sekolah->nama ?? 'Darto, M.Pd' }}</p>
            <p class="signature-position">{{ $kepala_sekolah->pangkat ?? 'Pembina (IV/a)' }}</p>
            <p class="signature-id">NIP {{ $kepala_sekolah->nip ?? '197012062008011010' }}</p>
        </div>
    </div>
</body>

</html>