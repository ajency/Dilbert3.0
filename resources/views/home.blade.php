@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-offset-3 col-lg-6 col-md-offset-3 col-md-6">
            <div class="alert alert-success alert-dismissible alert-fixed" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <center><strong>@lang('lang.welcome') </strong> {{ Auth::user()->name }} !</center>
            </div>
        </div>
    </div>
    <div class="row" style="display:none" id="tuckshop_alert">
        <div class="col-lg-offset-2 col-md-offset-3 col-md-6">
            <div class="alert alert-warning alert-dismissible alert-fixed" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <center><strong>Sorry! </strong> This feature is still under development.</center>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('lang.dashboard')</div>

                <div class="panel-body">
                    @lang('lang.2dy_wrk_hrs_cntrbtd') (min 5 hrs, max 10 hrs).
                    {{--*/ $dayCnt = 0; /*--}}
                    <div class="progress">
                        @if(count($logs) > 1)
                            @for($i = 1; $i < count($logs); $i++)   
                                @if($logs[$i - 1]->to_state == "active" && $logs[$i]->from_state == "active")
                                    <div class="progress-bar progress-bar-success progress-bar-striped" style="width: {{ (strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos))*100/36000 }}%"> <!-- hrs * 100 / 10 -->
                                        @if(floor((strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos)) / 3600) > 0)
                                            {{ floor((strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos)) / 3600) }} hr(s)
                                            {{--*/ $dayCnt += floor((strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos)) / 3600) * 60; /*--}}
                                        @endif
                                        @if(((strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos))/3600) - floor((strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos)) / 3600) != 0)
                                            {{ (((strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos))/3600) - floor((strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos)) / 3600)) * 60 }} min(s)
                                            {{--*/ $dayCnt += (((strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos))/3600) - floor((strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos)) / 3600)) * 60; /*--}}
                                        @endif
                                    </div>
                                @elseif($logs[$i - 1]->to_state == "idle" && $logs[$i]->from_state == "idle")
                                    <div class="progress-bar progress-bar-warning progress-bar-striped" style="width:{{ (strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos))*100/36000 }}%">
                                        @if(floor((strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos)) / 3600) > 0)
                                            {{ floor((strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos)) / 3600) }} hr(s)
                                            {{--*/ $dayCnt += floor((strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos)) / 3600) * 60; /*--}}
                                        @endif
                                        @if(((strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos))/3600) - floor((strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos)) / 3600) != 0)
                                            {{ (((strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos))/3600) - floor((strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos)) / 3600)) * 60 }} min(s)
                                            {{--*/ $dayCnt += (((strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos))/3600) - floor((strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos)) / 3600)) * 60; /*--}}
                                        @endif
                                    </div>
                                @elseif($logs[$i - 1]->to_state == "offline" && $logs[$i]->from_state == "offline")
                                    <div class="progress-bar progress-bar-danger progress-bar-striped" style="width:{{ (strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos))*100/36000 }}%">
                                        @if(floor((strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos)) / 3600) > 0)
                                            {{ floor((strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos)) / 3600) }} hr(s)
                                            {{--*/ $dayCnt += floor((strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos)) / 3600) * 60; /*--}}
                                        @endif
                                        @if(((strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos))/3600) - floor((strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos)) / 3600) != 0)
                                            {{ (((strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos))/3600) - floor((strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos)) / 3600)) * 60 }} min(s)
                                            {{--*/ $dayCnt += (((strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos))/3600) - floor((strtotime($logs[$i]->cos) - strtotime($logs[$i - 1]->cos)) / 3600)) * 60; /*--}}
                                        @endif
                                    </div>
                                @else
                                    <div class="progress-bar progress-bar-info progress-bar-striped" style="width:5%">
                                    </div>
                                @endif
                            @endfor
                        @endif
                    </div>

                    @lang('lang.hrs_cntrbtd_2dy'): {{ floor($dayCnt/60) }} @lang('lang.hrs') @lang('lang.and') {{ $dayCnt % 60 }} @lang('lang.mins').
                    <div class="media" style="float:right">
                        <div class="media-left media-middle">
                            <a href="#" class="btn btn-success"></a> @lang('lang.active')
                        </div>
                        <div class="media-left media-middle">
                            <a href="#" class="btn btn-warning"></a> @lang('lang.idle')
                        </div>
                        <div class="media-left media-middle">
                            <a href="#" class="btn btn-danger"></a> @lang('lang.offline')
                        </div>
                        <div class="media-left media-middle">
                            <a href="#" class="btn btn-info"></a> @lang('lang.member_set')
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('lang.this_week_workout')</div>

                <div class="panel-body">

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
