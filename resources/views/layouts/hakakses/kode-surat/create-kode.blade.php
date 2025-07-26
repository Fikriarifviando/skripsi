@extends('layouts.app')

@section('title', 'Buat Kode Baru')
@section('page-title', 'Buat Kode Baru')

@push('style')
    <!-- CSS Libraries -->
@endpush


@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('kode-surat.store') }}">
                            @csrf
                            <div class="row mb-3">
                                <label for="namaKode" class="col-md-4 col-form-label text-md-end">{{ __('Nama') }}</label>
                                <div class="col-md-6">
                                    <input id="namaKode" type="text"
                                        class="form-control @error('namaKode') is-invalid @enderror" name="nama_kode"
                                        value="{{ old('namaKode') }}" required autocomplete="namaKode" autofocus>

                                    @error('namaKode')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="kodeKlasifikasi" class="col-md-4 col-form-label text-md-end">{{ __('Kode Klasifikasi') }}</label>

                                <div class="col-md-6">
                                    <input id="kodeKlasifikasi" type="text"
                                        class="form-control @error('kodeKlasifikasi') is-invalid @enderror" name="kode_klasifikasi"
                                        value="{{ old('kodeKlasifikasi') }}" required autocomplete="kodeKlasifikasi" autofocus>

                                    @error('kodeKlasifikasi')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4 text-center">
                                    <button type="submit" class="btn btn-primary rounded-4">
                                        {{ __('Tambah') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
