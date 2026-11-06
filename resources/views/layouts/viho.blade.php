<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pustaka Sekolah')</title>
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/icofont.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/themify.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/feather-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">
    @stack('styles')
</head>
<body>
    <div class="loader-wrapper">
        <div class="loader"></div>
    </div>

    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        <div class="page-header">
            <div class="header-wrapper row m-0">
                <div class="header-logo-wrapper">
                    <div class="logo-wrapper">
                        <a href="{{ route('books.index') }}">
                            <img class="img-fluid for-light" src="{{ asset('assets/images/logo/logo.png') }}" alt="">
                        </a>
                    </div>
                </div>
                <div class="nav-right col">
                    <ul class="nav-menus">
                        <li><a href="{{ route('books.index') }}">Buku</a></li>
                        <li><a href="{{ route('borrowings.index') }}">Peminjaman</a></li>
                        <li><a href="{{ route('borrowings.create') }}">Transaksi Baru</a></li>
                        <li><a href="{{ route('api-test.index') }}">Test Sinkron</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="page-body-wrapper">
            <div class="sidebar-wrapper">
                <div>
                    <div class="logo-wrapper">
                        <a href="{{ route('books.index') }}">
                            <img class="img-fluid for-light" src="{{ asset('assets/images/logo/logo.png') }}" alt="">
                        </a>
                    </div>
                    <nav class="sidebar-main">
                        <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
                        <div id="sidebar-menu">
                            <ul class="sidebar-links">
                                <li class="back-btn">
                                    <a href="{{ route('books.index') }}"><img class="img-fluid" src="{{ asset('assets/images/logo/logo-icon.png') }}" alt=""></a>
                                </li>
                                <li class="sidebar-list">
                                    <a class="sidebar-link sidebar-title" href="{{ route('books.index') }}">
                                        <i data-feather="book"></i><span> Buku</span>
                                    </a>
                                </li>
                                <li class="sidebar-list">
                                    <a class="sidebar-link sidebar-title" href="{{ route('borrowings.index') }}">
                                        <i data-feather="repeat"></i><span> Peminjaman</span>
                                    </a>
                                </li>
                                <li class="sidebar-list">
                                    <a class="sidebar-link sidebar-title" href="{{ route('borrowings.create') }}">
                                        <i data-feather="plus-circle"></i><span> Transaksi Baru</span>
                                    </a>
                                </li>
                                <li class="sidebar-list">
                                    <a class="sidebar-link sidebar-title" href="{{ route('api-test.index') }}">
                                        <i data-feather="wifi"></i><span> Test Sinkron</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
                    </nav>
                </div>
            </div>

            <div class="page-body">
                <div class="container-fluid">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/feather/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/scrollbar/simplebar.js') }}"></script>
    <script src="{{ asset('assets/js/sidebar-menu.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script>
        if (window.feather) { feather.replace(); }
        window.addEventListener('load', function () {
            var lw = document.querySelector('.loader-wrapper');
            if (lw) { lw.style.display = 'none'; }
        });
    </script>
    @stack('scripts')
</body>
</html>
