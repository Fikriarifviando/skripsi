@extends('layouts.app')

@section('title', 'Data Klasifikasi')
@section('page-title', 'Data Klasifikasi')

@section('content')
    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <form id="updateForm" action="{{ route('data-pegawai.update', $pegawai->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nama">
                            <h4>{{ __('Nama') }}</h4>
                        </label>
                        <input type="text" name="nama" id="nama"
                            class="form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama', $pegawai->nama) }}" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nip">
                            <h4>{{ __('NIP') }}</h4>
                        </label>
                        <input type="text" name="nip" id="nip"
                            class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip', $pegawai->nip) }}"
                            required>
                        @error('nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="jabatan_id">
                            <h4>{{ __('Jabatan') }}</h4>
                        </label>
                        <select name="jabatan_id" id="jabatan_id"
                            class="form-control @error('jabatan_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Jabatan --</option>
                            @foreach ($jabatans as $jabatan)
                                <option value="{{ $jabatan->id }}"
                                    {{ old('jabatan_id', $pegawai->jabatan_id) == $jabatan->id ? 'selected' : '' }}>
                                    {{ $jabatan->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('jabatan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label for="email">
                            <h4>{{ __('Email') }}</h4>
                        </label>
                        <input type="text" name="email" id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $pegawai->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="button" onclick="confirmUpdate()" class="btn btn-primary btn-lg">Update</button>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Update</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin mengupdate data ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="submitForm()">Ya, Update</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmUpdate() {
            $('#confirmModal').modal('show');
        }

        function submitForm() {
            document.getElementById('updateForm').submit();
        }
    </script>
@endpush
