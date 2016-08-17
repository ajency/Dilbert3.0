@extends('layouts.app')

@section('content')
<div class="container max-420">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-login">
                <!-- <div class="panel-heading">Login</div> -->
                <div class="panel-body text-center">

                    <div class="dilbert-logo">
                        <img src="img/dilbert.jpg" alt="">
                        <h3 class="logo">Dilbert</h3>
                        <div class="sub-title">Track time. Record Work</div>
                    </div>

                    <hr>

                    <div id="forlogin" class="login-helper">

                        <h4 class="normal">Have an existing account?</h4>
                        <div class="sub-title">Sign in and continue where you left off</div>

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
                                <a href="redirect/google" class="btn google-signin">
                                    <i class="fa fa-google-plus"></i>
                                    Sign in with Google
                                    <img src="img/btn_google_signin_dark_web.png" width="160" id="google_btn" alt="">
                                </a>
                                <!-- <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a> -->
                            </div>
                        </form>
                    </div>

                    <div id="forsignup" class="login-helper hidden">

                        <h4 class="normal">Have an existing account?</h4>
                        <div class="sub-title">Sign in and continue where you left off</div>

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
                                <a href="redirect/google" class="btn google-signin">
                                    <i class="fa fa-google-plus"></i>
                                    Sign in with Google
                                    <img src="img/btn_google_signin_dark_web.png" width="160" id="google_btn" alt="">
                                </a>
                                <!-- <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div id="state-login" class="out-of-pannel">
                <p>Haven't connected to Dilbert yet? <a href="{{ url('/register') }}" class="sign-up-trigger">Sign up!</a></p>
            </div>
            <div id="state-signup" class="out-of-pannel hidden">
                <p>Already have an account on Dilbert? <a href="#" class="sign-in-trigger">Sign in!</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
