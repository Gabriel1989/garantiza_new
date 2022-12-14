<!DOCTYPE html>
<html lang="es" xml:lang="es">

    <head>
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <title>@yield('titulo', 'Garantiza')</title>
        <meta name="description" content="Garantiza - Soluciones TI - Registro Automotriz">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Font CSS (Via CDN) -->
        <link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700'>
        <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">

        <!-- Required Plugin CSS -->
        <link rel="stylesheet" type="text/css" href="{{asset("assets/$themes/js/utility/highlight/styles/googlecode.css")}}"> 

        <!-- Theme CSS -->
        <link rel="stylesheet" type="text/css" href="{{asset("assets/$themes/skin/default_skin/css/theme.css")}}">


        <!-- Favicon -->
        <link rel="shortcut icon" href="assets/img/favicon.ico">

        @yield('styles')

    </head>
    <body class="dashboard-page sb-l-o sb-r-c">
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

        <!-- Bootstrap -->
        <script type="text/javascript" src="{{asset("assets/$themes/js/bootstrap/bootstrap.min.js")}}"></script>

        <!-- Theme Javascript -->
        <script type="text/javascript" src="{{asset("assets/$themes/js/utility/utility.js")}}"></script>
        <script type="text/javascript" src="{{asset("assets/$themes/js/main.js")}}"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

        <!-- Garantiza Javascript -->
        <script type="text/javascript" src="/js/index.js"></script>
        <script type="text/javascript" src="/js/funciones.js"></script>

        <script type="text/javascript">
            jQuery(document).ready(function() {
    
                "use strict";
    
                // Init Theme Core    
                Core.init();
            });
        </script>

        @yield('scripts')
    </body>
</html>