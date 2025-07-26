@extends('layouts.app')

@section('title', 'Surat Undangan')
@section('page-title', 'Surat Undangan')

@push('style')
@endpush

@section('content')
    <div class="section-body">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="suratUndanganForm" action="{{ route('undangan.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <div class="form-group mb-3">
                                <label for="kode_klasifikasi">Kode Klasifikasi</label>
                                <select class="form-control @error('kode_klasifikasi') is-invalid @enderror"
                                    id="kode_klasifikasi" name="kode_klasifikasi">
                                    @foreach ($kodeSurat as $kode)
                                        <option value="{{ $kode->id }}"
                                            {{ old('kode_klasifikasi') == $kode->id ? 'selected' : '' }}>
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
                                        <input type="text" class="form-control" id="kepada" name="kepada"
                                            placeholder="Masukkan nama penerima surat">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kota">Kota:</label>
                                        <input type="text" class="form-control" id="kota" name="kota"
                                            placeholder="Masukkan nama kota">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tempat">Tempat Pelaksanaan Acara:</label>
                                        <input type="text" class="form-control" id="tempat" name="tempat"
                                            placeholder="Masukkan tempat pelaksanaan acara">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal">Tanggal:</label>
                                        @php
                                            $besok = \Carbon\Carbon::tomorrow()->format('Y-m-d');
                                        @endphp
                                        <input type="date" class="form-control" id="tanggal" name="tanggal"
                                            min="{{ $besok }}">

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="acara">Acara Yang Dilaksanakan:</label>
                                <textarea class="form-control" id="acara" name="acara" rows="3" placeholder="Masukkan deskripsi acara"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="waktu-mulai">Waktu Mulai:</label>
                                        <input type="time" class="form-control" id="waktu-mulai" name="waktu_mulai"
                                            min="03:00" max="22:00">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="waktu-selesai">Waktu Selesai:</label>
                                        <input type="time" class="form-control" id="waktu-selesai" name="waktu_selesai"
                                            min="03:00" max="22:00">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="alamat">Alamat Tempat:</label>
                                <input type="text" class="form-control" id="alamat" name="alamat"
                                    placeholder="Masukkan alamat lengkap tempat pelaksanaan">
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="ion ion-archive"></i> Simpan
                            </button>
                            <button type="button" class="btn btn-secondary btn-lg" onclick="resetForm()">
                                <i class="fas fa-redo"></i> Reset Form
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function resetForm() {
        document.getElementById('kepada').value = '';
        document.getElementById('kota').value = '';
        document.getElementById('acara').value = '';
        document.getElementById('tanggal').value = '';
        document.getElementById('waktu-mulai').value = '';
        document.getElementById('waktu-selesai').value = '';
        document.getElementById('tempat').value = '';
        document.getElementById('alamat').value = '';

        Swal.fire({
            icon: 'success',
            title: 'Form direset',
            showConfirmButton: false,
            timer: 1500
        });
    }

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

