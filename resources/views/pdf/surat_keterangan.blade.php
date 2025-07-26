
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Surat Keterangan Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img.logo {
            height: 80px;
            margin: 0 10px;
        }

        .header-line {
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: 10px;
        }

        .nomor {
            text-align: center;
            margin-bottom: 20px;
        }

        .content {
            width: 90%;
            margin: 0 auto;
        }

        .signature {
            width: 40%;
            margin-left: auto;
            margin-right: 50px;
            text-align: center;
        }

        /* Modified signature styling to make it larger */
        .signatureImg {
            height: 120px; /* Increased from 80px */
            width: auto;
            display: block;
            margin: 0 auto 10px;
        }

        table.form-table {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
        }

        table.form-table td {
            padding: 3px 5px;
            vertical-align: top;
        }

        p {
            margin: 8px 0;
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

    <div class="title">SURAT KETERANGAN</div>
    <div class="nomor">Nomor : {{ $no_surat }}</div>

    <div class="content">
        <p>Yang bertanda tangan dibawah ini :</p>
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
                <td>Unit Kerja</td>
                <td>:</td>
                <td>SMP Negeri 33 Surabaya</td>
            </tr>
        </table>

        <p>Menerangkan bahwa</p>
        <table class="form-table">
            <tr>
                <td style="width: 20%">Nama</td>
                <td style="width: 5%">:</td>
                <td style="width: 75%">{{ $metadata['informasi_pribadi']['nama'] }}</td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>{{ $metadata['informasi_pribadi']['jenis_kelamin'] }}</td>
            </tr>
            <tr>
                <td>Tempat / Tanggal lahir</td>
                <td>:</td>
                <td>{{ $metadata['informasi_pribadi']['tempat_lahir'] }} /
                    {{ $metadata['informasi_pribadi']['tanggal_lahir'] }}</td>
            </tr>
            <tr>
                <td>NIS / NISN</td>
                <td>:</td>
                <td>{{ $metadata['informasi_akademik']['nis_nisn'] }}</td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td>{{ $metadata['informasi_akademik']['kelas'] }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $metadata['informasi_kontak']['alamat'] }}</td>
            </tr>
        </table>

        @php
            $bulan = now()->month;
            $tahun = now()->year;
            $tahunAjaran = $bulan > 6 ? $tahun . '/' . ($tahun + 1) : $tahun - 1 . '/' . $tahun;
        @endphp

        <p>Adalah benar-benar siswa aktif SMP Negeri 33 Surabaya Tahun Ajaran {{ $tahunAjaran }}
            {{ $metadata['keperluan'] }}</p>


        <p>Demikian surat keterangan ini dibuat dengan sebenar-benarnya dan dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="signature">
        <div>Surabaya, {{ date('d F Y', strtotime($metadata['tanggal_registrasi'])) }}</div>
        <div>{{ $kepala_sekolah->jabatan }}</div>

        <!-- Modified signature placement to be between position and name -->
        <div style="margin-top: 20px;">
            @if (isset($ttd_path) && file_exists($ttd_path))
                <img src="{{ $ttd_path }}" class="signatureImg">
            @endif
        </div>
        
        <div>
            <div>{{ $kepala_sekolah->nama }}</div>
            <div>{{ $kepala_sekolah->pangkat }}</div>
            <div>NIP {{ $kepala_sekolah->nip }}</div>
        </div>
    </div>
</body>

</html>