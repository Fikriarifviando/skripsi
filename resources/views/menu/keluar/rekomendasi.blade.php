@extends('layouts.app')

@section('title', 'Form surat rekomendasi')
@section('page-title', 'Form surat rekomendasi')

@section('content')
    <div class="card">
        <div class="card-body">
            <form id="form-registrasi" action="{{ route('rekomendasi.store') }}" method="POST">
                @csrf
                <div class="card border">
                    <div class="card-header">
                        <h5>Data Diri</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 row">
                            <label for="nama" class="col-sm-2 col-form-label">Nama Lengkap</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                    id="nama" name="nama" value="{{ old('nama') }}" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="jenis_kelamin" class="col-sm-2 col-form-label">Jenis Kelamin</label>
                            <div class="col-sm-10">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="laki-laki"
                                        value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'checked' : '' }}
                                        required>
                                    <label class="form-check-label" for="laki-laki">Laki-laki</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="perempuan"
                                        value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="perempuan">Perempuan</label>
                                </div>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="tempat_lahir" class="col-sm-2 col-form-label">Tempat Lahir</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror"
                                    id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required>
                                @error('tempat_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="tanggal_lahir" class="col-sm-2 col-form-label">Tanggal Lahir</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                    id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required
                                    max="{{ now()->subYears(10)->format('Y-m-d') }}">

                                @error('tanggal_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="nis_nisn" class="col-sm-2 col-form-label">NIS/NISN</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('nis_nisn') is-invalid @enderror"
                                    id="nis_nisn" name="nis_nisn" value="{{ old('nis_nisn') }}" required>
                                @error('nis_nisn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="kelas" class="col-sm-2 col-form-label">Kelas</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('kelas') is-invalid @enderror"
                                    id="kelas" name="kelas" value="{{ old('kelas') }}" required>
                                @error('kelas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                            <div class="col-sm-10">
                                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3"
                                    required>{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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
                </div>

                <div class="card border">
                    <div class="card-header">
                        <h5>Data Acara</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 row">
                            <label for="nama_acara" class="col-sm-2 col-form-label">Nama Acara</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('nama_acara') is-invalid @enderror"
                                    id="nama_acara" name="nama_acara" value="{{ old('nama_acara') }}" required>
                                @error('nama_acara')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="penyelenggara" class="col-sm-2 col-form-label">Penyelenggara</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('penyelenggara') is-invalid @enderror"
                                    id="penyelenggara" name="penyelenggara" value="{{ old('penyelenggara') }}" required>
                                @error('penyelengga
                                    ra')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="tanggal_acara" class="col-sm-2 col-form-label">tanggal acara</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('tanggal_acara') is-invalid @enderror"
                                    id="tanggal_acara" name="tanggal_acara" value="{{ old('tanggal_acara') }}" required>
                                @error('tanggal_acara')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" id="btn-konfirmasi" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Konfirmasi -->
    <div class="modal fade" id="konfirmasiModal" tabindex="-1" role="dialog" aria-labelledby="konfirmasiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="konfirmasiModalLabel">Konfirmasi Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin data yang diinput sudah benar?</p>
                    <p class="text-danger font-weight-bold">Perhatian: Admin tidak bertanggung jawab atas kesalahan data
                        yang Anda input.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn-simpan">Ya, Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Kode Unik -->
    <div class="modal fade" id="kodeUnikModal" tabindex="-1" role="dialog" aria-labelledby="kodeUnikModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h4>Kode Surat:</h4>
                    <div class="p-3 mb-3 bg-light rounded">
                        <h2 id="kode-display" class="font-weight-bold text-primary"></h2>
                    </div>
                    <p id="email-status-message"></p>
                    <p class="text-muted">Silakan catat kode ini untuk keperluan pencarian surat.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Loading -->
    <div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true" data-backdrop="static"
        data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <div class="d-flex justify-content-center mb-3">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <h5>Sedang memproses data...</h5>
                    <p class="text-muted mb-0">Mohon tunggu sebentar</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Ketika tombol konfirmasi diklik
            $("#btn-konfirmasi").click(function() {
                // Periksa validitas formulir terlebih dahulu
                if (!$("#form-registrasi")[0].checkValidity()) {
                    // Memicu UI validasi native browser
                    $("#form-registrasi")[0].reportValidity();
                    return false;
                }

                // Jika valid, tampilkan modal konfirmasi
                $("#konfirmasiModal").modal('show');
            });

            // Ketika tombol simpan pada modal konfirmasi diklik
            $("#btn-simpan").click(function() {
                // Tutup modal konfirmasi
                $("#konfirmasiModal").modal('hide');
                $("#loadingModal").modal('show');

                // Submit form via AJAX
                var formData = $("#form-registrasi").serialize();

                $.ajax({
                    type: "POST",
                    url: $("#form-registrasi").attr('action'),
                    data: formData,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log('Response:',
                            response); // Tambahkan log untuk melihat response
                        $("#loadingModal").modal('hide');

                        if (response.success) {
                            // Tampilkan kode unik di modal
                            $("#kode-display").text(response.kode_uniq);

                            // Tampilkan pesan status email
                            if (response.email_status === true) { // Pengecekan yang lebih ketat
                                $("#email-status-message").html(
                                    'Kode Surat telah dikirim ke alamat email yang terdaftar.'
                                );
                                $("#email-status-message").removeClass('text-danger').addClass(
                                    'text-success');
                            } else {
                                $("#email-status-message").html(
                                    'Kode Surat gagal dikirim. Silakan hubungi admin.'
                                );
                                $("#email-status-message").removeClass('text-success').addClass(
                                    'text-danger');
                            }

                            // Tampilkan modal kode unik
                            $("#kodeUnikModal").modal('show');

                            // Reset form
                            $("#form-registrasi")[0].reset();
                        } else {
                            // Jika ada error
                            alert("Terjadi kesalahan: " + response.message);
                        }
                    },
                    error: function(xhr) {
                        $("#loadingModal").modal('hide');
                        // Handle validation errors
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            var errorMessage = "Mohon perbaiki kesalahan berikut:\n";

                            $.each(errors, function(key, value) {
                                errorMessage += "- " + value[0] + "\n";
                            });

                            alert(errorMessage);
                        } else {
                            alert("Terjadi kesalahan. Silakan coba lagi nanti.");
                        }
                    }
                });
            });
        });
    </script>
@endpush
