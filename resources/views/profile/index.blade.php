@extends('layouts.app')

@section('content')

<div class="container">
    @if (session()->has('status'))
        @if(session('status') == "success")
            <div class="row">
                <div class="col-lg-offset-3 col-lg-6 col-md-offset-3 col-md-6">
                    <div class="alert alert-success alert-dismissible alert-fixed" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <center><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green;float:left"></span> @lang('lang.update_success')</center>
                    </div>
                </div>
            </div>
        @elseif(session('status') == "fail")
            <div class="row">
                <div class="col-lg-offset-3 col-lg-6 col-md-offset-3 col-md-6">
                    <div class="alert alert-danger alert-dismissible alert-fixed" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <center><span class="glyphicon glyphicon-thumbs-down" aria-hidden="true" style="color:red;float:left"></span> <strong>@lang('lang.sorry')!</strong> @lang('lang.update_fail') </center>
                    </div>
                </div>
            </div>
        @endif
    @endif
    <div class="row">
        <div class="col-xs-12 col-md-6 col-md-offset-3">
            <div class="jumbotron" style="padding-top:10px;background-color:transparent">
                <div class="row">
                    <center><h3>@lang('lang.per_details')</h3></center>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    	<center><img src="{{ Auth::user()->avatar }}" class="img-circle"></center> 
                        <form action="/user/edit" method="post">
                            {{ csrf_field() }}
                            {{ method_field('PATCH') }}
                            <input type="hidden" name="empid" value="{{ Auth::user()->id }}"/>
                            <div class="form-group">
                            	@lang('lang.ur_name'):
                            	<input type="text" name="empname" class="form-control" placeholder="Employee's Name" value="{{ Auth::user()->name }}" required autofocus/>
                            </div>
                            <div class="form-group">
                            	@lang('lang.ur_off_email_id'): <strong name="empemail">{{ Auth::user()->email }}</strong>
                            </div>
                            <div class="form-group">
                            	@lang('lang.ur_role_permission'):
                            	<select name="emprole" id="emprole" class="form-control">
                            		<option value="">-- Select a role -- </option>
                            		@if(Auth::user()->role == "admin")
                            			<option value="admin" selected>Admin</option>
                            			<option value="moderator">Moderator</option>
                            			<option value="member">Member</option>
                            		@elseif(Auth::user()->role == "moderator")
                            			<option value="moderator" selected>Moderator</option>
                            			<option value="member">Member</option>
                            		@elseif(Auth::user()->role == "member")
                            			<option value="member" selected>Member</option>
                            		@else
                            			<option value="admin">Admin</option>
                            			<option value="moderator">Moderator</option>
                            			<option value="member">Member</option>
                            		@endif
                            	</select>
                            </div>
                            <div class="form-group">
                                @lang('lang.user_lang'):
                                <select class="form-control" name="emplang">
                                    @foreach (Config::get('app.locales') as $lang => $language)
                                        @if ($lang == Auth::user()->lang)
                                            <option value="{{$lang}}" selected>{{$language}}</option>
                                        @else
                                            <option value="{{$lang}}">{{$language}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                @lang('lang.time_zone'):
                                <select class="form-control" name="emptz">
                                    @foreach ($timeZones as $tz)
                                        @if($tz == Auth::user()->timeZone)
                                            <option value="{{$tz}}" selected>{{$tz}}</option>
                                        @else
                                            <option value="{{$tz}}">{{$tz}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                            	<center><button type="submit" class="btn btn-success"> @lang('lang.update_prof') </button></center>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection