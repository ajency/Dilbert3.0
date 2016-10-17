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
        <div class="col-md-8">
            <h1 class="dashboard-title">Welcome, Strange!</h1>
            <h4 class="sub-title dash">Track Time. Record Work. Boost Productivity.</h4>

            <div class="last-few-days card">
                <div class="card-header">
                    <div class="btn-group change-last-view">
                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        This Month <span class="fa fa-angle-down"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                        </ul>
                    </div>
                    <a href="#" class="show-hide-days pull-right"><small>Hide Sundays</small></a>
                </div>
                <div class="card-body">
                    <div class="month-view hscroll-table">
                        <div class="table-responsive setpadding">
                            <table class="table first-table">
                                <thead>
                                    <tr>
                                        <th><span class="th-label small-title">Mon</span></th>
                                        <th><span class="th-label small-title">Tue</span></th>
                                        <th><span class="th-label small-title">Wed</span></th>
                                        <th><span class="th-label small-title">Thu</span></th>
                                        <th><span class="th-label small-title">Fri</span></th>
                                        <th><span class="th-label small-title">Sat</span></th>
                                        <th class="sunday"><span class="th-label small-title">Sun</span></th>
                                        <th class="total"><span class="th-label small-title">Total</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="current">
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">8:30</span> |
                                                    <span class="th-break">1:00</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">8:30</span> |
                                                    <span class="th-break">1:00</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">8:30</span> |
                                                    <span class="th-break">1:00</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">8:30</span> |
                                                    <span class="th-break">1:00</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">8:30</span> |
                                                    <span class="th-break">1:00</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day current" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">8:30</span> |
                                                    <span class="th-break">1:00</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td class="sunday">
                                            <!-- <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">8:30</span> |
                                                    <span class="th-break">1:00</span>
                                                </span>
                                            </a> -->
                                        </td>
                                        <td>
                                            <a href="#" class="single-day total current" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">8:30</span> |
                                                    <span class="th-break">1:00</span>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">8:30</span> |
                                                    <span class="th-break">1:00</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">8:30</span> |
                                                    <span class="th-break">1:00</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">8:30</span> |
                                                    <span class="th-break">1:00</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">8:30</span> |
                                                    <span class="th-break">1:00</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">8:30</span> |
                                                    <span class="th-break">1:00</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">8:30</span> |
                                                    <span class="th-break">1:00</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td class="sunday">
                                            <!-- <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">8:30</span> |
                                                    <span class="th-break">1:00</span>
                                                </span>
                                            </a> -->
                                        </td>
                                        <td>
                                            <a href="#" class="single-day total" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">8:30</span> |
                                                    <span class="th-break">1:00</span>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">8:30</span> |
                                                    <span class="th-break">1:00</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">8:30</span> |
                                                    <span class="th-break">1:00</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">8:30</span> |
                                                    <span class="th-break">1:00</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">8:30</span> |
                                                    <span class="th-break">1:00</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">8:30</span> |
                                                    <span class="th-break">1:00</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">8:30</span> |
                                                    <span class="th-break">1:00</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td class="sunday">
                                            <!-- <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">8:30</span> |
                                                    <span class="th-break">1:00</span>
                                                </span>
                                            </a> -->
                                        </td>
                                        <td>
                                            <a href="#" class="single-day total" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">8:30</span> |
                                                    <span class="th-break">1:00</span>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="stats-view">
                        <h4 class="card-title">Stats since Oct 01</h4>
                        <div class="horizontal-stats">
                            <div class="stat-wrap">
                                <div class="stat-count">
                                    <strong>109:20</strong>
                                    <span class="stat-dev increase"><i class="fa fa-caret-up"></i> 1.5%</span>
                                </div>
                                <div class="stat-title small-title">Total Time</div>
                            </div>
                            <div class="stat-wrap">
                                <div class="stat-count">
                                    <strong>95:49</strong>
                                    <span class="stat-dev decrease"><i class="fa fa-caret-down"></i> 1.5%</span>
                                </div>
                                <div class="stat-title small-title">Total Work Time</div>
                            </div>
                            <div class="stat-wrap">
                                <div class="stat-count">
                                    <strong>09:52 <span>AM</span></strong>
                                    <span class="stat-dev increase"><i class="fa fa-caret-up"></i> 1.5%</span>
                                </div>
                                <div class="stat-title small-title">Avg. Say Start</div>
                            </div>
                            <div class="stat-wrap">
                                <div class="stat-count">
                                    <strong>0.47</strong>
                                    <span class="stat-dev decrease"><i class="fa fa-caret-down"></i> 1.5%</span>
                                </div>
                                <div class="stat-title small-title">Day start deviates by</div>
                            </div>
                            <div class="stat-wrap text-right">
                                <a href="#" class="more-stats"><small>More stats</small></a>
                            </div>
                        </div>
                        <!-- Please add the line chart here -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4"></div>
    </div>
</div>

<div class="fixed-bottom-color"></div>

<!-- Below is the old code -->
<div class="container hidden">
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
