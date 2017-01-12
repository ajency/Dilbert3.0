@extends('layouts.app')

@section('header')
  @if (!session()->has('reload'))
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">-->

    <link rel="stylesheet" href="{{ url('/css/clock.css') }}" />
    <link rel="stylesheet" href="{{ url('/assets/css/font.css') }}" />
    <!-- <link rel="stylesheet" href="./assets/css/style.css" />
    <link rel="stylesheet" href="./assets/css/styles.css"> -->

    <!-- Disable tap highlight on IE -->
    <meta name="msapplication-tap-highlight" content="no">

    <!-- Web Application Manifest -->
    <!-- <link rel="manifest" href="{{ url('/assets/manifest.webmanifest') }}"> -->

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="Web Starter Kit">
    <link rel="icon" sizes="192x192" href="images/touch/chrome-touch-icon-192x192.png">
    <link rel="icon" type="image/x-icon" href="favicon.ico">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Web Starter Kit">
    <link rel="apple-touch-icon" href="images/touch/apple-touch-icon.png">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="images/touch/ms-touch-icon-144x144-precomposed.png">
    <meta name="msapplication-TileColor" content="#2F3BA2">

    <!-- Color the status bar on mobile devices -->
    <meta name="theme-color" content="#2F3BA2"> 
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    -->
    <!-- <script src="https://www.gstatic.com/firebasejs/3.5.2/firebase.js"></script> -->
  @endif
@endsection
@section('content')
  <div id="user-log-content">
  @if (!session()->has('reload'))
    <!-- <app-root> <div class="loading_dashboard"><img src="/img/ring.svg"/></div> </app-root> -->
    <app-root>
      <div class="loading_dashboard">
        <div class='uil-clock-css' style='transform:scale(0.3);'><div class="clock"></div><div class="ptr1"></div><div class="ptr2"></div></div>
      </div>
    </app-root>
  @else
    <div class="loading_dashboard">
      <div class='uil-clock-css' style='transform:scale(0.3);'><div class="clock"></div><div class="ptr1"></div><div class="ptr2"></div></div>
    </div>
  @endif
  <div class="fixed-bottom-color"></div>
  <!-- <div class="fixed-top-color btm-big"></div> -->
  </div>
@endsection

@section('footer')
  @if (session()->has('reload'))
    @if (session('reload') == "true")
      <script type="text/javascript">
        window.onload = function() {
          $('.dropdown-toggle').dropdown();
          localStorage.setItem("ng2-webstorage|user_data", JSON.stringify(<?php echo $leads ?>));
          window.location.reload();
        };
      </script>
    @endif
  @else
      <script type="text/javascript">
        /*window.onload = function() {
          $('.dropdown-toggle').dropdown();
          localStorage.setItem("ng2-webstorage|user_data", JSON.stringify());
        }*/
        window.onload = function() {
          $('.dropdown-toggle').dropdown();
          /*if(JSON.parse(localStorage.getItem("ng2-webstorage|user_data")).hasOwnProperty("emp_name")){
            localStorage.setItem("ng2-webstorage|user_data", JSON.stringify(<?php echo $leads ?>));
            window.location.reload();
          } else {*/
            localStorage.setItem("ng2-webstorage|user_data", JSON.stringify(<?php echo $leads ?>));
          //}
        };
      </script>
  @endif
  <script type="text/javascript" src="{{ url('/views/inline.js') }}"></script>
  <script type="text/javascript" src="{{ url('/views/styles.bundle.js') }}"></script>
  <script type="text/javascript" src="{{ url('/views/scripts.bundle.js') }}"></script>
  <script type="text/javascript" src="{{ url('/views/main.bundle.js') }}"></script>
  <!-- IE required polyfills, in this exact order -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/es6-shim/0.33.3/es6-shim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/systemjs/0.19.20/system-polyfills.js"></script>
  <script src="https://unpkg.com/angular2/es6/dev/src/testing/shims_for_IE.js"></script>
@endsection