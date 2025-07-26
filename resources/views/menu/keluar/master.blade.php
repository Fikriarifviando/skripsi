@extends('layouts.app')

@section('title', 'Surat Keluar')
@section('page-title', 'Daftar Surat Keluar')

@push('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6 mb-2">
                    <div class="input-group border rounded">
                        <div class="input-group-prepend border-0">
                            <span class="input-group-text border-0">
                                <i class="fa fa-search text-muted"></i>
                            </span>
                        </div>
                        <input type="text" id="pencarian" class="form-control border-0"
                            placeholder="cari nomor surat, metadata atau kode">
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <select id="filter-jenis-surat" class="form-control">
                        <option value="">Semua Jenis Surat</option>
                        <option value="rekomendasi">Surat Rekomendasi</option>
                        <option value="keterangan">Surat Keterangan</option>
                        <option value="undangan">Surat Undangan</option>
                        {{-- <option value="pengumuman">Surat Pengumuman</option> --}}
                        <option value="perintah">Surat Perintah</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive table-striped">
                <table class="table table-hover" id="suratkeluar-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Surat Keluar</th>
                            <th>Jenis Surat</th>
                            <th>Metadata</th>
                            <th>Status</th>
                            <th>Kode Klasifikasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Delete -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <!-- UBAH INI -->
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus surat ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button> <!-- UBAH INI -->
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('PUT')
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
            // Simpan instance DataTable ke dalam variabel
            let table = $('#suratkeluar-table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ordering: true,
                ajax: {
                    url: "{{ route('keluar.master') }}",
                    data: function(d) {
                        d.pencarian = $('#pencarian').val();
                        d.jenis_surat = $('#filter-jenis-surat').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'no_surat_keluar',
                        name: 'no_surat_keluar',
                        render: function(data) {
                            return data || '-';
                        }
                    },
                    {
                        data: 'jenis_surat',
                        name: 'jenis_surat',
                        render: function(data) {
                            return data || '-';
                        }
                    },
                    {
                        data: 'metadata',
                        name: 'metadata',
                        render: function(data) {
                            return data || '-';
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data) {
                            return data || '-';
                        }
                    },
                    {
                        data: 'kode_klasifikasi',
                        name: 'kode_klasifikasi',
                        render: function(data) {
                            return data || '-';
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        render: function(data) {
                            return data || '-';
                        }
                    }
                ],
            });

            // Event listener untuk filter jenis surat
            $('#filter-jenis-surat').change(function() {
                table.draw();
            });

            // Event listener untuk pencarian
            $('#pencarian').on('keyup', function() {
                table.draw();
            });
        });

        function confirmDelete(url) {
            $('#deleteModal').modal('show');
            $('#deleteForm').attr('action', url);
        }
    </script>
@endpush
