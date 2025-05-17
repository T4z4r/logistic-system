<!doctype html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title> SudEnergy | ERP</title>

    <meta name="description" content="Krismo ERP">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">

    <!-- Icons -->
    <link rel="shortcut icon" href="{{ asset('media/favicons/favicon.png') }}">
    <link rel="icon" sizes="192x192" type="image/png" href="{{ asset('media/favicons/favicon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('media/favicons/apple-touch-icon-180x180.png') }}">
    <link rel="stylesheet" href="{{ asset('media/css/custom.css') }}">
    <!-- Modules -->
    @yield('css')
    @vite(['resources/sass/main.scss', 'resources/js/oneui/app.js'])

    <!-- Alternatively, you can also include a specific color theme after the main stylesheet to alter the default color theme of the template -->
    {{-- @vite(['resources/sass/main.scss', 'resources/sass/oneui/themes/amethyst.scss', 'resources/js/oneui/app.js']) --}}
    @yield('js')
</head>

<body>
    <!-- Page Container -->
    <!--
    Available classes for #page-container:

    GENERIC

      'remember-theme'                            Remembers active color theme and dark mode between pages using localStorage when set through
                                                  - Theme helper buttons [data-toggle="theme"],
                                                  - Layout helper buttons [data-toggle="layout" data-action="dark_mode_[on/off/toggle]"]
                                                  - ..and/or One.layout('dark_mode_[on/off/toggle]')

    SIDEBAR & SIDE OVERLAY

      'sidebar-r'                                 Right Sidebar and left Side Overlay (default is left Sidebar and right Side Overlay)
      'sidebar-mini'                              Mini hoverable Sidebar (screen width > 991px)
      'sidebar-o'                                 Visible Sidebar by default (screen width > 991px)
      'sidebar-o-xs'                              Visible Sidebar by default (screen width < 992px)
      'sidebar-dark'                              Dark themed sidebar

      'side-overlay-hover'                        Hoverable Side Overlay (screen width > 991px)
      'side-overlay-o'                            Visible Side Overlay by default

      'enable-page-overlay'                       Enables a visible clickable Page Overlay (closes Side Overlay on click) when Side Overlay opens

      'side-scroll'                               Enables custom scrolling on Sidebar and Side Overlay instead of native scrolling (screen width > 991px)

    HEADER

      ''                                          Static Header if no class is added
      'page-header-fixed'                         Fixed Header

    HEADER STYLE

      ''                                          Light themed Header
      'page-header-dark'                          Dark themed Header

    MAIN CONTENT LAYOUT

      ''                                          Full width Main Content if no class is added
      'main-content-boxed'                        Full width Main Content with a specific maximum width (screen width > 1200px)
      'main-content-narrow'                       Full width Main Content with a percentage width (screen width > 1200px)

    DARK MODE

      'sidebar-dark page-header-dark dark-mode'   Enable dark mode (light sidebar/header is not supported with dark mode)
    -->

    <div id="page-container"
        class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed main-content-narrow @if (Auth::user()->mode == 'dark') page-header-dark dark-mode @endif">

        <!-- Side Overlay-->
        @include('layouts.leftsidebar')

        <!-- END Side Overlay -->

        <!-- Sidebar -->
        <!--
        Sidebar Mini Mode - Display Helper classes

        Adding 'smini-hide' class to an element will make it invisible (opacity: 0) when the sidebar is in mini mode
        Adding 'smini-show' class to an element will make it visible (opacity: 1) when the sidebar is in mini mode
            If you would like to disable the transition animation, make sure to also add the 'no-transition' class to your element

        Adding 'smini-hidden' to an element will hide it when the sidebar is in mini mode
        Adding 'smini-visible' to an element will show it (display: inline-block) only when the sidebar is in mini mode
        Adding 'smini-visible-block' to an element will show it (display: block) only when the sidebar is in mini mode
    -->
        @include('layouts.leftsidebar-nav')

        <!-- END Sidebar -->

        <!-- Header -->
        @include('layouts.navbar')
        <!-- END Header -->

        <!-- Main Container -->
        <main id="main-container ">
            @yield('content')
        </main>
        <!-- END Main Container -->

        <!-- Noty CDN (keeping your original version) -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/noty@3.2.0-beta-deprecated/lib/noty.css" />
        <script src="https://cdn.jsdelivr.net/npm/noty@3.2.0-beta-deprecated/lib/noty.min.js"></script>

        <!-- Consolidated Styles -->
        <style>
            .noty_bar {
                border-radius: 8px !important;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12) !important;
                padding: 16px 16px 16px 48px !important;
                position: relative;
                display: flex;
                align-items: center;
                min-height: 48px;
                /* Ensure enough height for alignment */
            }

            .noty_icon {
                position: absolute;
                left: 16px;
                top: 50%;
                transform: translateY(-50%);
                font-size: 22px;
                opacity: 0.85;
                line-height: 1;
                /* Prevent icon from affecting line height */
            }

            .noty_text {
                display: inline-block;
                vertical-align: middle;
                line-height: 1.5;
                /* Consistent text line height */
                margin-left: 32px;
                /* Space between icon and text */
            }
        </style>

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
                        onTemplate: function() {
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
                        onTemplate: function() {
                            this.barDom.style.background = '#dc3545';
                            this.barDom.style.color = '#fff';
                        }
                    }
                }).show();
            </script>
        @endif

        <!-- Footer -->
        <footer id="page-footer" class="bg-body-light " hidden>
            <div class="content py-3">
                <div class="row fs-sm">
                    <div class="col-sm-6 order-sm-2 py-1 text-center text-sm-end">
                        Crafted with <i class="fa fa-heart text-danger"></i> by <a class="fw-semibold"
                            href="https://1.envato.market/ydb" target="_blank">krismo</a>
                    </div>
                    <div class="col-sm-6 order-sm-1 py-1 text-center text-sm-start">
                        <a class="fw-semibold" href="https://1.envato.market/AVD6j" target="_blank">krismoERP</a>
                        &copy; <span data-toggle="year-copy"></span>
                    </div>
                </div>
            </div>
        </footer>
        <!-- END Footer -->
    </div>
    <!-- END Page Container -->
</body>

</html>
