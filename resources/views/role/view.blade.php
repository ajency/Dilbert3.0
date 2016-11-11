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
              @if(isset($roles))
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
              @endif
            </tbody> 
          </table> 
          <center><a href="/roles/add" class="btn btn-success" style="margin:0px"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add new Role </a></center>
        </div> 
    </div> 
</div> 
 
@endsection