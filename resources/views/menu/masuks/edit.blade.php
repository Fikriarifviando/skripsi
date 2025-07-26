@extends('layouts.app')

@section('title', 'Edit Surat')
@section('page-title', 'Edit Surat')

@push('style')
    <!-- CSS Libraries -->
@endpush


@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('surat-masuk.update', $suratMasuk) }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="asal_surat">Asal Surat</label>
                            <input type="text" class="form-control @error('asal_surat') is-invalid @enderror"
                                id="asal_surat" name="asal_surat" value="{{ old('asal_surat', $suratMasuk->asal_surat) }}"
                                placeholder="Masukkan asal surat">
                            @error('asal_surat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="nomor_surat">Nomor Surat</label>
                            <input type="text" class="form-control @error('nomor_surat') is-invalid @enderror"
                                id="nomor_surat" name="nomor_surat" value="{{ old('nomor_surat', $suratMasuk->no_surat) }}"
                                placeholder="Masukkan nomor surat">
                            @error('nomor_surat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="perihal">Perihal</label>
                            <textarea class="form-control @error('perihal') is-invalid @enderror" id="perihal" name="perihal"
                                style="height: 300px;" placeholder="Masukkan perihal surat...">{{ old('perihal', $suratMasuk->perihal) }}</textarea>
                            @error('perihal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="kode_klasifikasi">Kode Klasifikasi</label>
                            <select class="form-control @error('kode_klasifikasi') is-invalid @enderror"
                                id="kode_klasifikasi" name="kode_klasifikasi">
                                @foreach ($kodeSurat as $kode)
                                    <option value="{{ $kode->id }}"
                                        {{ old('kode_klasifikasi', $suratMasuk->kode_surat_id) == $kode->id ? 'selected' : '' }}>
                                        {{ $kode->kode_klasifikasi }} - {{ $kode->nama_kode }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kode_klasifikasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="file_surat">Upload file surat</label>
                            @if ($suratMasuk->nama_file)
                                <div class="mb-2">
                                    <span>File saat ini: {{ $suratMasuk->nama_file }}</span>
                                    <a href="{{ Storage::url($suratMasuk->path_file) }}" class="btn btn-sm btn-info ms-2"
                                        target="_blank">
                                        Download
                                    </a>
                                </div>
                            @endif
                            <input type="file" class="form-control @error('file_surat') is-invalid @enderror"
                                id="file_surat" name="file_surat">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah file</small>
                            @error('file_surat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal"
                                name="tanggal"
                                value="{{ old('tanggal', $suratMasuk->tgl_terima ? date('Y-m-d', strtotime($suratMasuk->tgl_terima)) : '') }}"
                                max="{{ date('Y-m-d') }}">
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary px-4">Tambah</button>
                </div>
            </form>

        </div>
    </div>
@endsection
