@extends('layouts.app')

@section('title', 'Hak Akses')
@section('page-title', 'Hak Akses')

@push('style')
    <!-- CSS Libraries -->
@endpush


@section('content')
    <section class="section">
        <div class="section-body">
            <div class="card">
                {{-- <div class="card-header">
                    
                        {{-- <div class="col-md-6">
                            <a href="{{ route('hakakses.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Baru
                            </a>
                        </div>
                </div> --}}
                <div class="col-md-6 py-2 d-flex align-items-center">
                    <a href="{{ route('hakakses.create') }}" class="btn btn-success mr-3 d-flex align-items-center"
                        role="button">
                        <i class="fa fa-plus-circle mr-1"></i> Tambah User
                    </a>
                    <div class="input-group border rounded flex-grow-1">
                        <div class="input-group-prepend border-0">
                            <span class="input-group-text border-0">
                                <i class="fa fa-search text-muted"></i>
                            </span>
                        </div>
                        <input type="text" id="pencarian" class="form-control border-0"
                            placeholder="Cari Berdasarkan Nama atau Role">
                    </div>
                </div>


                <div class="card-body">
                    <div class="table-responsive table-striped">
                        <table class="table" id="datatable">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </section>

    <!-- Modal Konfirmasi Delete -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus data ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                ordering: false,
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{{ route('hakakses.index') }}",
                    data: function(d) {
                        d.pencarian = $('#pencarian').val()
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#pencarian').on('keyup', function() {
                $('#datatable').DataTable().draw(true);
            });
        });

        function confirmDelete(deleteUrl) {
            $('#deleteForm').attr('action', deleteUrl);
            $('#deleteConfirmModal').modal('show');
        };

        document.addEventListener('DOMContentLoaded', function() {
            // Konfigurasi Global iziToast
            iziToast.settings({
                position: 'topRight',
                transitionIn: 'fadeInDown',
                transitionOut: 'fadeOutUp',
                closeOnClick: true,
                pauseOnHover: true,
                timeout: 5000
            });

            // Cek session flash
            @if (session('success'))
                iziToast.success({
                    title: 'Sukses',
                    message: '{{ session('success') }}'
                });
            @endif

            @if (session('error'))
                iziToast.warning({
                    title: 'Gagal',
                    message: '{{ session('error') }}'
                });
            @endif

            @if (session('status'))
                iziToast.error({
                    title: 'Berhasil',
                    message: '{{ session('status') }}'
                });
            @endif
        });
    </script>
@endpush
