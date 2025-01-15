@extends('layouts.app')

@section('title', 'Edit Data Hak Akses')
@section('page-title', 'Edit Data Hak Akses')


@push('style')
    <!-- CSS Libraries -->
@endpush

@section('content')
    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <form id="updateForm" action="{{ route('hakakses.update', $hakakses->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="role">
                            <h4>{{ __('Silahkan Ganti Role') }}</h4>
                        </label>
                        <select name="role" id="role" class="form-control">
                            <option value="user" {{ $hakakses->role == 'user' ? 'selected' : '' }}>User</option>
                            @if (auth()->user()->role == 'superadmin')
                                <option value="admin" {{ $hakakses->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="superadmin" {{ $hakakses->role == 'superadmin' ? 'selected' : '' }}>
                                    Superadmin</option>
                            @endif
                        </select>
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
