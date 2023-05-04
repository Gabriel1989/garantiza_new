<!DOCTYPE html>
<html lang="es" xml:lang="es">

<head>
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <title>@yield('titulo', 'Garantiza')</title>
    <meta name="description" content="Garantiza - Soluciones TI - Registro Automotriz">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Font CSS (Via CDN) -->
    <link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700'>
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">

    <!-- Required Plugin CSS -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset("assets/$themes/js/utility/highlight/styles/googlecode.css") }}">

    <!-- Theme CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset("assets/$themes/skin/default_skin/css/theme.css") }}">

    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">


    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/img/favicon.ico">

    <!-- CSS Adicionales-->

    <link rel="stylesheet" type="text/css" href="{{ asset("css/app_garantiza.css")}}">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    @yield('styles')

</head>

<body class="dashboard-page sb-l-o sb-r-c">
    <!-- Inicio Overlay -->
    @include("themes/$themes/overlay")
    <!-- Fin Overlay -->

    <!-- Inicio header -->
    @include("themes/$themes/header")
    <!-- Fin header -->

    <!-- Inicio aside -->
    @include("themes/$themes/aside")
    <!-- Fin aside -->

    <!-- Start: Content-Wrapper -->
    <section id="content_wrapper">

        <!-- Start: Topbar-Dropdown -->
        <div id="topbar-dropmenu">
            <div class="topbar-menu row">
                <div class="col-xs-4 col-sm-2">
                    <a href="#" class="metro-tile bg-success">
                        <span class="metro-icon glyphicons glyphicons-inbox"></span>
                        <p class="metro-title">Messages</p>
                    </a>
                </div>
                <div class="col-xs-4 col-sm-2">
                    <a href="#" class="metro-tile bg-info">
                        <span class="metro-icon glyphicons glyphicons-parents"></span>
                        <p class="metro-title">Users</p>
                    </a>
                </div>
                <div class="col-xs-4 col-sm-2">
                    <a href="#" class="metro-tile bg-alert">
                        <span class="metro-icon glyphicons glyphicons-headset"></span>
                        <p class="metro-title">Support</p>
                    </a>
                </div>
                <div class="col-xs-4 col-sm-2">
                    <a href="#" class="metro-tile bg-primary">
                        <span class="metro-icon glyphicons glyphicons-cogwheels"></span>
                        <p class="metro-title">Settings</p>
                    </a>
                </div>
                <div class="col-xs-4 col-sm-2">
                    <a href="#" class="metro-tile bg-warning">
                        <span class="metro-icon glyphicons glyphicons-facetime_video"></span>
                        <p class="metro-title">Videos</p>
                    </a>
                </div>
                <div class="col-xs-4 col-sm-2">
                    <a href="#" class="metro-tile bg-system">
                        <span class="metro-icon glyphicons glyphicons-picture"></span>
                        <p class="metro-title">Pictures</p>
                    </a>
                </div>
            </div>
        </div>
        <!-- End: Topbar-Dropdown -->

        <!-- Start: Topbar -->
        {{-- <header id="topbar">
                <div class="topbar-left">
                    <ol class="breadcrumb">
                        <li class="crumb-active">
                            <a href="dashboard.html">Dashboard</a>
                        </li>
                        <li class="crumb-icon">
                            <a href="dashboard.html">
                                <span class="glyphicon glyphicon-home"></span>
                            </a>
                        </li>
                        <li class="crumb-link">
                            <a href="index.html">Home</a>
                        </li>
                        <li class="crumb-trail">Dashboard</li>
                    </ol>
                </div>
            </header> --}}
        <!-- End: Topbar -->


        <section id="content" class="animated fadeIn">
            @yield('contenido')
        </section>

    </section>

    <!-- jQuery -->
    {{-- <script type="text/javascript" src="{{asset("assets/$themes/vendor/jquery/jquery-1.11.1.min.js")}}"></script>
        <script type="text/javascript" src="{{asset("assets/$themes/vendor/jquery/jquery_ui/jquery-ui.min.js")}}"></script> --}}

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <!-- Popper.js -->
    <script src="https://unpkg.com/@popperjs/core@2.9.3/dist/umd/popper.min.js" crossorigin="anonymous"></script>

    <!-- Bootstrap -->
    <script type="text/javascript" src="{{ asset("assets/$themes/js/bootstrap/bootstrap.min.js") }}"></script>

    <!-- Theme Javascript -->
    <script type="text/javascript" src="{{ asset("assets/$themes/js/utility/utility.js") }}"></script>
    <script type="text/javascript" src="{{ asset("assets/$themes/js/main.js") }}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Garantiza Javascript -->
    <script type="text/javascript" src="/js/index.js"></script>
    <script type="text/javascript" src="/js/funciones.js"></script>

    <script type="text/javascript">
        jQuery(document).ready(function() {

            try {
                // declaraciones para try
                $('#nroEjes').select2();
            } catch (e) {
                console.log(e);
            }


            "use strict";

            // Init Theme Core    
            Core.init();
        });

        
    </script>
    <script type="text/javascript" src="/js/jquery.rut.min.js"></script>

    @yield('scripts')
</body>

</html>
