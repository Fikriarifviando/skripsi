<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Surat Rekomendasi Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            line-height: 1.2;
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

        hr {
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .title {
            text-align: center;
            margin: 10px 0;
        }

        .title h3 {
            margin-bottom: 0;
            font-weight: bold;
        }

        .title p {
            margin-top: 2px;
            font-weight: normal;
        }

        .content {
            margin: 10px 0;
        }

        /* Add padding to the entire data table to create indentation */
        .data-table {
            padding-left: 30px;
            /* Adjust this value for more or less indentation */
        }

        .signature {
            margin-top: 20px;
            text-align: right;
            width: 40%;
            float: right;
        }

        .signatureImg {
            width: 200px;  /* Increased from 150px to 200px */
            height: 100px; /* Increased from 70px to 100px */
            margin: 0 auto;
            display: block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .left-column {
            width: 170px;
            text-align: left;
            padding-right: 0;
        }

        .colon-column {
            width: 10px;
            text-align: right;
            padding-right: 5px;
        }

        .form-value {
            padding-left: 5px;
        }

        p {
            margin: 5px 0;
        }

        .signature p {
            margin: 3px 0;
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

    <div class="title">
        <h3>SURAT REKOMENDASI</h3>
        <p>Nomor : {{ $no_surat }}</p>
    </div>

    <div class="content">
        <p>Yang bertanda tangan dibawah ini :</p>
        <div class="data-table">
            <table>
                <tr>
                    <td class="left-column">N a m a</td>
                    <td class="colon-column">:</td>
                    <td class="form-value">{{ $kepala_sekolah->nama }}</td>
                </tr>
                <tr>
                    <td class="left-column">N I P</td>
                    <td class="colon-column">:</td>
                    <td class="form-value">{{ $kepala_sekolah->nip }}</td>
                </tr>
                <tr>
                    <td class="left-column">Jabatan</td>
                    <td class="colon-column">:</td>
                    <td class="form-value">{{ $kepala_sekolah->jabatan }}</td>
                </tr>
                <tr>
                    <td class="left-column">Unit Kerja</td>
                    <td class="colon-column">:</td>
                    <td class="form-value">SMP Negeri 33 Surabaya</td>
                </tr>
            </table>
        </div>

        <p>Menerangkan bahwa :</p>
        <div class="data-table">
            <table>
                <tr>
                    <td class="left-column">N a m a</td>
                    <td class="colon-column">:</td>
                    <td class="form-value">{{ $metadata['informasi_pribadi']['nama'] }}</td>
                </tr>
                <tr>
                    <td class="left-column">Jenis Kelamin</td>
                    <td class="colon-column">:</td>
                    <td class="form-value">{{ $metadata['informasi_pribadi']['jenis_kelamin'] }}</td>
                </tr>
                <tr>
                    <td class="left-column">Tempat / Tanggal lahir</td>
                    <td class="colon-column">:</td>
                    <td class="form-value">{{ $metadata['informasi_pribadi']['tempat_lahir'] }} /
                        {{ $metadata['informasi_pribadi']['tanggal_lahir'] }}</td>
                </tr>
                <tr>
                    <td class="left-column">NIS / NISN</td>
                    <td class="colon-column">:</td>
                    <td class="form-value">{{ $metadata['informasi_akademik']['nis_nisn'] }}</td>
                </tr>
                <tr>
                    <td class="left-column">Kelas</td>
                    <td class="colon-column">:</td>
                    <td class="form-value">{{ $metadata['informasi_akademik']['kelas'] }}</td>
                </tr>
                <tr>
                    <td class="left-column">Alamat</td>
                    <td class="colon-column">:</td>
                    <td class="form-value">{{ $metadata['informasi_kontak']['alamat'] }}</td>
                </tr>
            </table>
        </div>

        @php
            $bulan = now()->month;
            $tahun = now()->year;
            $tahunAjaran = $bulan > 6 ? $tahun . '/' . ($tahun + 1) : $tahun - 1 . '/' . $tahun;
        @endphp

        <p>Adalah benar merupakan peserta didik berstatus aktif dari SMP Negeri 33 Surabaya Tahun Pelajaran
            {{ $tahunAjaran }}.</p>


        <p>Surat Keterangan ini dibuat sebagai rekomendasi dan dispensasi tidak masuk sekolah untuk keperluan
            persyaratan mengikuti kegiatan {{ $metadata['informasi_acara']['nama_acara'] }} yang dilaksanakan pada:</p>

        <div class="data-table">
            <table>
                <tr>
                    <td class="left-column">Tanggal</td>
                    <td class="colon-column">:</td>
                    <td class="form-value">{{ $metadata['informasi_acara']['tanggal_acara'] }}</td>
                </tr>
                <tr>
                    <td class="left-column">Penyelenggara</td>
                    <td class="colon-column">:</td>
                    <td class="form-value">{{ $metadata['informasi_acara']['penyelenggara'] }}</td>
                </tr>
            </table>
        </div>

        <p>Demikian surat keterangan ini dibuat dengan sebenar-benarnya dan dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="signature">
        <p>Surabaya, {{ date('d F Y', strtotime($metadata['tanggal_registrasi'])) }}</p>
        <p>{{ $kepala_sekolah->jabatan }}</p>
        <br style="line-height: 0.5;">
        @if (isset($ttd_path) && file_exists($ttd_path))
            <img src="{{ $ttd_path }}" class="signatureImg">
        @endif
        <p>{{ $kepala_sekolah->nama }}</p>
        <p>{{ $kepala_sekolah->pangkat }}</p>
        <p>NIP {{ $kepala_sekolah->nip }}</p>
    </div>
</body>

</html>