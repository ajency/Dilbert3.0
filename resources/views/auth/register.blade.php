@extends('layouts.app')

@section('header')
<script type="text/javascript">
    window.onload = function() {
        localStorage.removeItem("ng2-webstorage|user_data");
    }
</script>
@endsection
@section('content')
<div class="container max-420">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <!-- <div class="panel-heading">Register</div> -->
                <div class="panel-body text-center">

                    <div class="dilbert-logo">
                        <img src="{{ url('img/dilbert.jpg') }}" alt="">
                        <h3 class="logo">Dilbert</h3>
                        <div class="sub-title">@lang('lang.hero_title')</div>
                    </div>

                    <hr>

                    <div id="forsignup" class="login-helper">
                        <h4 class="normal">@lang('lang.sign_up')@lang('lang._change_ur_work_way')</h4>
                        <div class="sub-title">@lang('lang.conn_using_google_acc')</div>
                        <!-- Form for sign-up - not used -->
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                            {{ csrf_field() }}

                            <div class="hidden form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Name</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}">

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="hidden form-group{{ $errors->has('email') ? ' has-error' : '' }}">
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

                            <div class="hidden form-group{{ $errors->has('password') ? ' has-error' : '' }}">
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

                            <div class="hidden form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation">

                                    @if ($errors->has('password_confirmation'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group hidden">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-btn fa-user"></i> @lang('lang.register')
                                    </button>
                                </div>
                            </div>

                            <a href="redirect/google" class="btn google-signin">
                                <i class="fa fa-google-plus"></i>
                                @lang('lang.sign_up') @lang('lang.with_google')
                                <img src="img/btn_google_signin_dark_web.png" width="160" id="google_btn" alt="">
                            </a>
                        </form>
                        <!-- End of signup form - not used -->
                    </div>
                </div>
            </div>
            <div id="state-signup" class="out-of-pannel">
                <p>@lang('lang.already_hv_acc_in_dil') <a href="{{ ('/login')}}" class="sign-in-trigger">@lang('lang.sign_in')!</a></p>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // this set adds a class landing-page to body tag
    bodyclass = " signup-page";
    document.body.className += bodyclass;
</script>
@endsection
