@extends('layouts.app')

@section('title', 'Edit Surat Undangan')
@section('page-title', 'Edit Surat Undangan')

@push('style')
@endpush

@section('content')
    <div class="section-body">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="suratUndanganForm" action="{{ route('undangan.update', $suratKeluar) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <div class="form-group mb-3">
                                <label for="kode_klasifikasi">Kode Klasifikasi</label>
                                <select class="form-control @error('kode_klasifikasi') is-invalid @enderror"
                                    id="kode_klasifikasi" name="kode_klasifikasi">
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
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kepada">Kepada Siapa:</label>
                                        <input type="text" class="form-control @error('kepada') is-invalid @enderror" 
                                            id="kepada" name="kepada" value="{{ json_decode($suratKeluar->metadata)->kepada ?? '' }}"
                                            placeholder="Masukkan nama penerima surat">
                                        @error('kepada')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kota">Kota:</label>
                                        <input type="text" class="form-control @error('kota') is-invalid @enderror" 
                                            id="kota" name="kota" value="{{ json_decode($suratKeluar->metadata)->kota ?? '' }}"
                                            placeholder="Masukkan nama kota">
                                        @error('kota')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tempat">Tempat Pelaksanaan Acara:</label>
                                        <input type="text" class="form-control @error('tempat') is-invalid @enderror" 
                                            id="tempat" name="tempat" value="{{ json_decode($suratKeluar->metadata)->tempat ?? '' }}"
                                            placeholder="Masukkan tempat pelaksanaan acara">
                                        @error('tempat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal">Tanggal:</label>
                                        <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                                            id="tanggal" name="tanggal" value="{{ json_decode($suratKeluar->metadata)->tanggal ?? '' }}">
                                        @error('tanggal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="acara">Acara Yang Dilaksanakan:</label>
                                <textarea class="form-control @error('acara') is-invalid @enderror" id="acara" 
                                    name="acara" rows="3" placeholder="Masukkan deskripsi acara">{{ json_decode($suratKeluar->metadata)->acara ?? '' }}</textarea>
                                @error('acara')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="waktu-mulai">Waktu Mulai:</label>
                                        <input type="time" class="form-control @error('waktu_mulai') is-invalid @enderror" 
                                            id="waktu-mulai" name="waktu_mulai" min="03:00" max="22:00"
                                            value="{{ json_decode($suratKeluar->metadata)->waktu_mulai ?? '' }}">
                                        @error('waktu_mulai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="waktu-selesai">Waktu Selesai:</label>
                                        <input type="time" class="form-control @error('waktu_selesai') is-invalid @enderror" 
                                            id="waktu-selesai" name="waktu_selesai" min="03:00" max="22:00"
                                            value="{{ json_decode($suratKeluar->metadata)->waktu_selesai ?? '' }}">
                                        @error('waktu_selesai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="alamat">Alamat Tempat:</label>
                                <input type="text" class="form-control @error('alamat') is-invalid @enderror" 
                                    id="alamat" name="alamat" value="{{ json_decode($suratKeluar->metadata)->alamat ?? '' }}"
                                    placeholder="Masukkan alamat lengkap tempat pelaksanaan">
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Perbarui
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
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mulaiInput = document.getElementById('waktu-mulai');
        const selesaiInput = document.getElementById('waktu-selesai');

        function validateWaktu() {
            const mulai = mulaiInput.value;
            const selesai = selesaiInput.value;

            if (mulai && selesai && selesai < mulai) {
                Swal.fire({
                    icon: 'error',
                    title: 'Waktu tidak valid',
                    text: 'Waktu selesai tidak boleh lebih awal dari waktu mulai!',
                });
                selesaiInput.value = '';
            }
        }

        mulaiInput.addEventListener('input', function () {
            if (mulaiInput.value) {
                selesaiInput.setAttribute('min', mulaiInput.value);
            }
            validateWaktu();
        });

        selesaiInput.addEventListener('input', validateWaktu);
    });
</script>
@endpush