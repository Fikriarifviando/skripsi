@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                <div class="col-12 py-5">

                </div>

                <div class="card shadow-lg card-primary">
                    <div class="card-header text-center">
                        <h4>{{ __('Login') }}</h4>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <label for="email">{{ __('Email Address') }}</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group position-relative">
                                <label for="password">{{ __('Password') }}</label>
                                <div class="input-group">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password">
                                    <div class="input-group-append">
                                        <span class="input-group-text toggle-password" style="cursor: pointer;">
                                            <i class="fas fa-eye-slash"></i>
                                        </span>
                                    </div>
                                </div>


                                @error('password')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="remember" class="custom-control-input" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div> --}}

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">
                                    {{ __('Login') }}
                                </button>
                            </div>

                            {{-- <div class="text-center">
                            @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                            <br>
                            <a class="btn btn-link" href="{{ route('register') }}">
                                {{ __('Belum Punya Akun? Register') }}
                            </a>
                        </div> --}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @push('scripts')
        <script>
            $(document).ready(function() {
                // Toggle Password
                $('.toggle-password').on('click', function() {
                    const passwordField = $('#password');
                    const icon = $(this).find('i');
                    if (passwordField.attr('type') === 'password') {
                        passwordField.attr('type', 'text');
                        icon.removeClass('fa-eye-slash').addClass('fa-eye');
                    } else {
                        passwordField.attr('type', 'password');
                        icon.removeClass('fa-eye').addClass('fa-eye-slash');
                    }
                });
            });
        </script>
    @endpush
@endpush