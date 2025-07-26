@extends('layouts.app')

@section('title', 'Surat Masuk')
@section('page-title', 'Daftar Surat Masuk')

@push('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
    <div class="card">
        <div class="col-md-6 py-2 d-flex align-items-center">
            @if (Auth::user()->role == 'superadmin')
            <a href="{{ route('surat-masuk.create') }}" class="btn btn-success mr-3 d-flex align-items-center" role="button">
                <i class="fa fa-plus-circle mr-1"></i> Tambah Surat Masuk
            </a>
            @endif
            <div class="input-group border rounded flex-grow-1">
                <div class="input-group-prepend border-0">
                    <span class="input-group-text border-0">
                        <i class="fa fa-search text-muted"></i>
                    </span>
                </div>
                <input type="text" id="pencarian" class="form-control border-0"
                    placeholder="cari perihal,asal,nomor atau kode">
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive table-striped">
                <table class="table table-hover" id="suratmasuk-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Surat</th>
                            <th>Perihal</th>
                            <th>Asal Surat</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Kode Klasifikasi</th>
                            {{-- <th>File</th> --}}
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Delete -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus surat ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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
            $('#suratmasuk-table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ordering: true,
                ajax: {
                    url: "{{ route('surat-masuk.index') }}",
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
                        data: 'no_surat',
                        name: 'no_surat'
                    },
                    {
                        data: 'perihal',
                        name: 'perihal'
                    },
                    {
                        data: 'asal_surat',
                        name: 'asal_surat'
                    },
                    {
                        data: 'tgl_terima',
                        name: 'tgl_terima'
                    },
                    {
                        data: 'status_disposisi',
                        name: 'status_disposisi'
                    },
                    {
                        data: 'kode_klasifikasi',
                        name: 'kode_klasifikasi'
                    },
                    // {
                    //     data: 'file',
                    //     name: 'file',
                    //     orderable: false,
                    //     searchable: false
                    // },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [4, 'desc']
                ] // Urutkan berdasarkan tanggal terbaru
            });

            $('#pencarian').on('keyup', function() {
                $('#suratmasuk-table').DataTable().draw(true);
            });
        });

        function confirmDelete(url) {
            $('#deleteModal').modal('show');
            $('#deleteForm').attr('action', url);
        }
    </script>
@endpush
