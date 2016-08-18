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
        	<table class="table table-striped table-hover" id="tableOrg">
        		<thead>
        			<tr>
	        			<th>Name</th>
	        			<th>Domain</th>
	        			<th>Logo</th>
	        		</tr>
        		</thead>
        		<tbody>
        			@foreach($orgs as $org)
	        			<tr>
	        				<td style="padding-top:12px"><p>{{$org->name}}</p></td>
	        				<td style="padding-top:12px"> <a href="">{{$org->domain}}</a> </td>
	        				<td> <img src="https://www.google.com/a/cpanel/{{$org->domain}}/images/logo.gif?alpha=1&service=google_default" alt="" style="height:35px;"> </td>
	        				<td style="width:10%"> <a href="/orgs/del/{{$org->id}}" class="btn btn-danger" onclick="confirmDel(this)" style="margin:0px"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Delete</a> </td>
	        			</tr>
        			@endforeach
        		</tbody>
        	</table>
        </div>
    </div>
</div>

@endsection