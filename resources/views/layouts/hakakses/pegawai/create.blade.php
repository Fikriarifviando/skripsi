@extends('layouts.app')

@section('title', 'Tambah Pegawai')
@section('page-title', 'Tambah Pegawai')

@push('style')
    <!-- CSS Libraries -->
@endpush


@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('data-pegawai.store') }}">
                            @csrf
                            <div class="row mb-3">
                                <label for="nama" class="col-md-4 col-form-label text-md-end">{{ __('Nama') }}</label>
                                <div class="col-md-6">
                                    <input id="nama" type="text"
                                        class="form-control @error('nama') is-invalid @enderror" name="nama"
                                        value="{{ old('nama') }}" required autocomplete="nama" autofocus>

                                    @error('nama')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="nip"
                                    class="col-md-4 col-form-label text-md-end">{{ __('NIP') }}</label>
                                <div class="col-md-6">
                                    <input id="nip" type="text"
                                        class="form-control @error('nip') is-invalid @enderror"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="nip"
                                        value="{{ old('nip') }}" required autocomplete="nip" autofocus>

                                    @error('nip')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="jabatan_id">Jabatan</label>
                                <select class="form-control @error('jabatan_id') is-invalid @enderror" id="jabatan_id"
                                    name="jabatan_id">
                                    <option value="">-- Pilih Jabatan --</option>
                                    @foreach ($jabatans as $jabatan)
                                        <option value="{{ $jabatan->id }}"
                                            {{ old('jabatan_id') == $jabatan->id ? 'selected' : '' }}>
                                            {{ $jabatan->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jabatan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
