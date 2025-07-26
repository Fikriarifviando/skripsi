@extends('layouts.app')

@section('title', 'Surat Disposisi')
@section('page-title', 'Surat Disposisi')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('content')
    <div class="section-body">
        <div class="card">
            <div class="col-md-6 py-2 d-flex align-items-center">
            <div class="input-group border rounded flex-grow-1">
                <div class="input-group-prepend border-0">
                    <span class="input-group-text border-0">
                        <i class="fa fa-search text-muted"></i>
                    </span>
                </div>
                <input type="text" id="pencarian" class="form-control border-0"
                    placeholder="cari perihal,asal,nomor, kode, atau nama">
            </div>
        </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabel-disposisi" class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>No Surat</th>
                                <th>Asal Surat</th>
                                <th>Tanggal Terima</th>
                                <th>Kode Klasifikasi</th>
                                <th>Pegawai Penerima</th>
                                <th>Status Disposisi</th>
                                <th>Catatan</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- DataTables akan mengisi data di sini -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus surat masuk ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <form id="delete-form" method="POST">
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
            let table = $('#tabel-disposisi').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ordering: false,
                ajax: {
                    url: "{{ route('surat-disposisi.index') }}",
                    data: function(d) {
                        d.pencarian = $('#pencarian').val();
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
                        data: 'asal_surat',
                        name: 'asal_surat'
                    },
                    {
                        data: 'tgl_terima',
                        name: 'tgl_terima'
                    },
                    {
                        data: 'kode_klasifikasi',
                        name: 'kode_klasifikasi'
                    },
                    {
                        data: 'nama',
                        nama: 'nama',
                        render: function(data, type, row) {
                            return '<span class="badge bg-primary text-white">' + data + '</span>';
                        }
                    }, // Pegawai yang menerima
                    {
                        data: 'status_disposisi',
                        name: 'status_disposisi'
                    },
                    {
                        data: 'catatan',
                        name: 'catatan'
                    }, // Catatan disposisi
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [
                    [3, 'desc']
                ], // Urutkan berdasarkan tanggal terbaru
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                }
            });

            // Fungsi pencarian
            $('#btn-search').click(function() {
                table.draw();
            });

            $('#pencarian').on('keyup', function(e) {
                if (e.which == 13) { // Tekan Enter untuk mencari
                    table.search($(this).val()).draw();
                }
            });
        });

        // Fungsi konfirmasi hapus
        function confirmDelete(url) {
            $('#delete-form').attr('action', url);
            $('#deleteModal').modal('show');
        }
    </script>
@endpush
