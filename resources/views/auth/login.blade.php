@extends('layouts.app')

@section('header')
<script type="text/javascript">
    window.onload = function() {
        localStorage.removeItem("ng2-webstorage|user_data");
    }
</script>
@endsection
@section('content')
    @if (!session()->has('session') and session('session') == "session_timeout")
        <div class="row">
            <div class="col-lg-offset-3 col-lg-6 col-md-offset-3 col-md-6">
                <div class="alert alert-warning alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Sorry</strong>, but it seems your Session timed out. Please login to continue.
                </div>
            </div>
        </div>
    @endif
<div class="container max-420">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-login">
                <!-- <div class="panel-heading">Login</div> -->
                <div class="panel-body text-center">

                    <div class="dilbert-logo">
                        <img src="{{ url('img/dilbert.jpg') }}" alt="">
                        <h3 class="logo">Dilbert</h3>
                        <div class="sub-title">@lang('lang.hero_title')</div>
                    </div>

                    <hr>

                    <div id="forlogin" class="login-helper">

                        <h4 class="normal">@lang('lang.account_exist')</h4>
                        <div class="sub-title">@lang('lang.sign_in') @lang('lang.n_cont_whr_u_left_off')</div>
                        
                        <!-- Form Login - not used -->
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} hidden">
                                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} hidden">
                                <label for="password" class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password">

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group hidden">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>

                            <div class="form-g roup">
                                <!-- <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-sign-in"></i> Login
                                </button> -->
                                <a href="{{ url('redirect/google') }}" class="btn google-signin">
                                    <i class="fa fa-google-plus"></i>
                                    @lang('lang.sign_in') @lang('lang.with_google')
                                    <img src="img/btn_google_signin_dark_web.png" width="160" id="google_btn" alt="">
                                </a>
                                <!-- <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a> -->
                            </div>
                        </form>
                        <!-- End of From Login -->
                    </div>

                    <div id="forsignup" class="login-helper hidden">

                        <h4 class="normal">@lang('lang.account_exist')</h4>
                        <div class="sub-title">@lang('lang.sign_in') @lang('lang.n_cont_whr_u_left_off')</div>
                        
                        <!-- Sign up page - not used -->
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} hidden">
                                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} hidden">
                                <label for="password" class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password">

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group hidden">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>

                            <div class="form-g roup">
                                <!-- <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-sign-in"></i> Login
                                </button> -->
                                <a href="{{ url('redirect/google') }}" class="btn google-signin">
                                    <i class="fa fa-google-plus"></i>
                                    @lang('lang.sign_in') with Google
                                    <img src="img/btn_google_signin_dark_web.png" width="160" id="google_btn" alt="">
                                </a>
                                <!-- <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a> -->
                            </div>
                        </form>

                        <!-- End of Sign up -->
                    </div>
                </div>
            </div>
            <div id="state-login" class="out-of-pannel">
               <!-- <p> @lang('lang.not_conn_2_dilbert') <a href="{{ url(app()->getLocale().'/register')}}" class="sign-up-trigger">Sign up!</a></p>-->
                <p> @lang('lang.not_conn_2_dilbert') <a href="{{ url('/register')}}" class="sign-up-trigger">@lang('lang.sign_up')!</a></p>
            </div>
            <div id="state-signup" class="out-of-pannel hidden">
                <p> @lang('lang.already_hv_acc_in_dil') <a href="#" class="sign-in-trigger">@lang('lang.sign_in')</a></p>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // this set adds a class landing-page to body tag
    bodyclass = " signin-page";
    document.body.className += bodyclass;
</script>
@endsection
