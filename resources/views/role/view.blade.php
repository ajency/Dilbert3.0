@extends('layouts.app') 
 
@section('header') 
<style> 
  /*th{ 
    text-align:center; 
  } 
 
  td { 
    text-align: center; 
    vertical-align: middle; 
    padding-top:12px; 
  }*/ 
</style> 
@endsection 
 
@section('content') 
<div class="container"> 
    <div class="row"> 
        <div class="col-xs-12 col-md-8 col-md-offset-2"> 
          <center><h3 style="margin-bottom: 25px;">Roles and Permissions</h3></center>
          <div class="card">
            <div class="table-card-inner">
              <table class="table table- striped table-hover team-table" id="tableOrg" style="margin-bottom: 0;"> 
                <thead> 
                  <tr> 
                    <th style="padding-left: 20px;">Role</th> 
                    <th>Permissions</th>
                    <th></th>
                  </tr> 
                </thead> 
                <tbody> 
                  @if(isset($roles))
                    @foreach($roles as $role) 
                      <tr> 
                        <td class="team-table__cell team-table--username"><p><strong>{{ ucfirst($role->display_name) }}</strong></p></td> 
                        <td class="team-table__cell team-roles" style="width: 50%"> <a href=""></a>
                        @foreach($role->perms as $index => $permission) 
                          @if($index == 0) <span>{{ $permission->display_name }}</span>
                          @else <span>{{ $permission->display_name }}</span>
                          @endif 
                        @endforeach</td> 
                        <td  class="team-table__cell rtp" style="width:15%"> <a href="/roles/edit/{{$role->id}}" class="" style="margin:0px"><!-- <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> --> Edit Role </a> </td> 
                      </tr> 
                    @endforeach 
                  @endif
                </tbody> 
              </table> 
              <center><a href="/roles/add" class="btn btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add new Role </a></center>
            </div>
          </div>
        </div> 
    </div> 
</div>

<div class="fixed-top-color btm-big"></div>
 
@endsection