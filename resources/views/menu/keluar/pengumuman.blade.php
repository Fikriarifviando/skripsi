@extends('layouts.app')

@section('title', 'Form Surat Pengumuman')
@section('page-title', 'Form Surat Pengumuman')

@push('style')
@endpush

@section('content')
    <div class="section-body">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="suratPengumumanForm" method="POST" action="{{ route('pengumuman.store') }}">
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
                            <div class="form-group">
                                <label for="perihal">Perihal:</label>
                                <textarea class="form-control @error('perihal') is-invalid @enderror" id="perihal" name="perihal" rows="3"
                                    placeholder="Masukkan perihal surat">{{ old('perihal') }}</textarea>
                                @error('perihal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="pembuka">Pembuka Surat:</label>
                                <textarea class="form-control @error('pembuka') is-invalid @enderror" id="pembuka" name="pembuka" rows="3"
                                    placeholder="Masukkan pembuka surat">{{ old('pembuka') }}</textarea>
                                @error('pembuka')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="body">Body Surat:</label>
                                <textarea class="form-control @error('body') is-invalid @enderror" id="body" name="body" rows="5"
                                    placeholder="Masukkan isi surat">{{ old('body') }}</textarea>
                                @error('body')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
        // Fungsi untuk mereset form
        function resetForm() {
            document.getElementById('perihal').value = '';
            document.getElementById('pembuka').value = '';
            document.getElementById('body').value = '';

            Swal.fire({
                icon: 'success',
                title: 'Form direset',
                showConfirmButton: false,
                timer: 1500
            });
        }
    </script>
@endpush
