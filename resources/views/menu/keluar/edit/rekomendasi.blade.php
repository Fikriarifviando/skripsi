@extends('layouts.app')

@section('title', 'Edit surat rekomendasi')
@section('page-title', 'Edit surat rekomendasi')

@section('content')
    <div class="card">
        <div class="card-body">
            @if($suratKeluar->status == 'revisi')
            <div class="alert alert-warning">
                <strong>Catatan Revisi:</strong>
                <p>{{ $suratKeluar->komentar }}</p>
            </div>
            @endif

            <form id="form-edit" action="{{ route('rekomendasi.update', $suratKeluar) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card border">
                    <div class="card-header">
                        <h5>Data Diri</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 row">
                            <label for="nama" class="col-sm-2 col-form-label">Nama Lengkap</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                    id="nama" name="nama" value="{{ old('nama', $metadata['informasi_pribadi']['nama']) }}" required>
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
                                        value="Laki-laki" {{ old('jenis_kelamin', $metadata['informasi_pribadi']['jenis_kelamin']) == 'Laki-laki' ? 'checked' : '' }}
                                        required>
                                    <label class="form-check-label" for="laki-laki">Laki-laki</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="perempuan"
                                        value="Perempuan" {{ old('jenis_kelamin', $metadata['informasi_pribadi']['jenis_kelamin']) == 'Perempuan' ? 'checked' : '' }}>
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
                                    id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $metadata['informasi_pribadi']['tempat_lahir']) }}" required>
                                @error('tempat_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="tanggal_lahir" class="col-sm-2 col-form-label">Tanggal Lahir</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                    id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $metadata['informasi_pribadi']['tanggal_lahir']) }}" required
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
                                    id="nis_nisn" name="nis_nisn" value="{{ old('nis_nisn', $metadata['informasi_akademik']['nis_nisn']) }}" required>
                                @error('nis_nisn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="kelas" class="col-sm-2 col-form-label">Kelas</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('kelas') is-invalid @enderror"
                                    id="kelas" name="kelas" value="{{ old('kelas', $metadata['informasi_akademik']['kelas']) }}" required>
                                @error('kelas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email', $metadata['informasi_kontak']['email']) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                            <div class="col-sm-10">
                                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3"
                                    required>{{ old('alamat', $metadata['informasi_kontak']['alamat']) }}</textarea>
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
                                        <option value="{{ $kode->id }}" {{ $suratKeluar->kode_surat_id == $kode->id ? 'selected' : '' }}>
                                            {{ $kode->kode_klasifikasi }} - {{ $kode->nama_kode }}
                                        </option>
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
                                    id="nama_acara" name="nama_acara" value="{{ old('nama_acara', $metadata['informasi_acara']['nama_acara']) }}" required>
                                @error('nama_acara')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="penyelenggara" class="col-sm-2 col-form-label">Penyelenggara</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('penyelenggara') is-invalid @enderror"
                                    id="penyelenggara" name="penyelenggara" value="{{ old('penyelenggara', $metadata['informasi_acara']['penyelenggara']) }}" required>
                                @error('penyelenggara')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="tanggal_acara" class="col-sm-2 col-form-label">tanggal acara</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('tanggal_acara') is-invalid @enderror"
                                    id="tanggal_acara" name="tanggal_acara" value="{{ old('tanggal_acara', $metadata['informasi_acara']['tanggal_acara']) }}" required>
                                @error('tanggal_acara')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection