@extends('layouts.app')

@section('title', 'Edit Surat Perintah')
@section('page-title', 'Edit Surat Perintah')

@push('style')
    <style>
        .scroll-area {
            height: 300px;
            max-height: calc(60px * 5);
            overflow-y: auto;
        }

        .pegawai-item {
            height: 60px;
            margin-bottom: 8px !important;
            padding: 8px !important;
        }
    </style>
@endpush

@section('content')
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('perintah.update', $suratKeluar) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-4">
                                <h5>Data Guru yang Ditugaskan</h5>
                                <div class="form-group mb-3">
                                    <label>Cari Guru</label>
                                    <div class="input-group">
                                        <input type="text" id="searchGuru" class="form-control"
                                            placeholder="Ketik nama atau jabatan...">
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="fas fa-search"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header bg-light font-weight-bold">
                                                Daftar Guru
                                            </div>
                                            <div class="card-body">
                                                <div class="scroll-area transparant">
                                                    <div id="daftar-guru">
                                                        @php
                                                            $metadata = json_decode($suratKeluar->metadata);
                                                            $guruTerpilihIds = [];
                                                            if(isset($metadata->guru_ditugaskan)) {
                                                                foreach($metadata->guru_ditugaskan as $guruDitugaskan) {
                                                                    $guruTerpilihIds[] = $guruDitugaskan->id;
                                                                }
                                                            }
                                                        @endphp
                                                        
                                                        @foreach ($pegawai as $guru)
                                                            @if(!in_array($guru->id, $guruTerpilihIds))
                                                                <div class="pegawai-item mb-2 p-2 border rounded"
                                                                    data-id="{{ $guru->id }}"
                                                                    data-nama="{{ $guru->nama }}"
                                                                    data-nip="{{ $guru->nip }}"
                                                                    data-email="{{ $guru->email }}" style="min-height: 60px;">
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-center">
                                                                        <div>
                                                                            <strong>{{ $guru->nama }}</strong><br>
                                                                            <small class="text-muted">NIP:
                                                                                {{ $guru->nip }}</small>
                                                                        </div>
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-outline-primary btn-pilih">
                                                                            Pilih
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header bg-light font-weight-bold">
                                                Guru Terpilih
                                            </div>
                                            <div class="card-body scroll-area">
                                                <div id="guru-terpilih">
                                                    @if(isset($metadata->guru_ditugaskan))
                                                        @foreach($metadata->guru_ditugaskan as $guruDitugaskan)
                                                            <div class="pegawai-item mb-2 p-2 border rounded"
                                                                data-id="{{ $guruDitugaskan->id }}"
                                                                data-nama="{{ $guruDitugaskan->nama }}"
                                                                data-nip="{{ $guruDitugaskan->nip }}"
                                                                data-email="{{ $guruDitugaskan->email ?? '' }}" style="min-height: 60px;">
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <div>
                                                                        <strong>{{ $guruDitugaskan->nama }}</strong><br>
                                                                        <small class="text-muted">NIP:
                                                                            {{ $guruDitugaskan->nip }}</small>
                                                                    </div>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-outline-danger btn-hapus">
                                                                        Hapus
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hidden input untuk ID guru -->
                                <div id="selected-guru-inputs">
                                    @if(isset($metadata->guru_ditugaskan))
                                        @foreach($metadata->guru_ditugaskan as $guruDitugaskan)
                                            <input type="hidden" name="guru_ids[]" value="{{ $guruDitugaskan->id }}">
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="hari">Hari:</label>
                                            <select id="hari" name="hari" class="form-control @error('hari') is-invalid @enderror" required>
                                                <option value="">- Pilih Hari -</option>
                                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                                                    <option value="{{ $hari }}" {{ (isset($metadata->hari) && $metadata->hari == $hari) ? 'selected' : '' }}>
                                                        {{ $hari }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('hari')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tanggal">Tanggal:</label>
                                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                                                id="tanggal" name="tanggal" 
                                                value="{{ isset($metadata->tanggal) ? $metadata->tanggal : '' }}" required>
                                            @error('tanggal')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="waktu_mulai">Waktu Mulai:</label>
                                    <input type="time" class="form-control @error('waktu_mulai') is-invalid @enderror" 
                                        id="waktu_mulai" name="waktu_mulai" 
                                        value="{{ isset($metadata->waktu_mulai) ? $metadata->waktu_mulai : '' }}" required>
                                    @error('waktu_mulai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="tugas">Tugas:</label>
                                    <input type="text" class="form-control @error('tugas') is-invalid @enderror" 
                                        id="tugas" name="tugas" 
                                        value="{{ isset($metadata->tugas) ? $metadata->tugas : '' }}"
                                        placeholder="Tugas yang diemban" required>
                                    @error('tugas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="tempat">Tempat:</label>
                                    <input type="text" class="form-control @error('tempat') is-invalid @enderror" 
                                        id="tempat" name="tempat" 
                                        value="{{ isset($metadata->tempat) ? $metadata->tempat : '' }}"
                                        placeholder="Masukkan tempat pelaksanaan" required>
                                    @error('tempat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="alamat">Alamat:</label>
                                    <input type="text" class="form-control @error('alamat') is-invalid @enderror" 
                                        id="alamat" name="alamat" 
                                        value="{{ isset($metadata->alamat) ? $metadata->alamat : '' }}"
                                        placeholder="Masukkan alamat tempat pelaksanaan" required>
                                    @error('alamat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <div class="form-group">
                                        <label for="kode_klasifikasi">Kode Klasifikasi:</label>
                                        <select name="kode_klasifikasi" id="kode_klasifikasi" 
                                            class="form-control @error('kode_klasifikasi') is-invalid @enderror" required>
                                            <option value="">- Pilih Kode Klasifikasi -</option>
                                            @foreach ($kodeSurat as $kode)
                                                <option value="{{ $kode->id }}" 
                                                    {{ $suratKeluar->kode_surat_id == $kode->id ? 'selected' : '' }}>
                                                    {{ $kode->kode_klasifikasi }} - {{ $kode->nama_kode }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('kode_klasifikasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 text-center">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> Perbarui Surat Perintah
                                </button>
                                <a href="{{ route('keluar.master') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Pencarian guru
            $("#searchGuru").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#daftar-guru .pegawai-item").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            // Pilih guru
            $(".btn-pilih").click(function() {
                var item = $(this).closest('.pegawai-item');
                var guruId = item.data('id');
                var guruNama = item.data('nama');
                var guruNip = item.data('nip');
                var guruHtml = item.clone();

                // Ganti tombol Pilih menjadi Hapus
                guruHtml.find('.btn-pilih')
                    .removeClass('btn-outline-primary btn-pilih')
                    .addClass('btn-outline-danger btn-hapus')
                    .text('Hapus');

                // Tambah hidden input
                $('#selected-guru-inputs').append(
                    `<input type="hidden" name="guru_ids[]" value="${guruId}">`
                );

                // Pindah ke daftar terpilih
                $('#guru-terpilih').append(guruHtml);
                item.hide();
            });

            // Hapus guru yang dipilih
            $(document).on('click', '.btn-hapus', function() {
                var item = $(this).closest('.pegawai-item');
                var guruId = item.data('id');

                // Hapus dari daftar terpilih
                item.remove();

                // Hapus hidden input
                $(`input[name="guru_ids[]"][value="${guruId}"]`).remove();

                // Tampilkan kembali di daftar guru
                $(`#daftar-guru .pegawai-item[data-id="${guruId}"]`).show();
                
                // Jika guru tidak ada di daftar (karena page refresh), tambahkan ke daftar
                if ($(`#daftar-guru .pegawai-item[data-id="${guruId}"]`).length === 0) {
                    var guruNama = item.data('nama');
                    var guruNip = item.data('nip');
                    var guruEmail = item.data('email');
                    
                    var newGuruHtml = `
                        <div class="pegawai-item mb-2 p-2 border rounded"
                            data-id="${guruId}" data-nama="${guruNama}" 
                            data-nip="${guruNip}" data-email="${guruEmail}" style="min-height: 60px;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>${guruNama}</strong><br>
                                    <small class="text-muted">NIP: ${guruNip}</small>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary btn-pilih">
                                    Pilih
                                </button>
                            </div>
                        </div>
                    `;
                    
                    $('#daftar-guru').append(newGuruHtml);
                }
            });

            $('#tanggal').on('change', function() {
                const tanggal = new Date(this.value);
                if (!isNaN(tanggal)) {
                    const hariIndo = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    const hariDariTanggal = hariIndo[tanggal.getDay()];
                    $('#hari').val(hariDariTanggal);
                }
            });
        });
    </script>
@endpush