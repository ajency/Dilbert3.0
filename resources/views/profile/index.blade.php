@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-xs-12 col-md-6 col-md-offset-3">
            <div class="jumbotron" style="padding-top:10px">
                <div class="row">
                    <center><h3>Personal Details</h3></center>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    	<center><img src="{{ Auth::user()->avatar }}" class="img-circle"></center> 
                        <form action="/user/edit" method="post">
                            {{ csrf_field() }}
                            {{ method_field('PATCH') }}
                            <input type="hidden" name="empid" value="{{ Auth::user()->id }}"/>
                            <div class="form-group">
                            	Your Name:
                            	<input type="text" name="empname" class="form-control" placeholder="Employee's Name" value="{{ Auth::user()->name }}" required autofocus/>
                            </div>
                            <div class="form-group">
                            	Your official Email-ID:
                            	<input type="email" name="empemail" class="form-control" placeholder="Employee's Email-ID" value="{{ Auth::user()->email }}" required />
                            </div>
                            <div class="form-group">
                            	Your role/permissions:
                            	<select name="emprole" id="emprole" class="form-control">
                            		<option value="">-- Select a role -- </option>
                            		@if(Auth::user()->role == "admin")
                            			<option value="admin" selected>Admin</option>
                            			<option value="moderate">Moderate</option>
                            			<option value="member">Member</option>
                            		@elseif(Auth::user()->role == "moderate")
                            			<option value="admin">Admin</option>
                            			<option value="moderate" selected>Moderate</option>
                            			<option value="member">Member</option>
                            		@elseif(Auth::user()->role == "member")
                            			<option value="admin">Admin</option>
                            			<option value="moderate">Moderate</option>
                            			<option value="member" selected>Member</option>
                            		@else
                            			<option value="admin">Admin</option>
                            			<option value="moderate">Moderate</option>
                            			<option value="member">Member</option>
                            		@endif
                            	</select>
                            </div>
                            <div class="form-group">
                            	<center><button type="submit" class="btn btn-success"> Update Profile </button></center>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection