@extends('layouts.app')

@section('content')
<div class="container">

    <div class="hero shade-btm">
        <h1 class="hero-title"> @lang('lang.hero_title') </h1>
        <p class="hero-tag">@lang('lang.hero_tag')</p>

        @if(Auth::guest())
            <a href="{{ url('/register') }}" class="btn btn-primary btn-hero">@lang('lang.sign_up') @lang('lang.now')!</a>
        @else
            <a href="{{ url('/home') }}" class="btn btn-primary btn-hero">@lang('lang.go_2_dashbrd')!</a>
        @endif

        <div class="hero-image">
            <img src="img/hero-img.png" class="img-responsive" alt="">
        </div>
    </div>
    
    <div class="chrome-store-section max-w-767">
        <div class="row">
            <div class="col-md-4 col-sm-5 text-center-xs">
                <img src="img/web-store.png" class="chrome-badge" alt="">
            </div>
            <div class="col-md-8 col-sm-7 text-right text-center-xs">
                <div class="btn-group">
                    <a href="#" class="btn btn-ghost btn-with-rt-icon nomar">{{ trans('lang.get_the') }} Chrome App <i class="fa fa-angle-right"></i></a>
                    <a href="#" class="btn btn-ghost btn-with-rt-icon nomar">{{ trans('lang.get_the') }} Chrome Extension <i class="fa fa-angle-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="why-dilbert shade-top shade-btm">
        <h3 class="hero-title text-center">
            @lang('lang.why') Dilbert?
        </h3>
        <p class="hero-tag text-center">@lang('lang.main_hero_tag')</p>
        <div class="row feature-row">
            <div class="col-xs-12 text-center visible-xs">
                <img src="img/feat-01.png" alt="" class="feature-img">
            </div>
            <div class="col-md-5 col-md-push-1 col-sm-6 text-center-xs col-xs-12">
                <h3 class="why-sub-t">
                    @lang('lang.the_features')
                </h3>
                <ul class="features">
                    @lang('lang.features')
                </ul>
            </div>
            <div class="col-md-6 col-sm-5 text-center hidden-xs">
                <img src="img/feat-01.png" alt="" class="feature-img">
            </div>
        </div>
        <div class="row feature-row">
            <div class="col-md-5 col-md-push-1 col-sm-6 text-center-xs col-xs-12">
                <img src="img/feat-02.png" alt="" class="feature-img">
            </div>
            <div class="col-md-5 col-md-push-1 col-sm-6 text-center-xs col-xs-12">
                <h3 class="why-sub-t">
                    @lang('lang.for_employers')
                </h3>
                <ul class="features">
                    @lang('lang.employers')
                </ul>
            </div>
        </div>
        <div class="row feature-row">
            <div class="col-xs-12 text-center visible-xs">
                <img src="img/feat-03.png" alt="" class="feature-img">
            </div>
            <div class="col-md-5 col-md-push-1 col-sm-6 text-center-xs col-xs-12">
                <h3 class="why-sub-t">
                    @lang('lang.for_employees')
                </h3>
                <ul class="features">
                    @lang('lang.employees')
                </ul>
            </div>
            <div class="col-md-6 col-sm-6 text-center hidden-xs">
                <img src="img/feat-03.png" alt="" class="feature-img">
            </div>
        </div>
    </div>

    <div class="cta">
        <div class="row">
            <div class="col-md-7 col-md-push-1 col-sm-6 text-center-xs">
                <h3 class="cta-title">@lang('lang.main_get_title')</h3>
                <div class="cta-sub-title">@lang('lang.main_get_sub_title')</div>
            </div>
            <div class="col-md-4 col-sm-6 text-right text-center-xs">
                @if(Auth::guest())
                    <a href="#" class="btn btn-primary btn-cta">@lang('lang.main_get_sub_btn')</a>
                @else
                    <a href="#" class="btn btn-primary btn-cta">@lang('lang.main_get_sub_dashbrd_btn')</a>
                @endif
            </div>
        </div>
    </div>

</div>

<script type="text/javascript">
    // this set adds a class landing-page to body tag
    bodyclass = " landing-page";
    document.body.className += bodyclass;
</script>
@endsection
