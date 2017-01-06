@extends('layouts.app')

@section('header')
@endsection
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3 col-xs-4 col-xs-offset-2">
			<div class="card">
				<h2 class="title-404"> Oops!! </h2>
				<p class="content-404">@lang('lang.wrong_turn'). @lang('lang.dont_worry').... @lang('lang.it_happens').</p>
				<p class="content-404">@lang('lang.little_map_track') :</p>
				<div class="row">
					<div class="col-md-6 btn-404">
			        	<a href="{{ url('/') }}" class="btn btn-hero">@lang('lang.back_2_main')</a>
			        </div>
					<div class="col-md-6 btn-404">
						@if(Auth::guest())
				            <a href="{{ url('/login') }}" class="btn btn-hero">@lang('lang.back_2_login')</a>
				        @else
				            <a href="{{ url('/home') }}" class="btn btn-hero">@lang('lang.back_2_dashboard')</a>
				        @endif
			        </div>
			    </div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('footer')
@endsection