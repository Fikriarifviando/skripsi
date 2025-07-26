@extends('layouts.app')

@section('title', 'Detail Surat')
@section('page-title', 'Detail Surat')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <h4>Detail Surat</h4>
                    <hr>
                </div>
                <div class="col-md-12">
                    <table class="table table-bordered">
                        @php
                            $metadata = json_decode($suratKeluar->metadata, true);
                        @endphp

                        @if (is_array($metadata))
                            @foreach ($metadata as $key => $value)
                                <tr>
                                    <th width="30%">{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                                    <td>
                                        @if ($key === 'guru_ditugaskan' && is_array($value))
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Nama</th>
                                                            <th>NIP</th>
                                                            <th>Email</th>
                                                            <th>Jabatan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($value as $index => $guru)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td>{{ $guru['nama'] ?? '-' }}</td>
                                                                <td>{{ $guru['nip'] ?? '-' }}</td>
                                                                <td>{{ $guru['email'] ?? '-' }}</td>
                                                                <td>{{ $guru['jabatan']['nama'] ?? 'Guru' }}</td>

                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @elseif (is_array($value))
                                            <table class="table table-sm mb-0">
                                                @foreach ($value as $subKey => $subValue)
                                                    <tr>
                                                        <th width="40%">{{ ucfirst(str_replace('_', ' ', $subKey)) }}
                                                        </th>
                                                        <td>
                                                            @if (is_array($subValue))
                                                                <pre>{{ json_encode($subValue, JSON_PRETTY_PRINT) }}</pre>
                                                            @else
                                                                {{ $subValue }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        @else
                                            {{ $value }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2">Metadata tidak valid atau kosong</td>
                            </tr>
                        @endif
                    </table>
                </div>

                <div class="col-md-12 mt-4">
                    <h5>Informasi Surat</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Kode Pencarian</th>
                            <td>{{ $suratKeluar->kode_pencarian ?? 'Tidak ada' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Dibuat</th>
                            <td>{{ $suratKeluar->created_at ? date('d-m-Y H:i:s', strtotime($suratKeluar->created_at)) : 'Tidak ada' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Kode Surat</th>
                            <td>{{ $suratKeluar->kodeSurat ? $suratKeluar->kodeSurat->kode_klasifikasi . ' - ' . $suratKeluar->kodeSurat->nama_kode : 'Belum ditentukan' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Nomor Surat</th>
                            <td>{{ $suratKeluar->no_surat_keluar ?? 'Belum dibuat' }}</td>
                        </tr>
                    </table>
                </div>
                @if (!empty($suratKeluar->nama_file))
                    <div class="col-md-12 mt-4">
                        <h5>File PDF Surat</h5>
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>Nama File:</strong> {{ $suratKeluar->nama_file }}
                                </div>

                                @php
                                    $tanggal = $suratKeluar->created_at ?? now();
                                    $bulan = $tanggal->format('m');
                                    $tahun = $tanggal->format('Y');

                                    // Membuat path lengkap
                                    $fullPath = "surat_keluar/{$tahun}/{$bulan}/{$suratKeluar->nama_file}";
                                @endphp

                                <a href="{{ Storage::url($fullPath) }}" target="_blank" class="btn btn-info">
                                    <i class="fas fa-eye"></i> Lihat PDF
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                @if (empty($suratKeluar->no_surat_keluar))
                    <div class="col-md-12 mt-4">
                        <h5>Atur Kode Surat</h5>
                        <form method="POST" action="{{ route('keluar.update-kode', $suratKeluar) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="kode_klasifikasi">Kode Klasifikasi</label>
                                        <select class="form-control @error('kode_klasifikasi') is-invalid @enderror"
                                            id="kode_klasifikasi" name="kode_klasifikasi">
                                            <option value="">-- Pilih Kode Klasifikasi --</option>
                                            @foreach ($kodeSurat as $kode)
                                                <option value="{{ $kode->id }}"
                                                    {{ old('kode_klasifikasi', $suratKeluar->kode_surat_id) == $kode->id ? 'selected' : '' }}>
                                                    {{ $kode->kode_klasifikasi }} - {{ $kode->nama_kode }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('kode_klasifikasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-primary">Generate Nomor Surat</button>
                            </div>
                        </form>
                    </div>
                @endif

                @if (!empty($suratKeluar->no_surat_keluar))
                    <div class="col-md-12 mt-4">
                        <h5>Persetujuan Surat</h5>
                        @if ($suratKeluar->gambar_ttd)
                            <div class="card">
                                <div class="card-body">
                                    <img src="{{ asset('storage/' . $suratKeluar->gambar_ttd) }}" alt="Tanda Tangan"
                                        class="img-fluid" style="max-height: 150px;">

                                    <div class="mt-2 d-flex justify-content-between">
                                        @if (Auth::user()->role == 'admin')
                                            @if ($suratKeluar->gambar_ttd && empty($suratKeluar->nama_file))
                                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                                    data-target="#hapusTtdModal">
                                                    Batalkan Persetujuan
                                                </button>
                                            @endif
                                        @endif

                                        @if ($suratKeluar->gambar_ttd && empty($suratKeluar->nama_file))
                                            @if (in_array($suratKeluar->jenis_surat, ['perintah', 'rekomendasi', 'keterangan']))
                                                <form action="{{ route('surat.generate', $suratKeluar) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success">
                                                        Generate & Kirim Surat
                                                    </button>
                                                </form>
                                            @elseif (in_array($suratKeluar->jenis_surat, ['undangan', 'pengumuman']))
                                                <form action="{{ route('surat.generateOnly', $suratKeluar) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary">
                                                        Generate Surat Saja
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            @if (Auth::user()->role == 'admin')
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label class="mb-2">Pilih Jenis Persetujuan:</label>
                                            <div class="d-flex">
                                                <form action="{{ route('keluar.upload-ttd', $suratKeluar) }}" method="POST" class="mr-2">
                                                    @csrf
                                                    <input type="hidden" name="approval_type" value="with_stamp">
                                                    <button type="submit" class="btn btn-primary" id="btn_with_stamp">
                                                        <i class="fas fa-stamp mr-1"></i> Setujui dengan Stempel & TTD
                                                    </button>
                                                </form>
                                        
                                                <form action="{{ route('keluar.upload-ttd', $suratKeluar) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="approval_type" value="without_stamp">
                                                    <button type="submit" class="btn btn-outline-primary" id="btn_without_stamp">
                                                        <i class="fas fa-signature mr-1"></i> Setujui dengan TTD Saja
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        

                                        <div class="text-center mb-3 mt-3">
                                            <img id="previewImage" src="{{ asset('img/stempel_ttd.png') }}" alt="Preview"
                                                class="img-fluid" style="max-height: 150px;">
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const previewImage = document.getElementById('previewImage');
                                        const btnWithStamp = document.getElementById('btn_with_stamp');
                                        const btnWithoutStamp = document.getElementById('btn_without_stamp');

                                        // Set tampilan awal
                                        previewImage.src = "{{ asset('img/stempel_ttd.png') }}";
                                        previewImage.alt = "Preview Stempel dan TTD";

                                        // Preview untuk tombol stempel dan tanda tangan
                                        btnWithStamp.addEventListener('mouseenter', function() {
                                            previewImage.src = "{{ asset('img/stempel_ttd.png') }}";
                                            previewImage.alt = "Preview Stempel dan TTD";
                                        });

                                        // Preview untuk tombol tanda tangan saja
                                        btnWithoutStamp.addEventListener('mouseenter', function() {
                                            previewImage.src = "{{ asset('img/ttd_only.png') }}";
                                            previewImage.alt = "Preview TTD Saja";
                                        });
                                    });
                                </script>
                            @else
                                <div class="alert alert-info">
                                    Surat belum disetujui. Hubungi Kepala Sekolah untuk persetujuan surat.
                                </div>
                            @endif
                        @endif
                    </div>
                @endif
                @if (empty($suratKeluar->gambat_ttd))
                    <div class="col-md-12 mt-4">
                        <h5>Status Surat</h5>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="badge badge-{{ $suratKeluar->status == 'revisi' ? 'warning' : ($suratKeluar->status == 'selesai' ? 'success' : 'primary') }} mb-3">
                                            Status: {{ ucfirst($suratKeluar->status ?? 'pending') }}
                                        </div>

                                        @if ($suratKeluar->komentar)
                                            <div class="alert alert-info">
                                                <strong>Komentar Revisi:</strong>
                                                <p>{{ $suratKeluar->komentar }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                @if (Auth::user()->role == 'admin')
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-warning" data-toggle="modal"
                                                data-target="#revisiModal">
                                                <i class="fas fa-edit"></i> Minta Revisi
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if ($suratKeluar->status == 'revisi' && Auth::user()->role != 'admin')
                            @if (in_array($suratKeluar->jenis_surat, ['perintah', 'rekomendasi', 'keterangan', 'undangan', 'pengumuman']))
                                <a href="{{ route($suratKeluar->jenis_surat . '.edit', $suratKeluar) }}"
                                    class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit Surat
                                </a>
                            @endif
                        @endif
                    </div>
                @endif


            </div>
        </div>
    </div>

    <!-- Modal Hapus Tanda Tangan -->
    <div class="modal fade" id="hapusTtdModal" tabindex="-1" role="dialog" aria-labelledby="hapusTtdModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="hapusTtdModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Yakin ingin menghapus tanda tangan?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <form action="{{ route('keluar.hapus-ttd', $suratKeluar) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Revisi -->
    <div class="modal fade" id="revisiModal" tabindex="-1" role="dialog" aria-labelledby="revisiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="revisiModalLabel">Form Revisi Surat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('keluar.revisi', $suratKeluar) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="komentar">Komentar Revisi</label>
                            <textarea class="form-control" id="komentar" name="komentar" rows="4" required></textarea>
                            <small class="form-text text-muted">Masukkan detail yang perlu direvisi</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-warning">Kirim Revisi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
