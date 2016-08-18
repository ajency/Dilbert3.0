<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dilbert</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,600,700"> --}}
    <link rel="stylesheet" href="css/font.css">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    {{-- <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> --}}
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <style>
        /*body {
            font-family: 'Lato';
            padding-top: 80px;
        }*/

        .fa-btn {
            margin-right: 6px;
        }

        /*.navbar-static-top {
            width: 100%;
            position: fixed;
            top: 0px;
        }*/
    </style>

    @yield('header')
</head>
<body id="app-layout">
    <div class="full-wrapper">
        <nav class="navbar navbar-default navbar-static-top header">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a href="{{ url('/') }}">
                        <h3 class="logo navbar-brand">
                            @if (Auth::guest() || empty($logo))
                                Dilbert
                            @else
                               <img src="https://www.google.com/a/cpanel/{{ $logo }}/images/logo.gif?alpha=1&service=google_default" alt="" style="height:40px;margin-top:-7px;">
                            @endif
                        </h3>
                    </a>
                </div>

                <div class="collapse navbar-collapse posrel" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    @if (!(Auth::guest()))
                        <ul class="nav navbar-nav">
                            <li><a href="{{ url('/home') }}">Home</a></li>
                        </ul>
                    @endif

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li class="highlight"><a href="{{ url('/register') }}">Register</a></li>
                            <li><a href="{{ url('/login') }}">Login</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <img src="{{ Auth::user()->avatar }}" height="20px" class="img-circle"><input type="hidden" id="uremail" value="{{ Auth::user()->email }}"><span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    @if(Auth::user()->role == "admin")
                                        <li><a href="#" id="editProfile"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> View Employee details</a></li>
                                        <li><a href="/orgs" id="editProfile"><span class="glyphicon glyphicon-bookmark" aria-hidden="true"></span> Organizations </a></li>
                                    @elseif(Auth::user()->role == "moderator")
                                        <li><a href="#" id="editProfile"><span class="glyphicon glyphicon-equalizer" aria-hidden="true"></span> View Team members</a></li><!-- <span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span> -->
                                    @endif
                                    <li><a href="/user" id="editProfile"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Edit Profile</a></li>
                                    <li><a href="#" id="tuckshopLink" data-showdiv="tuckshop_alert"><span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span> Tuckshop</a></li>
                                    <li><a href="{{ url('/logout/google') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                                </ul>
                            </li>
                        @endif
                        <li><a href="#">Contact us</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="content-wrapper">
            @yield('content')
        </div>

        <footer class="footer">
            <div class="text-center">by <a href="http://ajency.in" target="_blank">Ajency.in</a></div>
        </footer>
    </div>
    <!-- JavaScripts -->
    <!--<script src="js/jQuery v1.12.2.js"></script>
    <script src="js/bootstrap.min.js"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="js/style.js"></script>
    <script src="js/frontend.js"></script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
    @yield('footer')
</body>
</html>
