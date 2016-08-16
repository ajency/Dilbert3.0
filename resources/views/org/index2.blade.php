@extends('layouts.app')

@section('header')
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-md-offset-3 col-md-6">
            <div class="alert alert-info alert-dismissible alert-fixed" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <center><strong>Whoops! </strong> Seems like your organization is not present in our list.</center>
            </div>
        </div>
    </div>
    <div class="row" style="display:none" id="tuckshop_alert">
        <div class="col-xs-12 col-md-offset-3 col-md-6">
            <div class="alert alert-warning alert-dismissible alert-fixed" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <center><strong>Sorry! </strong> Seems like you didn't complete the statement.</center>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-md-6 col-md-offset-3">
            <div class="jumbotron" style="padding-top:10px">
                <div class="row">
                    <center><h3>Organization</h3></center>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <form action="/org/save" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                Name of the Organization<span style="color:red">*</span> <input type="text" name="orgname" class="form-control" placeholder="Organization's Name" value="{{ old('orgname') }}" required autofocus/>
                            </div>
                            <div class="form-group">
                                Domain of the Organization<span style="color:red">*</span> <input type="text" name="orgdomain" class="form-control" placeholder="Organization's Domain" value="{{$account->user['domain']}}" required/>
                            </div>
                            <div class="form-group">
                                Logo of the company <input type="url" name="orglogo" class="form-control" placeholder="http://organization_name/logo.png" title="Tip: refer to 'https://www.youtube.com/watch?v=qkbYXqi-C4o' for a better idea"/>
                            </div>
                            <div class="form-group">
                                Default Timezone<span style="color:red">*</span>: 
                                <select class="form-control" name="defaulttz" required> 
                                    <!-- <option> -- Select the timezone that rest of the branches refer to -- </option> -->
                                    <option>-- Select primary timezone --</option>
                                    <option value="IST">Indian Standard Time (UTC+5:30)</option>
                                    <option value="BST">British Standard Time (UTC+1:00)</option>
                                    <option value="CET">Central European Time (UTC+1:00)</option>
                                    <option value="EET">Eastern European Time (UTC+2:00)</option>
                                    <option value="PST">Pacific Standard Time (UTC-8:00) [North America]</option>
                                    <option value="PYT">Paraguay Time (UTC-4:00) [South America]</option>
                                    <option value="SST">Singapore Standard Time (UTC+8:00)</option>
                                    <option value="WET">Western European Time (UTC+0:00)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                Alternate Timezone(s):
                                <table class="table" id="newTimeTable">
                                    <tbody>
                                        <tr style="display:none">
                                            <td>
                                                <select name="alttime[]" class="form-control" onchange="overrideSelect(this)">
                                                    <option>-- Select other timezone(s) --</option>
                                                    <option value="IST">Indian Standard Time (UTC+5:30)</option>
                                                    <option value="BST">British Standard Time (UTC+1:00)</option>
                                                    <option value="CET">Central European Time (UTC+1:00)</option>
                                                    <option value="EET">Eastern European Time (UTC+2:00)</option>
                                                    <option value="PST">Pacific Standard Time (UTC-8:00) [North America]</option>
                                                    <option value="PYT">Paraguay Time (UTC-4:00) [South America]</option>
                                                    <option value="SST">Singapore Standard Time (UTC+8:00)</option>
                                                    <option value="WET">Western European Time (UTC+0:00)</option>
                                                </select>
                                            </td>
                                            <td><button type="button" id="rmvbtn" class="btn btn-default" style="padding: 6px 13px" onclick="deleteTableRow(this)"> Remove </button></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <select class="form-control" disabled>
                                                    <option>-- Select other timezone(s) --</option>
                                                    <option value="IST">Indian Standard Time (UTC+5:30)</option>
                                                    <option value="BST">British Standard Time (UTC+1:00)</option>
                                                    <option value="CET">Central European Time (UTC+1:00)</option>
                                                    <option value="EET">Eastern European Time (UTC+2:00)</option>
                                                    <option value="PST">Pacific Standard Time (UTC-8:00) [North America]</option>
                                                    <option value="PYT">Paraguay Time (UTC-4:00) [South America]</option>
                                                    <option value="SST">Singapore Standard Time (UTC+8:00)</option>
                                                    <option value="WET">Western European Time (UTC+0:00)</option>
                                                </select>
                                            </td>
                                            <td><button type="button" id="addbtn" class="btn btn-default" style="padding: 6px 25px" onclick="addTableRow()"> Add </button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group">
                                IP address List:
                                <table class="table" id="newIPTable">
                                    <tbody>
                                        <tr style="display:none">
                                            <td><input type="text" name="ip[]" class="form-control" placeholder="IP address" title="Helps to track if Employee is working from home or at office" required></td>
                                            <td>
                                                <select class="form-control" name="ipstatus[]" title="Public IP address keeps changing, hence needs to be updated. Private IP remains constant.">
                                                    <option> -- Type of IP -- </option>
                                                    <option value="static">Private address (static)</option>
                                                    <option value="dynamic">Public address (dynamic)</option>
                                                </select>
                                            </td>
                                            <td><button type="button" id="rmvipbtn" class="btn btn-default" style="padding: 6px 13px" onclick="removeIPTableRow(this)"> Remove </button></td>
                                        </tr>
                                        <tr>
                                            <td><input name="ip[]" type="text" class="form-control" placeholder="IP address" title="Helps to track if Employee is working from home or at office"></td>
                                            <td>
                                                <select class="form-control" name="ipstatus[]" title="Public IP address keeps changing, hence needs to be updated. Private IP remains constant.">
                                                    <option> -- Type of IP -- </option>
                                                    <option value="static">Private address (static)</option>
                                                    <option value="dynamic">Public address (dynamic)</option>
                                                </select>
                                            </td>
                                            <td><button type="button" id="addipbtn" class="btn btn-default" style="padding: 6px 25px" onclick="addIPTableRow()"> Add </button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <button type="submit" class="btn btn-success"> Add Organization </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
    <script src="../js/style.js"></script>
@endsection