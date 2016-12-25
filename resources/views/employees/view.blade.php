@extends('layouts.app')

@section('header')
<style>
	th{
		text-align:center;
	}

	td {
		text-align: center;
		vertical-align: middle;
		/*padding-top:12px;*/
	}
</style>
@endsection

@section('content')
<div class="container">
	@if (session()->has('status'))
		<div class="row">
		        <div class="col-lg-offset-3 col-lg-6 col-md-offset-3 col-md-6 col-xs-12">
		        	@if(session('status') == "success")
			            <div class="alert alert-success alert-dismissible alert-fixed" role="alert">
			              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			              <center>@lang('lang.succ_del')  <strong>{{ session('name')}}</strong> !</center>
			            </div>
			        @elseif(session('status') == "invalid")
			        	<div class="alert alert-warning alert-dismissible alert-fixed" role="alert">
			              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			              <center><strong>@lang('lang.sorry')</strong>! @lang('lang.cant_delete'). @lang('lang.only_admin')</center>
			            </div>
			        @else
			        	<div class="alert alert-danger alert-dismissible alert-fixed" role="alert">
			              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			              <center><strong>@lang('lang.per_denied')</strong>!</center>
			            </div>
			        @endif
		        </div>
		    </div>
		</div>
	@endif
    <div class="row">
        <div class="col-xs-12 col-md-8 col-md-offset-2">
        	<center><h3>Organizations</h3></center>
        	<table class="table table-striped table-hover" id="tableEmployee">
        		<thead>
        			<tr>
	        			<th>@lang('lang.name')</th>
	        			<th>@lang('lang.company_name')</th>
	        			<th>@lang('lang.role')</th>
	        		</tr>
        		</thead>
        		<tbody>
        			@foreach($users as $user)
	        			<tr>
	        				<td style="padding-top:12px"><p><img src="{{$user->avatar}}" height="15px" class="img-circle"/>@if(auth()->user()->name != $user->name)<a style="color:#606060" href="/dashboard/{{$user->id}}">{{$user->name}}</a>@else{{$user->name}}@endif</p></td>
	        				<td style="padding-top:12px"> <img src="https://www.google.com/a/cpanel/{{$orgLogo->domain}}/images/logo.gif?alpha=1&service=google_default" alt="" style="height:35px;"> </td>
	        				<td>
	        					<input type="hidden" name="user_id" value="{{$user->id}}"/>
	        					<select class="form-control" onchange="confirmRoleChange(this)">
	        						@foreach($roles as $role)
	        							@if($role->name == $user->role)
	        								<option value="{{$role->name}}" selected> {{ $role->display_name }} </option>
	        							@else
	        								<option value="{{$role->name}}"> {{ $role->display_name }} </option>
	        							@endif
	        						@endforeach
	        					</select> 
	        				</td>
	        				<td style="width:10%"> <a href="/employees/delete/{{$user->id}}" class="btn btn-warning" onclick="confirmDelEmp(this)" style="margin:0px"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>@lang('lang.delete')</a> </td>
	        			</tr>
        			@endforeach
        		</tbody>
        	</table>
        </div>
    </div>
</div>

@endsection