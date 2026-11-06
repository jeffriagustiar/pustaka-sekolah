<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pustaka Sekolah')</title>
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.gstatic.com/">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/fontawesome.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/icofont.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/themify.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/flag-icon.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/feather-icon.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link id="color" rel="stylesheet" href="{{ asset('assets/css/color-1.css') }}" media="screen">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}">
    @stack('styles')
</head>
<body>
    <div class="loader-wrapper">
        <div class="theme-loader">
            <div class="loader-p"></div>
        </div>
    </div>

    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        <div class="page-main-header">
            <div class="main-header-right row m-0">
                <div class="main-header-left">
                    <div class="logo-wrapper">
                        <a href="{{ route('books.index') }}">
                            <img class="img-fluid" src="{{ asset('assets/images/logo/logo.png') }}" alt="">
                        </a>
                    </div>
                    <div class="toggle-sidebar">
                        <i class="status_toggle middle" data-feather="align-center" id="sidebar-toggle"></i>
                    </div>
                </div>
                <div class="left-menu-header col">
                    <ul>
                        <li>
                            <form class="form-inline search-form">
                                <div class="search-bg"><i class="fa fa-search"></i>
                                    <input class="form-control-plaintext" placeholder="Cari...">
                                </div>
                            </form>
                        </li>
                    </ul>
                </div>
                <div class="nav-right col pull-right right-menu p-0">
                    <ul class="nav-menus">
                        <li>
                            <div class="mode"><i class="fa fa-moon-o"></i></div>
                        </li>
                        <li class="onhover-dropdown p-0">
                            <button class="btn btn-primary-light" type="button">
                                <a href="#"><i data-feather="log-out"></i> Keluar</a>
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="d-lg-none mobile-toggle pull-right w-auto">
                    <i data-feather="more-horizontal"></i>
                </div>
            </div>
        </div>

        <div class="page-body-wrapper sidebar-icon">
            <header class="main-nav">
                <div class="sidebar-user text-center">
                    <a class="setting-primary" href="javascript:void(0)"><i data-feather="settings"></i></a>
                    <img class="img-90 rounded-circle" src="{{ asset('assets/images/dashboard/1.png') }}" alt="">
                    <div class="badge-bottom"><span class="badge badge-primary">Admin</span></div>
                    <a href="#">
                        <h6 class="mt-3 f-14 f-w-600">Pustaka Sekolah</h6>
                    </a>
                    <p class="mb-0 font-roboto">Pengelola Pustaka</p>
                </div>
                <nav>
                    <div class="main-navbar">
                        <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
                        <div id="mainnav">
                            <ul class="nav-menu custom-scrollbar">
                                <li class="back-btn">
                                    <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                                </li>
                                <li class="sidebar-main-title">
                                    <div><h6>Menu</h6></div>
                                </li>
                                <li>
                                    <a class="nav-link menu-title link-nav" href="{{ route('books.index') }}">
                                        <i data-feather="book"></i><span>Buku</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link menu-title link-nav" href="{{ route('borrowings.index') }}">
                                        <i data-feather="repeat"></i><span>Peminjaman</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link menu-title link-nav" href="{{ route('borrowings.create') }}">
                                        <i data-feather="plus-circle"></i><span>Transaksi Baru</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link menu-title link-nav" href="{{ route('api-test.index') }}">
                                        <i data-feather="wifi"></i><span>Test Sinkron</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
                    </div>
                </nav>
            </header>

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
    <script src="{{ asset('assets/js/bootstrap/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/feather/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/scrollbar/simplebar.js') }}"></script>
    <script src="{{ asset('assets/js/sidebar-menu.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script>
        if (window.feather) { feather.replace(); }
        (function () {
            if (localStorage.getItem('body') === 'dark-only') {
                document.body.classList.add('dark-only');
                var icon = document.querySelector('.mode i');
                if (icon) { icon.classList.remove('fa-moon-o'); icon.classList.add('fa-lightbulb-o'); }
            }
        })();
        window.addEventListener('load', function () {
            var lw = document.querySelector('.loader-wrapper');
            if (lw) { lw.style.display = 'none'; }
        });
    </script>
    @stack('scripts')
</body>
</html>
