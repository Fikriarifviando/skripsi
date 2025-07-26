@extends('layouts.app')

@section('title', 'Detail Surat')
@section('page-title', 'Detail Surat')

@push('style')
    <style>
        .scroll-area {
            height: 300px;
            max-height: calc(60px * 5);
            /* 5 items dengan tinggi 60px */
            overflow-y: auto;
        }

        .pegawai-item {
            height: 60px;
            margin-bottom: 8px !important;
            padding: 8px !important;
        }
    </style>
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <!-- Card Detail Surat -->
                    <div class="card mb-4 card-primary">
                        <div class="card-body ">
                            <div class="row mb-3">
                                <div class="col-md-4 font-weight-bold">Nomor Surat</div>
                                <div class="col-md-8">{{ $suratMasuk->no_surat }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 font-weight-bold">Perihal</div>
                                <div class="col-md-8">{{ $suratMasuk->perihal }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 font-weight-bold">Asal Surat</div>
                                <div class="col-md-8">{{ $suratMasuk->asal_surat }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 font-weight-bold">Kode Surat</div>
                                <div class="col-md-8">{{ $suratMasuk->kodeSurat->kode_klasifikasi }} -
                                    {{ $suratMasuk->kodeSurat->nama_kode }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 font-weight-bold">File Surat</div>
                                <div class="col-md-8">
                                    <a href="{{ Storage::url($suratMasuk->path_file) }}" target="_blank"
                                        class="btn btn-sm btn-info">
                                        Lihat File
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4 card-success">
                        <div class="card-header">
                            <h5>
                                @if ($suratMasuk->status_disposisi === 'belum_ditentukan')
                                    Form Disposisi
                                @elseif($suratMasuk->status_disposisi === 'sudah_disposisi')
                                    Data Disposisi
                                @else
                                    Status Disposisi
                                @endif
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($suratMasuk->status_disposisi === 'belum_ditentukan')
                                <form action="{{ route('surat-masuk.disposisi', $suratMasuk) }}" method="POST">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <label>Cari Pegawai</label>
                                        <div class="input-group">
                                            <input type="text" id="searchPegawai" class="form-control"
                                                placeholder="Ketik nama atau jabatan...">
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fas fa-search"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header bg-light font-weight-bold">
                                                    Daftar Pegawai
                                                </div>
                                                <div class="card-body">
                                                    <div class="scroll-area transparant"
                                                        style="height: 300px; overflow-y: auto; overflow: overlay;">
                                                        <div id="daftar-pegawai">
                                                            @foreach ($pegawais as $pegawai)
                                                                <div class="pegawai-item mb-2 p-2 border rounded"
                                                                    data-id="{{ $pegawai->id }}" style="min-height: 60px;">
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-center">
                                                                        <div>
                                                                            <strong>{{ $pegawai->nama }}</strong><br>
                                                                            <small
                                                                                class="text-muted">{{ $pegawai->jabatan->nama ?? '-' }}</small>

                                                                        </div>
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-outline-primary btn-pilih">
                                                                            Pilih
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header bg-light font-weight-bold">
                                                    Pegawai Terpilih
                                                </div>
                                                <div class="card-body scroll-area">
                                                    <div id="pegawai-terpilih"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="selected-pegawai-inputs"></div>

                                    <div class="form-group mb-3">
                                        <label>Catatan Disposisi</label>
                                        <textarea name="catatan" class="form-control" rows="3" style="height: 200px;"></textarea>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary mr-5">Simpan Disposisi</button>
                                        <button type="button" class="btn btn-outline-danger" id="btn-tidak-perlu">
                                            Tidak Perlu Disposisi
                                        </button>
                                    </div>
                                </form>
                            @elseif($suratMasuk->status_disposisi === 'sudah_disposisi')
                                <div class="mb-3">
                                    <strong>Disposisi Kepada:</strong>
                                    @if ($suratMasuk->pegawais->isNotEmpty())
                                        <ul class="mt-2">
                                            @foreach ($suratMasuk->pegawais as $pegawai)
                                                <li>{{ $pegawai->nama }} - <small
                                                        class="text-muted">{{ $pegawai->jabatan->nama ?? '-' }}</small>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="mt-2">Belum ada disposisi</p>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <strong>Catatan Disposisi:</strong>
                                    @if ($suratMasuk->pegawais->isNotEmpty())
                                        <p class="mt-2">{{ $suratMasuk->pegawais->first()->pivot->catatan ?: '-' }}</p>
                                    @else
                                        <p class="mt-2">-</p>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <strong>Tanggal Disposisi:</strong>
                                    @if ($suratMasuk->pegawais->isNotEmpty())
                                        <p class="mt-2">
                                            {{ \Carbon\Carbon::parse($suratMasuk->pegawais->first()->pivot->created_at)->format('d/m/Y H:i') }}
                                        </p>
                                    @else
                                        <p class="mt-2">-</p>
                                    @endif
                                </div>
                            @else
                                <div class="alert alert-info">
                                    Surat ini tidak memerlukan disposisi.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Tidak Perlu Disposisi -->
    <div class="modal fade" id="modalKonfirmasiTidakPerlu" tabindex="-1" role="dialog"
        aria-labelledby="modalKonfirmasiLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalKonfirmasiLabel">Konfirmasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin surat ini tidak perlu disposisi?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="konfirmasi-tidak-perlu">Ya, Tidak Perlu
                        Disposisi</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Pencarian pegawai
            $("#searchPegawai").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#daftar-pegawai .pegawai-item").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            // Pilih pegawai
            $(".btn-pilih").click(function() {
                var item = $(this).closest('.pegawai-item');
                var pegawaiId = item.data('id');
                var pegawaiHtml = item.clone();

                // Ganti tombol Pilih menjadi Hapus
                pegawaiHtml.find('.btn-pilih')
                    .removeClass('btn-outline-primary btn-pilih')
                    .addClass('btn-outline-danger btn-hapus')
                    .text('Hapus');

                // Tambah hidden input
                $('#selected-pegawai-inputs').append(
                    `<input type="hidden" name="pegawai_ids[]" value="${pegawaiId}">`
                );

                // Pindah ke daftar terpilih
                $('#pegawai-terpilih').append(pegawaiHtml);
                item.hide();
            });

            // Hapus pegawai yang dipilih
            $(document).on('click', '.btn-hapus', function() {
                var item = $(this).closest('.pegawai-item');
                var pegawaiId = item.data('id');

                // Hapus dari daftar terpilih
                item.remove();

                // Hapus hidden input
                $(`input[name="pegawai_ids[]"][value="${pegawaiId}"]`).remove();

                // Tampilkan kembali di daftar pegawai
                $(`#daftar-pegawai .pegawai-item[data-id="${pegawaiId}"]`).show();
            });

            // Tampilkan modal konfirmasi saat tombol "Tidak Perlu Disposisi" diklik
            $("#btn-tidak-perlu").click(function() {
                $('#modalKonfirmasiTidakPerlu').modal('show');
            });

            // Aksi saat tombol konfirmasi pada modal diklik
            $("#konfirmasi-tidak-perlu").click(function() {
                $.ajax({
                    url: '{{ route('surat-masuk.update-status', $suratMasuk) }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'PUT',
                        status_disposisi: 'tidak_perlu_disposisi'
                    },
                    success: function(response) {
                        window.location.reload();
                    }
                });

                // Tutup modal setelah ajax request dikirim
                $('#modalKonfirmasiTidakPerlu').modal('hide');
            });
        });
    </script>
@endpush
