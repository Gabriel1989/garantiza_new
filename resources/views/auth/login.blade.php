<!DOCTYPE html>
<html>

<head>
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <title>@yield('titulo', 'Garantiza')</title>
    <meta name="description" content="Garantiza - Soluciones TI - Registro Automotriz">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font CSS (Via CDN) -->
    <link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800'>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300">

    <!-- Theme CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset("assets/$themes/skin/default_skin/css/theme.css")}}">

    <!-- Admin Forms CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset("assets/$themes/admin-tools/admin-forms/css/admin-forms.css")}}">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset("assets/$themes/img/favicon.ico")}}">

</head>

<body>
    <div class="admin-form tab-pane active" id="login2" role="tabpanel">
        <div class="col-lg-12 mb50"></div>
        <div class="col-lg-4"></div>
        <div class="panel panel-primary heading-border col-lg-4">
            <div class="panel-heading">
                {{-- <span class="panel-title"><i class="fa fa-sign-in"></i>Login</span> --}}
                <img src="/img/LogoGarantiza.jpeg" width="450px" alt="">
            </div>
            <!-- end .form-header section -->

            <form method="post" action="{{ route('login') }}" id="form-login2">
                @csrf
                <div class="panel-body p25 pt10">
                    <div class="section row">
                        <div class="col-md-1 pl30"></div>
                        <div class="col-md-10 pl30">
                            <div class="section"></div>
                            <div class="section">
                                <label for="email" class="field prepend-icon">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    <label for="email" class="field-icon"><i class="fa fa-user"></i>
                                    </label>
                                </label>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <!-- end section -->

                            <div class="section">
                                <label for="password" class="field prepend-icon">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                    <label for="password" class="field-icon"><i class="fa fa-lock"></i>
                                    </label>
                                </label>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            </div>

                        </div>
                        <!-- end .colm section -->

                    </div>
                    <!-- end .section row section -->

                </div>
                <!-- end .form-body section -->
                <div class="panel-footer">
                    <button type="submit" class="button btn-primary">{{ __('Login') }}</button>
                </div>
                <!-- end .form-footer section -->
            </form>
        </div>
        <!-- end .admin-form section -->
    </div>
<body>

<!-- jQuery -->
<script type="text/javascript" src="{{asset("assets/$themes/vendor/jquery/jquery-1.11.1.min.js")}}"></script>
<script type="text/javascript" src="{{asset("assets/$themes/vendor/jquery/jquery_ui/jquery-ui.min.js")}}"></script>

<!-- Bootstrap -->
<script type="text/javascript" src="{{asset("assets/$themes/assets/js/bootstrap/bootstrap.min.js")}}"></script>

<!-- Theme Javascript -->
<script type="text/javascript" src="{{asset("assets/$themes/assets/js/utility/utility.js")}}"></script>
<script type="text/javascript" src="{{asset("assets/$themes/assets/js/main.js")}}"></script>