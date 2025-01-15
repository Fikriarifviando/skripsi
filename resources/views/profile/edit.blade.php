@extends('layouts.app')
@section('title', 'Edit Profile')
@section('page-title', 'Edit Profile')
@push('style')
    <!-- CSS Libraries -->
@endpush

@section('content')
    <section class="section py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="col-12 py-5">

                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('Edit Profile') }}</h4>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group row">
                                <label for="email"
                                    class="col-md-4 col-lg-3 col-form-label text-md-left">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6 col-lg-8">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email', $user->email) }}" required autocomplete="email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name"
                                    class="col-md-4 col-lg-3 col-form-label text-md-left">{{ __('Name') }}</label>

                                <div class="col-md-6 col-lg-8">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>

                                    @error('name')
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
                                        {{ __('Update Profile') }}
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
            timeout: 5000,
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