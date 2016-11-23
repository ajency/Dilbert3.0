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
	        				<td style="padding-top:12px"><p><img src="{{$user->avatar}}" height="15px" class="img-circle"/> {{$user->name}}</p></td>
	        				<td style="padding-top:12px"> <a href=""></a> </td>
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
	        				<td style="width:10%"> <a href="/orgs/del/{{$user->id}}" class="btn btn-warning" onclick="confirmDelEmp(this)" style="margin:0px"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>@lang('lang.delete')</a> </td>
	        			</tr>
        			@endforeach
        		</tbody>
        	</table>
        </div>
    </div>
</div>

@endsection