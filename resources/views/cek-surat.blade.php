@extends('layouts.public') {{-- atau layouts kosong kalau kamu mau lebih minimal --}}

@section('title', 'Cek Surat')

@section('content')
<div class="container mt-5">
    <h4>Cek Status Surat</h4>
    <form action="{{ route('cek-surat') }}" method="GET">
        <div class="input-group mb-3">
            <input type="text" name="kode" class="form-control" placeholder="Masukkan Kode Pencarian" required>
            <button class="btn btn-primary" type="submit">Cek</button>
        </div>
    </form>

    @if (isset($suratKeluar))
        <div class="card mt-4">
            <div class="card-body">
                <h5>Hasil Pencarian</h5>
                <table class="table table-bordered">
                    <tr>
                        <th>Nama</th>
                        <td>{{ data_get($suratKeluar, 'metadata.informasi_pribadi.nama', '-') }}</td>

                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $suratKeluar->status ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Surat</th>
                        <td>{{ $suratKeluar->jenis_surat }}</td>
                    </tr>
                </table>
            </div>
        </div>
    @elseif (request()->has('kode'))
        <div class="alert alert-danger mt-3">Kode tidak ditemukan.</div>
    @endif
</div>
@endsection
