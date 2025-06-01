<!doctype html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>LOMIS | ERP</title>

    <meta name="description" content="Krismo ERP">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">

    <!-- Icons -->
    <link rel="shortcut icon" href="{{ asset('media/favicons/favicon.png') }}">
    <link rel="icon" sizes="192x192" type="image/png" href="{{ asset('media/favicons/favicon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('media/favicons/apple-touch-icon-180x180.png') }}">
    <link rel="stylesheet" href="{{ asset('media/css/custom.css') }}">

    <!-- Phosphor Icons CDN -->
    <link
      rel="stylesheet"
      type="text/css"
      href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css"
    />
    <link
      rel="stylesheet"
      type="text/css"
      href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css"
    />
    <!-- Modules -->
    @yield('css')
    @vite(['resources/sass/main.scss', 'resources/js/oneui/app.js'])

    <!-- Alternatively, you can also include a specific color theme after the main stylesheet to alter the default color theme of the template -->
    {{-- @vite(['resources/sass/main.scss', 'resources/sass/oneui/themes/amethyst.scss', 'resources/js/oneui/app.js']) --}}
    @yield('js')
</head>

<body>
    <!-- Page Container -->
    <div id="page-container"
        class="sidebar-o enable-page-overlay side-scroll page-header-fixed main-content-narrow @if (Auth::user()->mode == 'dark') page-header-dark dark-mode sidebar-dark @else sidebar-light @endif">

        <!-- Custom Loader -->
        <div id="custom-loader" class="custom-loader">
            <div class="spinner"></div>
        </div>

        <!-- Side Overlay-->
        @include('layouts.leftsidebar')

        <!-- END Side Overlay -->

        <!-- Sidebar -->
        @include('layouts.leftsidebar-nav')

        <!-- END Sidebar -->

        <!-- Header -->
        @include('layouts.navbar')
        <!-- END Header -->

        <!-- Main Container -->
        <main id="main-container">
            @yield('content')
        </main>
        <!-- END Main Container -->

        <!-- Noty CDN -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/noty@3.2.0-beta-deprecated/lib/noty.css" />
        <script src="https://cdn.jsdelivr.net/npm/noty@3.2.0-beta-deprecated/lib/noty.min.js"></script>

        <!-- Consolidated Styles -->
        <style>
            /* Noty Styles */
            .noty_bar {
                border-radius: 8px !important;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12) !important;
                padding: 16px 16px 16px 48px !important;
                position: relative;
                display: flex;
                align-items: center;
                min-height: 48px;
            }

            .noty_icon {
                position: absolute;
                left: 16px;
                top: 50%;
                transform: translateY(-50%);
                font-size: 22px;
                opacity: 0.85;
                line-height: 1;
            }

            .noty_text {
                display: inline-block;
                vertical-align: middle;
                line-height: 1.5;
                margin-left: 32px;
            }

            /* Custom Loader Styles */
            .custom-loader {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.9); /* Light background for light mode */
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 9999;
                transition: opacity 0.3s ease;
            }

            .dark-mode .custom-loader {
                background: rgba(0, 0, 0, 0.9); /* Dark background for dark mode */
            }

            .custom-loader.hidden {
                opacity: 0;
                visibility: hidden;
            }

            .spinner {
                width: 50px;
                height: 50px;
                border: 5px solid #3498db;
                border-top-color: transparent;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }

            .dark-mode .spinner {
                border-color: #6ab0f3;
                border-top-color: transparent;
            }

            @keyframes spin {
                to {
                    transform: rotate(360deg);
                }
            }
        </style>

        <!-- Loader JavaScript -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const loader = document.getElementById('custom-loader');
                // Hide loader when page is fully loaded
                window.addEventListener('load', function () {
                    loader.classList.add('hidden');
                    // Optional: Remove loader from DOM after transition
                    setTimeout(() => {
                        loader.style.display = 'none';
                    }, 300);
                });
            });
        </script>

        @if (session('success'))
            <script>
                new Noty({
                    type: 'success',
                    layout: 'bottomRight',
                    text: `<span class="noty_icon"><i class="fa fa-check-circle"></i></span><span class="noty_text">@json(session('success'))</span>`,
                    timeout: 3000,
                    progressBar: true,
                    theme: 'mint',
                    callbacks: {
                        onTemplate: function () {
                            this.barDom.style.background = '#28a745';
                            this.barDom.style.color = '#fff';
                        }
                    }
                }).show();
            </script>
        @endif

        @if (session('error'))
            <script>
                new Noty({
                    type: 'error',
                    layout: 'bottomRight',
                    text: `<span class="noty_icon"><i class="fa fa-times-circle"></i></span><span class="noty_text">@json(session('error'))</span>`,
                    timeout: 3000,
                    progressBar: true,
                    theme: 'mint',
                    callbacks: {
                        onTemplate: function () {
                            this.barDom.style.background = '#dc3545';
                            this.barDom.style.color = '#fff';
                        }
                    }
                }).show();
            </script>
        @endif

        <!-- Footer -->
        <footer id="page-footer" class="bg-body-light" hidden>
            <div class="content py-3">
                <div class="row fs-sm">
                    <div class="col-sm-6 order-sm-2 py-1 text-center text-sm-end">
                        Crafted with <i class="fa fa-heart text-danger"></i> by <a class="fw-semibold"
                            href="https://1.envato.market/ydb" target="_blank">krismo</a>
                    </div>
                    <div class="col-sm-6 order-sm-1 py-1 text-center text-sm-start">
                        <a class="fw-semibold" href="https://1.envato.market/AVD6j" target="_blank">krismoERP</a>
                        © <span data-toggle="year-copy"></span>
                    </div>
                </div>
            </div>
        </footer>
        <!-- END Footer -->
    </div>
    <!-- END Page Container -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.swal-confirm-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const form = this.closest('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, proceed!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>

</body>

</html>
