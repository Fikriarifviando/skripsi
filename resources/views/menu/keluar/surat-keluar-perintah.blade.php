@extends('layouts.app')

@section('title', 'Surat Perintah')
@section('page-title', 'Surat Perintah')

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
                        <form action="{{ route('surat-perintah.store') }}" method="POST">
                            @csrf
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
                                                        @foreach ($pegawai as $guru)
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
                                                <div id="guru-terpilih"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hidden input untuk ID guru -->
                                <div id="selected-guru-inputs"></div>
                            </div>

                            <div class="mb-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="hari">Hari:</label>
                                            <select id="hari" name="hari" class="form-control" required>
                                                <option value="">- Pilih Hari -</option>
                                                <option value="Senin">Senin</option>
                                                <option value="Selasa">Selasa</option>
                                                <option value="Rabu">Rabu</option>
                                                <option value="Kamis">Kamis</option>
                                                <option value="Jumat">Jumat</option>
                                                <option value="Sabtu">Sabtu</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tanggal">Tanggal:</label>
                                            <input type="date" class="form-control" id="tanggal" name="tanggal"
                                                min="{{ date('Y-m-d') }}" required>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label for="waktu_mulai">Waktu Mulai:</label>
                                    <input type="time" class="form-control" id="waktu_mulai" name="waktu_mulai" required>
                                </div>

                                <div class="form-group">
                                    <label for="tugas">Tugas:</label>
                                    <input type="text" class="form-control" id="tugas" name="tugas"
                                        placeholder="Tugas yang diemban" required>
                                </div>

                                <div class="form-group">
                                    <label for="tempat">Tempat:</label>
                                    <input type="text" class="form-control" id="tempat" name="tempat"
                                        placeholder="Masukkan tempat pelaksanaan" required>
                                </div>

                                <div class="form-group">
                                    <label for="alamat">Alamat:</label>
                                    <input type="text" class="form-control" id="alamat" name="alamat"
                                        placeholder="Masukkan alamat tempat pelaksanaan" required>
                                </div>

                                <div class="mb-4">
                                    <div class="form-group">
                                        <label for="kode_klasifikasi">Kode Klasifikasi:</label>
                                        <select name="kode_klasifikasi" id="kode_klasifikasi" class="form-control" required>
                                            <option value="">- Pilih Kode Klasifikasi -</option>
                                            @foreach ($kodeSurat as $kode)
                                                <option value="{{ $kode->id }}">{{ $kode->kode_klasifikasi }} -
                                                    {{ $kode->nama_kode }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 text-center">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> Simpan Surat Perintah
                                </button>
                                <button type="reset" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-redo"></i> Reset Form
                                </button>
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
