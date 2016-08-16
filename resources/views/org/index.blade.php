<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Dilbert</title>
        <link rel="stylesheet" href="../css/bootstrap.min.css" />
        <link rel="stylesheet" href="../css/font-awesome.min.css" />
        <link rel="stylesheet" href="../css/font.css" />
        <link rel="stylesheet" href="../css/styles.css" />
        <link rel="stylesheet" href="../css/style.css" />
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    </head>
    <body>
        
        <div class="full-wrapper">
            <header class="header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-7">
                            <h3 class="logo">Dilbert</h3>
                        </div>
                        <div class="col-xs-5 text-right">
                            <a href="#">Contact Us</a>
                        </div>
                    </div>
                </div>
            </header>
            <div class="container max-767">
                <div class="steps-wrapper only-two">
                    <div class="step done">
                        <span class="step-count">
                            <i class="fa fa-check icon"></i>
                            <span class="count">1</span>
                        </span>
                        <span class="step-label">Step 1</span>
                        <span class="step-title">Sign in with Google</span>
                    </div>
                    <div class="step current">
                        <span class="step-count">
                            <i class="fa fa-check icon"></i>
                            <span class="count">2</span>
                        </span>
                        <span class="step-label">Step 2</span>
                        <span class="step-title">Create an Organisation</span>
                    </div>
                    <div class="step hidden">
                        <span class="step-count">
                            <i class="fa fa-check icon"></i>
                            <span class="count">3</span>
                        </span>
                        <span class="step-label">Step 3</span>
                        <span class="step-title">Add your Personal Details</span>
                    </div>
                </div>

                <div class="no-organisation alert alert-warning alert-dismissible">
                    <i class="fa fa-exclamation-triangle alert-icon"></i>
                    <span class="alert-data">
                        Looks like no organisation has been associated with the domain <strong> {{ucfirst($account->user['domain'])}} </strong><br>
                        Create your Organisation below and get started!
                    </span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="page-title">
                    <h1 class="normal">Join <strong>Dilbert</strong></h1>
                    <h6 class="sub-title">Create an Organisation</h6>
                </div>

                <div class="row">
                    <div class="col-xs-8">
                        <form action="/org/save" method="post" id="create-organisation">
                            {{ csrf_field() }}
                            <div class="row top-row">
                                <div class="col-xs-6">
                                    <label for="logoUpload" class="upload-label">
                                        <input type="file" id="logoUpload">
                                        <span class="upload-text">
                                            <i class="fa fa-camera"></i>
                                            Upload Logo
                                        </span>
                                    </label>
                                </div>
                                <div class="col-xs-6 posrel setheight">
                                    <div class="bottom-align">
                                        <div class="form-group">
                                            <!-- <label for="orgName">Organisation Name</label> -->
                                            <input type="text" id="orgName" name="orgname" class="form-control" placeholder="The Organisation Name" value="{{ old('orgname') }}" required autofocus/>
                                            <!-- <div id="orgName" name="orgname" contentEditable="true" class="form-control" placeholder="The Organisation Name" value="{{ old('orgname') }}" required autofocus/></div> -->
                                        </div>
                                        <div class="domain">
                                            Domain: <strong> @ {{$account->user['domain']}} </strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="defaultTimezones">Default Timezone <span style="color:red">*</span></label>
                                <input type="text" id="defaultTimezones" class="form-control" placeholder="Type and select timezone"/>
                            </div>
                            <div class="form-group">
                                <label for="allowedTimezones">Allowed Timezone</label>
                                <div class="selected-zones" id="added_alt_zones">
                                    <!-- <div class="zone">
                                        <strong>ADT</strong>
                                        - Atlantic Daylight Time (UTC-03)
                                        <a href="#" class="remove-zone">&times;</a>
                                    </div>
                                    <div class="zone">
                                        <strong>IST</strong>
                                        - Indian Standard Time (UTC+05:30)
                                        <a href="#" class="remove-zone">&times;</a>
                                    </div> -->
                                </div>
                                <div class="col-xs-10" style="padding-left:0px">
                                    <input type="text" id="allowedTimezones" class="form-control" placeholder="Type and select timezones">
                                </div>
                                <div class="col-xs-2" style="margin-top:-10px">
                                    <button type="button" class="btn" id="add_alt_tz"> <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="timeidle">Time until Idle <span style="color:red">*</span></label>
                                        <input type="number" id="timeidle" class="form-control" placeholder="Enter time in minutes">
                                    </div>
                                </div>
                            </div>
                            <div id="iplist">
                                <div class="row" id="iprow">
                                    <div class="col-xs-6" id="iptext"> <!-- type IP address -->
                                        <div class="form-group">
                                            <label for="ipaddr">IP address <span style="color:red">*</span></label>
                                            <input type="text" id="ipaddr" name="ip[]" class="form-control" placeholder="Enter IP address ex. 127.0.0.1">
                                        </div>
                                    </div>
                                     <div class="col-xs-5" id="ipselect"> <!-- select Static or Dynamic IP -->
                                        <div class="form-group posrel">
                                            <label for="ipadd">Type of IP address  <span style="color:red">*</span></label>
                                            <select id="ipadd" class="form-control" name="ipstatus[]" title="Public IP address keeps changing, hence needs to be updated. Private IP remains constant.">
                                                <option value=""> -- Select Type of IP -- </option>
                                                <option value="static">Private address (static)</option>
                                                <option value="dynamic">Public address (dynamic)</option>
                                            </select>
                                            <i class="fa fa-sort noclick"></i>
                                        </div>
                                    </div>
                                    <div class="col-xs-1" id="ipbtn"> <!-- remove button -->
                                        <div class="form-group posrel">
                                            <button class="btn btn-default" style="margin-top:35px" onclick="removeIP(this)"><span aria-hidden="true" style="color:red">&times;</span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 text-right">
                                    <button type="button" class="btn btn-default" id="addIP" style="margin-top:-10px"> Add new IP address</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 text-right">
                                    <button class="btn btn-primary">Create Organisation</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-xs-4">
                        <div class="rt-text-org">
                            <i class="organisation-icon"></i>
                            <h3 class="text-center">Organisations</h3>
                            <div class="org-expln">
                                <p>Create your organisation with your unique domain name</p>
                                <p>Manage all your employees and projects in one place</p>
                                <p>Generate reports for your entire organisation automatically</p>
                                <p>Create multiple organisations, each with a unique domain name</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="../js/jQuery.1.12.2.js"></script>

        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/custom.js"></script>
    </body>
</script>