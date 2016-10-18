@extends('layouts.app')

@section('content')
<div class="container">
    {{-- <div class="row">
        <div class="col-lg-offset-3 col-lg-6 col-md-offset-3 col-md-6">
            <div class="alert alert-success alert-dismissible alert-fixed" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <center><strong>@lang('lang.welcome') </strong> {{ Auth::user()->name }} !</center>
            </div>
        </div>
    </div> --}}
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
        <div class="col-md-12">
        
            <div class="user-page-title">
                <h1 class="dashboard-title">Ajency.in Work Summary</h1>
                <h4 class="sub-title dash">Track Time. Record Work. Boost Productivity.</h4>
            </div>

            <div class="company-summary card">
                <div class="card-header">
                    <div class="pull-left">
                        <label for="search-table" class="search-emp-names">
                            <input id="search-table" type="search" class=" form-control" placeholder="Filter by Name">
                            <i class="fa fa-search empty-icon"></i>
                            <!-- show this icon only if text has been entered - so the text can be cleared as well as the filtered table can be cleared -->
                            <a href="#" class="clear-icon hidden">&times;</a>
                        </label>
                    </div>
                    <div class="table-navi pull-right">
                        <!-- add class disabled to disable the btn/link -->
                        <a href="#" class="week-navi prev"><i class="fa fa-angle-left"></i></a>
                        <a href="#" class="week-navi next disabled"><i class="fa fa-angle-right"></i></a>
                    </div>
                    <div class="btn-group btn-dropdown filter pull-right">
                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Jump to Date <span class="fa fa-angle-down"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li class="current active"><a href="#">This Month</a></li>
                            <li><a href="#">This Week</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                        </ul>
                    </div>
                    <!-- <a href="#" class="show-hide-days pull-right"><small>Hide Sundays</small></a> -->
                    <div class="clearfix"></div>
                </div>
                <div class="card-body">
                    <div class="table-view month hscroll-table hide-in-week-view">
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
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day current" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td class="sunday">
                                            <!-- <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a> -->
                                        </td>
                                        <td>
                                            <a href="#" class="single-day total current" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day visi-hide">Week's Total</span>
                                                <span class="th-total">44:10</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">39:13</span> -
                                                    <span class="th-break">6:13</span>
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
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td class="sunday">
                                            <!-- <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a> -->
                                        </td>
                                        <td>
                                            <a href="#" class="single-day total" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day visi-hide">Week's Total</span>
                                                <span class="th-total">44:10</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">39:13</span> -
                                                    <span class="th-break">6:13</span>
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
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td class="sunday">
                                            <!-- <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day small-title">Oct 10</span>
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a> -->
                                        </td>
                                        <td>
                                            <a href="#" class="single-day total" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <span class="th-day visi-hide">Week's Total</span>
                                                <span class="th-total">44:10</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">39:13</span> -
                                                    <span class="th-break">6:13</span>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="table-view week show-in-week-view">
                        <div class="table-responsive">
                            <table class="table first-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th class="eq"><span class="th-label small-title">Total</span></th>
                                        <!-- <th class="eq"><span class="th-label small-title">Work</span></th>
                                        <th class="eq last"><span class="th-label small-title">Break</span></th> -->
                                        <th class="wide">
                                            <span class="th-label small-title pull-left">Day Start</span>
                                            <span class="th-label small-title pull-right">Day End</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="current">
                                        <td>
                                            <span class="th-day">
                                                <span class="small-title"><big>Oct 14</big></span>
                                                <span class="small-title">Fri</span>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="th-total total th-time">
                                                <strong>9:30</strong>
                                            </span>
                                        </td>
                                        <!-- <td>
                                            <span class="th-total work th-time">
                                                <strong>8:38</strong>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="th-total break th-time">
                                                <strong>0:52</strong>
                                            </span>
                                        </td> -->
                                        <td>
                                            <span class="top-meta">
                                                <span class="th-day-end pull-right">
                                                    <small>8:13 PM</small>
                                                </span>
                                                <span class="th-day-start">
                                                    <small>9:43 AM</small>
                                                </span>
                                            </span>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                                                    <span class="sr-only">60% Complete</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="th-day">
                                                <span class="small-title"><big>Oct 14</big></span>
                                                <span class="small-title">Fri</span>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="th-total total th-time">
                                                <strong>9:30</strong>
                                            </span>
                                        </td>
                                        <!-- <td>
                                            <span class="th-total work th-time">
                                                <strong>8:38</strong>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="th-total break th-time">
                                                <strong>0:52</strong>
                                            </span>
                                        </td> -->
                                        <td>
                                            <span class="top-meta">
                                                <span class="th-day-start">
                                                    <small>9:43 AM</small>
                                                </span>
                                                <span class="th-day-end pull-right">
                                                    <small>8:13 PM</small>
                                                </span>
                                            </span>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                                                    <span class="sr-only">60% Complete</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="th-day">
                                                <span class="small-title"><big>Oct 14</big></span>
                                                <span class="small-title">Fri</span>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="th-total total th-time">
                                                <strong>9:30</strong>
                                            </span>
                                        </td>
                                        <!-- <td>
                                            <span class="th-total work th-time">
                                                <strong>8:38</strong>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="th-total break th-time">
                                                <strong>0:52</strong>
                                            </span>
                                        </td> -->
                                        <td>
                                            <span class="top-meta">
                                                <span class="th-day-start">
                                                    <small>9:43 AM</small>
                                                </span>
                                                <span class="th-day-end pull-right">
                                                    <small>8:13 PM</small>
                                                </span>
                                            </span>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                                                    <span class="sr-only">60% Complete</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="stats-view">
                        <h4 class="card-title hide-in-week-view">Stats since Oct 01</h4>
                        <div class="spacer-20 show-in-week-view"></div>
                        <div class="horizontal-stats">
                            <div class="stat-wrap">
                                <div class="stat-count">
                                    <strong>109:20</strong>
                                    <span class="stat-dev increase"><i class="fa fa-caret-up"></i> 1.5%</span>
                                </div>
                                <div class="stat-title small-title">Total Time</div>
                            </div>
                            <!-- <div class="stat-wrap">
                                <div class="stat-count">
                                    <strong>95:49</strong>
                                    <span class="stat-dev decrease"><i class="fa fa-caret-down"></i> 1.5%</span>
                                </div>
                                <div class="stat-title small-title">Total Work Time</div>
                            </div> -->
                            <div class="stat-wrap">
                                <div class="stat-count">
                                    <strong>09:52 <span>AM</span></strong>
                                    <span class="stat-dev increase"><i class="fa fa-caret-up"></i> 1.5%</span>
                                </div>
                                <div class="stat-title small-title">Avg. Say Start</div>
                            </div>
                            <div class="stat-wrap wide">
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
