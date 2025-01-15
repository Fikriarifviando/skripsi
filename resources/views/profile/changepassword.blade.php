@extends('layouts.app')

@section('title', 'Ganti Password')
@section('page-title', 'Ganti Password')

@section('content')
    <section class="section">

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="col-12 py-5">

                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('Ganti Password') }}</h4>
                    </div>


                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.password') }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group row">

                                <label for="current_password"
                                    class="col-md-4 col-lg-3 col-form-label text-md-left">{{ __('Password Sekarang') }}</label>

                                <div class="col-md-6">
                                    <input id="current_password" type="text"
                                        class="form-control @error('current_password') is-invalid @enderror"
                                        name="current_password" required autocomplete="current_password">

                                    @error('current_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="new_password"
                                    class="col-md-4 col-lg-3 col-form-label text-md-left">{{ __('Password Baru') }}</label>

                                <div class="col-md-6">
                                    <input id="new_password" type="text"
                                        class="form-control @error('new_password') is-invalid @enderror" name="new_password"
                                        required autocomplete="new_password">

                                    @error('new_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="new_password_confirmation"
                                    class="col-md-4 col-lg-3 col-form-label text-md-left">{{ __('Konfirmasi Password Baru') }}</label>

                                <div class="col-md-6">
                                    <input id="new_password_confirmation" type="text"
                                        class="form-control @error('new_password_confirmation') is-invalid @enderror"
                                        name="new_password_confirmation" required autocomplete="new_password_confirmation">

                                    @error('new_password_confirmation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Add more fields as necessary -->

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Ganti Password') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<!-- Tambahkan di bagian scripts -->
<script>
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
        @if(session('success'))
            iziToast.success({
                title: 'Sukses',
                message: '{{ session('success') }}'
            });
        @endif

        @if(session('error'))
            iziToast.warning({
                title: 'Gagal',
                message: '{{ session('error') }}'
            });
        @endif
    });
</script>
@endpush
