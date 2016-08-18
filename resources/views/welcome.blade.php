@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome</div>

                <div class="panel-body">
                    Your Application's Landing Page.
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // this set adds a class landing-page to body tag
    var bodyclass=document.createAttribute("class");
    bodyclass.value="landing-page";
    document.getElementsByTagName("body")[0].setAttributeNode(bodyclass);
</script>
@endsection
