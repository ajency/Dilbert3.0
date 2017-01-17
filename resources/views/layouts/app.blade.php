<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dilbert</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
    {{-- Commented the below FontAwesome call because it wasn't working --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous"> --}}
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,600,700"> --}}
    <link rel="stylesheet" href="{{ url('/css/font.css') }}">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    {{-- <link rel="stylesheet" type="text/css" href="{{ url('/css/bootstrap.min.css') }}"> --}}
    <link rel="stylesheet" type="text/css" href="{{ url('/css/styles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('/css/style.css') }}">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}


    <link rel="shortcut icon" href="{{ url('img/favicon.png') }}" >

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
                    <button type="button" class="navbar-toggle collapsed pull-left" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    @if (Auth::guest())
                    @else
                    <div class="dropdown userdata visible-xs pull-right">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <img src="{{ Auth::user()->avatar }}" height="20px" class="img-circle"><input type="hidden" id="uremail" value="{{ Auth::user()->email }}">
                            <!-- <span class="the-name">{{ Auth::user()->name }}</span> -->
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            @if(Auth::user()->can('edit-users'))
                            <li><a href="{{ url('/employees') }}" id="editProfile"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> @lang('lang.view_emp_det') </a></li>
                            <li><a href="{{ url('/orgs') }}" id="editProfile"><span class="glyphicon glyphicon-bookmark" aria-hidden="true"></span> Organizations </a></li>
                            <li><a href="{{ url('/roles') }}" id="editRoles"><span class="glyphicon glyphicon-barcode" aria-hidden="true"></span> @lang('lang.roles_n_permissions') </a></li>
                            @elseif(Auth::user()->role == "moderator")
                            <li><a href="#" id="editProfile"><span class="glyphicon glyphicon-equalizer" aria-hidden="true"></span> View Team members</a></li><!-- <span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span> -->
                            @endif
                            <li><a href="{{ url('/user') }}" id="editProfile"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> @lang('lang.edit_profile') </a></li>
                            <!-- Tuckshop -->
                            <!-- <li><a href="#" id="tuckshopLink" data-showdiv="tuckshop_alert"><span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span> Tuckshop</a></li> -->
                            <li><a href="{{ url('/logout/google') }}"><i class="fa fa-btn fa-sign-out"></i>@lang('lang.logout')</a></li>
                        </ul>
                    </div>
                    @endif

                    <!-- Branding Image -->
                    <h3 class="logo navbar-brand">
                        <a href="{{ url('/') }}">
                            @if (Auth::guest() || empty($logo))
                                Dilbert
                            @else
                               <img src="https://www.google.com/a/cpanel/{{ $logo }}/images/logo.gif?alpha=1&service=google_default" alt="" style="height:40px;margin-top:-7px;">
                            @endif
                        </a>
                    </h3>
                </div>

                <div class="collapse navbar-collapse posrel" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav text-left">
                        @if (!(Auth::guest()))
                            <li><a href="/home">@lang('lang.home')</a></li>
                        @endif
                        <li class="@if (!(Auth::guest())) haslogo @endif language-sel">
                            <span class="visible-xs-inline-block" style= "color: #828282;font-weight: 600;text-decoration: none;font-size: 13px;">@lang('lang.site_lang') : </span>
                            <button type="button" class="btn btn-default dropdown-toggle language-selector" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              {{ Config::get('app.locales')[App::getLocale()] }}
                              <i class="fa fa-angle-down"></i>
                            </button>
                            <ul class="dropdown-menu">
                                @foreach (Config::get('app.locales') as $lang => $language)
                                    @if ($lang != App::getLocale())
                                        <li><a href="{{ route('lang.switch', $lang) }}">{{$language}}</a></li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right text-left">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li class="highlight"><a href="{{ url('/register') }}">@lang('lang.register')</a></li>
                            <li><a href="{{ url('/login') }}">@lang('lang.login')</a></li>
                        @else
                            <li class="dropdown userdata hidden-xs">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <img src="{{ Auth::user()->avatar }}" height="20px" class="img-circle"><input type="hidden" id="uremail" value="{{ Auth::user()->email }}">
                                    <span class="the-name">{{ Auth::user()->name }}</span>
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    @if(Auth::user()->can('edit-users'))
                                        <li><a href="{{ url('/employees') }}" id="editProfile"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> @lang('lang.view_emp_det') </a></li>
                                        <li><a href="{{ url('/orgs') }}" id="editProfile"><span class="glyphicon glyphicon-bookmark" aria-hidden="true"></span> Organizations </a></li>
                                        <li><a href="{{ url('/roles') }}" id="editRoles"><span class="glyphicon glyphicon-barcode" aria-hidden="true"></span> @lang('lang.roles_n_permissions') </a></li>
                                    @elseif(Auth::user()->role == "moderator")
                                        <li><a href="#" id="editProfile"><span class="glyphicon glyphicon-equalizer" aria-hidden="true"></span> View Team members</a></li><!-- <span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span> -->
                                    @endif
                                    <li><a href="{{ url('/user') }}" id="editProfile"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> @lang('lang.edit_profile') </a></li>
                                    <!-- Tuckshop -->
                                    <!-- <li><a href="#" id="tuckshopLink" data-showdiv="tuckshop_alert"><span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span> Tuckshop</a></li> -->
                                    <li><a href="{{ url('/logout/google') }}"><i class="fa fa-btn fa-sign-out"></i>@lang('lang.logout')</a></li>
                                </ul>
                            </li>
                        @endif
                        <!-- Contact Us -->
                        <!-- <li><a href="#">@lang('lang.contact_us')</a></li> -->
                    </ul>
                </div>
            </div>
        </nav>

        <div class="content-wrapper" style="min-height: 850px;">
            @yield('content')
        </div>

        <footer class="footer">
            <div class="text-center">@lang('lang.by') <a href="http://ajency.in" target="_blank">Ajency.in</a></div>
        </footer>
    </div>
    <!-- JavaScripts -->
    <!--<script src="js/jQuery v1.12.2.js"></script>
    <script src="js/bootstrap.min.js"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="{{ url('/js/style.js') }}"></script>
    <script src="{{ url('/js/frontend.js') }}"></script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
    @yield('footer')
</body>
</html>
