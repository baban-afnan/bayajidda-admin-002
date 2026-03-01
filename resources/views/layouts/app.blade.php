<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="Bayajidda Global - Premium Administrative Dashboard.">
    <meta name="author" content="Dreams Technologies">
    <meta name="robots" content="index, follow">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/img/logo/logo.png') }}" type="image/x-icon" />
    <link rel="shortcut icon" href="{{ asset('assets/img/logo/logo.png') }}" type="image/x-icon" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <!-- Feather CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/icons/feather/feather.css') }}">
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/tabler-icons/tabler-icons.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">
    <!-- Custom App CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/premium.css') }}">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @stack('styles')
    
    <style>
        /* Global Page Loader */
        #global-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #ffffff;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #global-loader.hide {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.5s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.5s ease;
        }

        .page-loader {
            width: 60px;
            height: 60px;
            border: 4px solid #f1f5f9;
            border-top: 4px solid var(--primary-solid);
            border-radius: 50%;
            animation: spin 0.8s cubic-bezier(0.5, 0.1, 0.4, 0.9) infinite;
            box-shadow: 0 0 15px rgba(0, 47, 186, 0.1);
        }

        body {
            font-family: 'Outfit', 'Inter', sans-serif !important;
            background-color: var(--bg-main) !important;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

</head>

<body>
    <!-- Loader -->
    <div id="global-loader">
        <div class="page-loader"></div>
    </div>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        @include('layouts.partials.header')
        @include('layouts.partials.sidebar')

        <!-- Page Wrapper -->
        <div class="page-wrapper">
            <div class="content">
                @isset($header)
                    <div class="page-header">
                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="page-title">{{ $header }}</h3>
                            </div>
                        </div>
                    </div>
                @endisset

                <main>
                    {{ $slot }}
                </main>
            </div>

            <!-- ===== Footer Start ===== -->
            <footer class="footer bg-primary text-light py-3 mt-auto">
                <div class="container-fluid">
                    <div class="row align-items-center justify-content-between">
                        <!-- Left Side: Copyright -->
                        <div class="col-md-5 text-center text-md-start mb-2 mb-md-0">
                            <p class="mb-0 small">
                                © <script>document.write(new Date().getFullYear())</script> 
                                <strong class="text-white"> Bayajidda Global </strong>. 
                                All Rights Reserved.
                            </p>
                        </div>

                        <!-- Right Side: Social & Quick Links -->
                        <div class="col-md-6 text-center text-md-end">
                            <div class="d-inline-flex align-items-center gap-3">
                                <a href="#" target="_blank" class="text-light text-decoration-none footer-social">
                                    <i class="ti ti-brand-facebook fs-18"></i>
                                </a>
                                <a href="#" target="_blank" class="text-light text-decoration-none footer-social">
                                    <i class="ti ti-brand-twitter fs-18"></i>
                                </a>
                                <a href="#" target="_blank" class="text-light text-decoration-none footer-social">
                                    <i class="ti ti-brand-whatsapp fs-18"></i>
                                </a>
                                <a href="#" class="text-light text-decoration-none footer-social">
                                    <i class="ti ti-mail fs-18"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- ===== Footer End ===== -->
        </div>
        <!-- /Page Wrapper -->
    </div>
    <!-- /Main Wrapper -->

    <!-- KYC Modal -->
    @include('pages.dashboard.kyc')

    <!-- Scripts -->
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>
    
    <!-- Custom JS -->
    <script src="{{ asset('assets/js/script.js') }}"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Hide loader when page is fully loaded
        window.addEventListener('load', function() {
            const loader = document.getElementById('global-loader');
            if (loader) {
                loader.classList.add('hide');
                // Remove from DOM after transition
                setTimeout(function() {
                    loader.style.display = 'none';
                }, 300);
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
