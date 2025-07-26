@extends('layouts.app')

@section('title', 'Edit Surat Keterangan')
@section('page-title', 'Edit Surat Keterangan')

@section('content')
    <div class="card">
        <div class="card-body">
            <form id="form-edit" action="{{ route('keterangan.update', $suratKeluar) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card border">
                    <div class="card-header">
                        <h5>Edit Data Diri</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 row">
                            <label for="nama" class="col-sm-2 col-form-label">Nama Lengkap</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                    id="nama" name="nama"
                                    value="{{ old('nama', json_decode($suratKeluar->metadata)->informasi_pribadi->nama) }}"
                                    required>
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
                                        value="Laki-laki"
                                        {{ old('jenis_kelamin', json_decode($suratKeluar->metadata)->informasi_pribadi->jenis_kelamin) == 'Laki-laki' ? 'checked' : '' }}
                                        required>
                                    <label class="form-check-label" for="laki-laki">Laki-laki</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="perempuan"
                                        value="Perempuan"
                                        {{ old('jenis_kelamin', json_decode($suratKeluar->metadata)->informasi_pribadi->jenis_kelamin) == 'Perempuan' ? 'checked' : '' }}>
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
                                    id="tempat_lahir" name="tempat_lahir"
                                    value="{{ old('tempat_lahir', json_decode($suratKeluar->metadata)->informasi_pribadi->tempat_lahir) }}"
                                    required>
                                @error('tempat_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="tanggal_lahir" class="col-sm-2 col-form-label">Tanggal Lahir</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                    id="tanggal_lahir" name="tanggal_lahir"
                                    value="{{ old('tanggal_lahir', json_decode($suratKeluar->metadata)->informasi_pribadi->tanggal_lahir) }}"
                                    required max="{{ now()->subYears(10)->format('Y-m-d') }}">
                                @error('tanggal_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="nis_nisn" class="col-sm-2 col-form-label">NIS/NISN</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('nis_nisn') is-invalid @enderror"
                                    id="nis_nisn" name="nis_nisn"
                                    value="{{ old('nis_nisn', json_decode($suratKeluar->metadata)->informasi_akademik->nis_nisn) }}"
                                    required>
                                @error('nis_nisn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="kelas" class="col-sm-2 col-form-label">Kelas</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('kelas') is-invalid @enderror"
                                    id="kelas" name="kelas"
                                    value="{{ old('kelas', json_decode($suratKeluar->metadata)->informasi_akademik->kelas) }}"
                                    required>
                                @error('kelas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email"
                                    value="{{ old('email', json_decode($suratKeluar->metadata)->informasi_kontak->email) }}"
                                    required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                            <div class="col-sm-10">
                                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3"
                                    required>{{ old('alamat', json_decode($suratKeluar->metadata)->informasi_kontak->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="keperluan" class="col-sm-2 col-form-label">Keperluan</label>
                            <div class="col-sm-10">
                                <textarea class="form-control @error('keperluan') is-invalid @enderror" id="keperluan" name="keperluan"
                                    rows="3" required>{{ old('keperluan', json_decode($suratKeluar->metadata)->keperluan) }}</textarea>
                                @error('keperluan')
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
                                        <option value="{{ $kode->id }}"
                                            {{ $suratKeluar->kode_surat_id == $kode->id ? 'selected' : '' }}>
                                            {{ $kode->kode_klasifikasi }} - {{ $kode->nama_kode }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('keluar.master') }}" class="btn btn-secondary mr-2">Batal</a>
                    <button type="button" id="btn-konfirmasi" class="btn btn-primary">Simpan Perubahan</button>
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
                    <h5 class="modal-title" id="konfirmasiModalLabel">Konfirmasi Update Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin mengubah data surat keterangan ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn-simpan">Ya, Simpan</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $("#btn-konfirmasi").click(function() {
                if (!$("#form-edit")[0].checkValidity()) {
                    $("#form-edit")[0].reportValidity();
                    return false;
                }

                var tanggalLahir = new Date($("#tanggal_lahir").val());
                var hariIni = new Date();
                var batasTanggal = new Date(hariIni.getFullYear() - 10, hariIni.getMonth(), hariIni
                .getDate());

                if (tanggalLahir > batasTanggal) {
                    alert("Tanggal lahir tidak valid. Umur harus minimal 10 tahun.");
                    return false;
                }

                $("#konfirmasiModal").modal('show');
            });

            $("#btn-simpan").click(function() {
                $("#konfirmasiModal").modal('hide');

                var formData = $("#form-edit").serialize();

                $.ajax({
                    type: "POST",
                    url: $("#form-edit").attr('action'),
                    data: formData,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location.href = "{{ route('keluar.master') }}";
                        } else {
                            alert("Terjadi kesalahan: " + response.message);
                        }
                    },
                    error: function(xhr) {
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
