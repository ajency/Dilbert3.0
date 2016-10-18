@extends('layouts.app')

@section('content')

<div class="container">
    @if (session()->has('status'))
        @if(session('status') == "success" && session('display') == "add")
            <div class="row">
                <div class="col-lg-offset-3 col-lg-6 col-md-offset-3 col-md-6">
                    <div class="alert alert-success alert-dismissible alert-fixed" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <center><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green;float:left"></span>New Role Successfully created !!</center>
                    </div>
                </div>
            </div>
        @elseif(session('status') == "success" && session('display') == "edit")
            <div class="row">
                <div class="col-lg-offset-3 col-lg-6 col-md-offset-3 col-md-6">
                    <div class="alert alert-success alert-dismissible alert-fixed" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <center><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green;float:left"></span>New Role Successfully updated !!</center>
                    </div>
                </div>
            </div>
        @elseif(session('status') == "fail")
            <div class="row">
                <div class="col-lg-offset-3 col-lg-6 col-md-offset-3 col-md-6">
                    <div class="alert alert-danger alert-dismissible alert-fixed" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <center><span class="glyphicon glyphicon-thumbs-down" aria-hidden="true" style="color:red;float:left"></span><strong>@lang('lang.sorry')!</strong>Couldn't upload your new Role & it's Permission</center>
                    </div>
                </div>
            </div>
        @endif
    @endif
    
    <div class="row">
        @if($display == "add")
            <div class="col-xs-12 col-md-6 col-md-offset-3">
                <div class="jumbotron personal-data">
                    <div class="row">
                        <center><h3 class="jumbo-title">Create new Role</h3></center>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                        	<form action="/roles/create" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="status" value="{{$display}}"/>
                                <div class="form-group">
                                	Role Name:
                                	<input type="text" name="rolename" class="form-control" placeholder="Role" value="" required autofocus/>
                                </div>
                                <div class="form-group">
                                    Role Display Name:
                                    <input type="text" name="roledisplayname" class="form-control" placeholder="Role Display" required/>
                                </div>
                                <div class="form-group">
                                    Role Description:
                                    <input type="text" name="roledescription" class="form-control" placeholder="Role Description" required/>
                                </div>
                                <div class="form-group posrel">
                                	Permission for the Role:
                                	<select name="newpermission" id="newpermission" class="form-control">
                                		<option value="">-- Select permission for role --</option>
                                		@foreach($permissions as $permission)
                                            <option value="{{$permission->name}}">{{$permission->display_name}}</option>
                                        @endforeach
                                        <option value="-1">Add new Permission</option>
                                	</select>
                                    <i class="fa fa-sort noclick"></i>
                                </div>
                                <div class="form-group permission" style="display:none">
                                    Permission Name:
                                    <input type="text" name="permissionname" class="form-control" placeholder="Permission" value=""/>
                                </div>
                                <div class="form-group permission" style="display:none">
                                    Permission Display Name:
                                    <input type="text" name="permissiondisplayname" class="form-control" placeholder="Permission Display"/>
                                </div>
                                <div class="form-group permission" style="display:none">
                                    Permission Description:
                                    <input type="text" name="permissiondescription" class="form-control" placeholder="Permission Description"/>
                                </div>
                                <div class="form-group">
                                	<center><button type="submit" class="btn btn-primary"> Create new Role </button></center>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($display == "edit")
            <div class="col-xs-12 col-md-6 col-md-offset-3">
                <div class="jumbotron personal-data">
                    <div class="row">
                        <center><h3 class="jumbo-title">Edit this Role</h3></center>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <form action="/roles/edits" method="post">
                                {{ csrf_field() }}
                                {{ method_field('PATCH') }}
                                 <input type="hidden" name="status" value="{{$display}}"/>
                                 <input type="hidden" name="roleid" value="{{$role->id}}"/>
                                <div class="form-group">
                                    Role Name:
                                    <strong>{{$role->name}}</strong><input type="hidden" name="rolename" value="{{$role->name}}"/>
                                </div>
                                <div class="form-group">
                                    Role Display Name:
                                    <input type="text" name="roledisplayname" class="form-control" placeholder="Role Display" value="{{$role->display_name}}" required autofocus/>
                                </div>
                                <div class="form-group">
                                    Role Description:
                                    <input type="text" name="roledescription" class="form-control" placeholder="Role Description" value="{{$role->description}}" required/>
                                </div>
                                <div class="form-group posrel">
                                    Permission for the Role:
                                    <select name="newpermission" id="newpermission" class="form-control">
                                        <option value="">-- Select permission for role --</option>
                                        @foreach($permissions as $permission)
                                            <option value="{{$permission->name}}">{{$permission->display_name}}</option>
                                        @endforeach
                                        <option value="-1">Add new Permission</option>
                                    </select>
                                    <i class="fa fa-sort noclick"></i>
                                </div>
                                <div class="form-group permission" style="display:none">
                                    Permission Name:
                                    <input type="text" name="permissionname" class="form-control" placeholder="Permission" value=""/>
                                </div>
                                <div class="form-group permission" style="display:none">
                                    Permission Display Name:
                                    <input type="text" name="permissiondisplayname" class="form-control" placeholder="Permission Display" value=""/>
                                </div>
                                <div class="form-group permission" style="display:none">
                                    Permission Description:
                                    <input type="text" name="permissiondescription" class="form-control" placeholder="Permission Description" value=""/>
                                </div>
                                <div class="form-group">
                                    <center><button type="submit" class="btn btn-primary"> Update this Role </button></center>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@endsection


role/view.blade.php

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
        	<center><h3>Roles and Permissions</h3></center>
        	<table class="table table-striped table-hover" id="tableOrg">
        		<thead>
        			<tr>
	        			<th>Role</th>
	        			<th>Permissions</th>
	        		</tr>
        		</thead>
        		<tbody>
        			@foreach($roles as $role)
	        			<tr>
	        				<td style="padding-top:12px"><p>{{ ucfirst($role->display_name) }}</p></td>
	        				<td style="padding-top:12px"> <a href=""></a>@foreach($role->perms as $index => $permission)
	        				@if($index == 0){{ $permission->display_name }}
	        				@else, {{ $permission->display_name }}
	        				@endif
	        				@endforeach</td>
	        				<td style="width:10%"> <a href="/roles/edit/{{$role->id}}" class="btn btn-warning" style="margin:0px"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit Role </a> </td>
	        			</tr>
        			@endforeach
        		</tbody>
        	</table>
        	<center><a href="/roles/add" class="btn btn-success" style="margin:0px"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add new Role </a></center>
        </div>
    </div>
</div>

@endsection