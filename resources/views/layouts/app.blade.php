<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title -->
    <title>@yield('title') &mdash; SIKAPS</title>
    <link rel="shortcut icon" href="{{ url('img/avatar/logo.png') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap CSS (Bootstrap 4) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('library/ionicons201/css/ionicons.min.css') }}">

    <link rel="stylesheet" href="{{ asset('library/izitoast/dist/css/iziToast.min.css') }}">

    <!-- Custom CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/skins/reverse.css') }}">

    <!-- Additional CSS (if any) -->
    @stack('css')
</head>

<body>
    <div id="app">
        @if (Route::currentRouteName() == 'login')
            <div class="login-container">
                @yield('content')
            </div>
            <!-- Footer -->
            @include('components.auth-footer')
        @else
            <div class="main-wrapper">
                <!-- Header -->
                @include('components.header')

                <!-- Sidebar -->
                @include('components.sidebar')

                <!-- Main Content -->
                <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h1>@yield('page-title')</h1>
                        </div>

                        {{-- Flash Messages (Dinonaktifkan karena akan diganti dengan iziToast) --}}
                        {{-- @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if (session('warning'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                {{ session('warning') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif --}}
                    </section>
                    @yield('content')
                </div>

                <!-- Footer -->
                @include('components.footer')
            </div>
        @endif
    </div>

    <!-- jQuery and Popper.js (required for Bootstrap 4) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

    <!-- Bootstrap JS (Bootstrap 4) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>

    <!-- Custom JS Libraries -->
    <script src="{{ asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('library/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('library/izitoast/dist/js/iziToast.min.js') }}"></script>

    <!-- Template JS Files -->
    <script src="{{ asset('js/stisla.js') }}"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>

    <!-- Flash message auto-close setelah 3 detik (Bisa dinonaktifkan karena sudah menggunakan iziToast) -->
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert-dismissible').alert('close');
            }, 30000); // 3 detik
        });
    </script>

    <!-- Script untuk iziToast Global -->
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
            @if (session('success'))
                iziToast.success({
                    title: 'Sukses',
                    message: '{{ session('success') }}'
                });
            @endif

            @if (session('warning'))
                iziToast.warning({
                    title: 'Peringatan',
                    message: '{{ session('warning') }}'
                });
            @endif

            @if (session('error'))
                iziToast.error({
                    title: 'Gagal',
                    message: '{{ session('error') }}'
                });
            @endif

            @if (session('status'))
                iziToast.info({
                    title: 'Informasi',
                    message: '{{ session('status') }}'
                });
            @endif
        });
    </script>

    <!-- Additional JS (if any) -->
    @stack('scripts')

</body>

</html>
