@extends('layouts.app')

@section('content')
<div class="container">

    <div class="hero shade-btm">
        <h1 class="hero-title">Track Time. Record Work.</h1>
        <p class="hero-tag">A complete productivity solution to track your work, your employees’ work and manage projects without any hassle. And the best thing? <strong>It’s totally free!</strong></p>

        <a href="{{ url('/register') }}" class="btn btn-primary btn-hero">Sign up Now!</a>

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
                    <a href="#" class="btn btn-ghost btn-with-rt-icon nomar">Get the Chrome App <i class="fa fa-angle-right"></i></a>
                    <a href="#" class="btn btn-ghost btn-with-rt-icon nomar">Get the Chrome Extension <i class="fa fa-angle-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="why-dilbert shade-top shade-btm">
        <h3 class="hero-title text-center">
            Why Dilbert?
        </h3>
        <p class="hero-tag text-center">Tracks your time and work so you can focus with ease</p>
        <div class="row feature-row">
            <div class="col-xs-12 text-center visible-xs">
                <img src="img/feat-01.png" alt="" class="feature-img">
            </div>
            <div class="col-md-5 col-md-push-1 col-sm-6 text-center-xs col-xs-12">
                <h3 class="why-sub-t">
                    <span>The</span> Features
                </h3>
                <ul class="features">
                    <li>Accurate time tracking with login and logout timestamps</li>
                    <li>Detailed, auto-generated timesheets and reports</li>
                    <li>Activity level tracker</li>
                    <li>Seamless sync across the website, Chrome app & extenstion</li>
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
                    <span>For</span> Employers
                </h3>
                <ul class="features">
                    <li>Manage your organisation</li>
                    <li>Keep a track of all your active projects</li>
                    <li>Help your employees manage their time better</li>
                    <li>Detailed reporting with accurate timesheets generated daily</li>
                </ul>
            </div>
        </div>
        <div class="row feature-row">
            <div class="col-xs-12 text-center visible-xs">
                <img src="img/feat-03.png" alt="" class="feature-img">
            </div>
            <div class="col-md-5 col-md-push-1 col-sm-6 text-center-xs col-xs-12">
                <h3 class="why-sub-t">
                    <span>For</span> Employees
                </h3>
                <ul class="features">
                    <li>Manage all your projects</li>
                    <li>Keep a track of all the tasks you've done and yet to do</li>
                    <li>No distractions, increase your productivity</li>
                    <li>Ensure you meet your daily and weekly goals</li>
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
                <h3 class="cta-title">Get your own Dilbert now</h3>
                <div class="cta-sub-title">Just sign in using your Google account and leave the hustle n bustle to us!</div>
            </div>
            <div class="col-md-4 col-sm-6 text-right text-center-xs">
                <a href="#" class="btn btn-primary btn-cta">Sign up for Dilbert now</a>
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
