<!-- resources/views/pdf/surat_perintah.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Surat Perintah</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.3;
            /* Dikurangi dari 1.5 */
            color: #000;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            /* Dikurangi dari 20px */
        }

        .header img.logo {
            height: 70px;
            /* Dikurangi dari 80px */
            margin: 0 10px;
        }

        .header-line {
            border-bottom: 2px solid #000;
            margin-bottom: 10px;
            /* Dikurangi dari 20px */
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: 5px;
            /* Dikurangi dari 10px */
        }

        .nomor {
            text-align: center;
            margin-bottom: 10px;
            /* Dikurangi dari 20px */
        }

        table.form-table {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
        }

        table.form-table td {
            padding: 2px 5px;
            /* Dikurangi dari 3px 5px */
            vertical-align: top;
        }

        table.data-table {
            width: 90%;
            margin: 5px auto;
            /* Dikurangi dari 10px */
            border-collapse: collapse;
            border: 1px solid #000;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #000;
            padding: 3px;
            /* Dikurangi dari 5px */
        }

        .content {
            width: 90%;
            margin: 0 auto;
        }

        .content p {
            margin: 5px 0;
            /* Mengurangi margin paragraf */
        }

        .footer {
            width: 40%;
            margin-left: auto;
            margin-right: 50px;
            text-align: center;
        }

        .signature {
            margin: 10px 0;
            /* Jarak atas dan bawah tanda tangan */
        }

        .ttd-image {
            height: 80px;
            /* Hilangkan position absolute supaya tanda tangan mengalir dengan natural */
            /* position: absolute; */
            /* margin-top: -40px; */
            /* margin-left: 30px; */
        }

        .footer {
            width: 40%;
            margin-left: auto;
            margin-right: 50px;
            text-align: center;
        }

        .signature-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .ttd-image {
            height: 100px;
            /* Atur ukuran sesuai keinginan */
            opacity: 0.95;
            margin-bottom: -10px;
            /* Supaya lebih dekat dengan nama */
        }

        .nama-ttd {
            font-weight: bold;
            margin-top: 5px;
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
                    <div style="font-size: 12pt;">PEMERINTAH KOTA SURABAYA</div>
                    <div style="font-size: 14pt; font-weight: bold;">SMP NEGERI 33 SURABAYA</div>
                    <div style="font-size: 10pt;">Jalan Putat Gede Selatan No. 8 Surabaya, Jawa Timur 60189</div>
                    <div style="font-size: 10pt;">Telepon (031) 73120886, Faximile (031) 7310224</div>
                    <div style="font-size: 10pt;">Laman smpn33-sby.sch.id, Pos-el smpn_33sby@yahoo.co.id</div>
                </td>
                <td style="width: 15%; text-align: left;">
                    <img class="logo" src="{{ public_path('img/logo_SMP.png') }}" alt="Logo Sekolah">
                </td>
            </tr>
        </table>
    </div>

    <div class="header-line"></div>

    <div class="title">SURAT PERINTAH</div>
    <div class="nomor">Nomor: {{ $no_surat }}</div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini</p>

        <table class="form-table">
            <tr>
                <td style="width: 20%">Nama</td>
                <td style="width: 5%">:</td>
                <td style="width: 75%">{{ $kepala_sekolah->nama }}</td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>:</td>
                <td>{{ $kepala_sekolah->nip }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $kepala_sekolah->jabatan }}</td>
            </tr>
            <tr>
                <td>Instansi</td>
                <td>:</td>
                <td>SMP Negeri 33 Surabaya.</td>
            </tr>
        </table>

        <p>Memerintahkan kepada</p>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%">NO</th>
                    <th style="width: 45%">NAMA</th>
                    <th style="width: 30%">NIP</th>
                    <th style="width: 20%">KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($metadata['guru_ditugaskan'] as $index => $guru)
                    <tr>
                        <td style="text-align: center">{{ $index + 1 }}.</td>
                        <td>{{ $guru['nama'] }}</td>
                        <td>{{ $guru['nip'] ?? '-' }}</td>
                        <td>{{ $guru['jabatan']['nama'] ?? 'Guru' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- After the data-table and before the form-table with date/time -->
        <p>{{ $metadata['tugas'] ?? 'Untuk mengikuti kegiatan yang dilaksanakan pada' }}</p>

        <table class="form-table">
            <tr>
                <td style="width: 20%">Hari / Tanggal</td>
                <td style="width: 5%">:</td>
                <td style="width: 75%">{{ $metadata['hari'] }} / {{ $metadata['tanggal'] }}</td>
            </tr>
            <tr>
                <td>Waktu</td>
                <td>:</td>
                <td>pukul {{ $metadata['waktu_mulai'] }} WIB s.d. SELESAI</td>
            </tr>
            <tr>
                <td>Tempat</td>
                <td>:</td>
                <td>{{ $metadata['tempat'] }}</td>
            </tr>
            @if (isset($metadata['alamat']) && !empty($metadata['alamat']))
                <tr>
                    <td></td>
                    <td></td>
                    <td>{{ $metadata['alamat'] }}</td>
                </tr>
            @endif
        </table>

        <p>Demikian surat perintah ini dibuat, untuk dilaksanakan dengan penuh tanggung jawab dan setelah melaksanakan
            tugas harap membuat laporan kepada Kepala Sekolah.</p>
    </div>

    <div class="footer">
        <div>{{ $metadata['kota'] ?? 'Surabaya' }}, {{ now()->translatedFormat('d F Y') }}</div>
        <div>{{ $kepala_sekolah->jabatan ?? 'Plt. Kepala SMP Negeri 33 Surabaya' }}</div>

        <div class="signature-container" style="position: relative; display: inline-block;">
            @if (isset($ttd_path) && file_exists($ttd_path))
                <img src="{{ $ttd_path }}" class="ttd-image" alt="Tanda Tangan" />
            @endif
            <div class="nama-ttd">{{ $kepala_sekolah->nama }}</div>
        </div>

        <div>{{ $kepala_sekolah->pangkat }}</div>
        <div>NIP {{ $kepala_sekolah->nip }}</div>
    </div>


</body>

</html>
