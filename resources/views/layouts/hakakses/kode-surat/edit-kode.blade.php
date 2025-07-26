@extends('layouts.app')

@section('title', 'Data Klasifikasi')
@section('page-title', 'Data Klasifikasi')

@section('content')
    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <form id="updateForm" action="{{ route('kode-surat.update', $kodeSurat->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="kodeKlasifikasi">
                            <h4>{{ __('Kode Klasifikasi') }}</h4>
                        </label>
                        <input type="text" 
                               name="kodeKlasifikasi" 
                               id="kodeKlasifikasi" 
                               class="form-control @error('kodeKlasifikasi') is-invalid @enderror"
                               value="{{ old('kodeKlasifikasi', $kodeSurat->kode_klasifikasi) }}"
                               required>
                        @error('kodeKlasifikasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="namaKode">
                            <h4>{{ __('Nama Kode') }}</h4>
                        </label>
                        <input type="text" 
                               name="namaKode" 
                               id="namaKode" 
                               class="form-control @error('namaKode') is-invalid @enderror"
                               value="{{ old('namaKode', $kodeSurat->nama_kode) }}"
                               required>
                        @error('namaKode')
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