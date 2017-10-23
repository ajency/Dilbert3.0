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
                    <!-- I'm not sure if this is required so I've added the class hidden for now, remove hidden if this elelment is needed -->
                    <div class="btn-group btn-dropdown filter pull-right hidden">
                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Jump to Date <span class="fa fa-angle-down"></span>
                        </button>
                        <ul class="dropdown-menu stay-open">
                            <li class="select-a-date">
                                <h6 class="dd-title">Select a Date</h6>
                                <p>The result will show the week which includes the date you picked</p>
                                <div class="form-group">
                                    <label for="find-week-day"><input type="date" id="find-week-day" class="form-control"></label>
                                </div>
                                <!-- add class hidden as soon as a date has been selected -->
                                <p class="date-not-selected">Select a date to see week start and end dates</p>

                                <!-- After a date is picked, show this div and hi de the previous one-->
                                <div class="hidden">
                                    <h6 class="dd-title">The Week</h6>
                                    <p>10 Oct, 2016 - 16 Oct, 2016</p>
                                    <a href="#" class="btn btn-primary pull-right">Done</a>
                                    <div class="clearfix"></div>
                                </div>
                            </li>
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
                                        <th class="employee-id">
                                            <span class="th-label small-title">
                                                <!-- ************************************
                                                    I N S T R U C T I O N S
                                                    change class of <i> to
                                                    "fa fa-caret-up" - ascending order
                                                    "fa fa-caret-down" - descending order
                                                    This is same for all table headers
                                                    ************************************* -->
                                                <span class="th-date">Employees <a href="#" class="sort-col"><i class="fa fa-sort"></i></a></span>
                                            </span>
                                        </th>
                                        <th>
                                            <span class="th-label small-title">
                                                <span class="th-date">Oct 10 <a href="#" class="sort-col"><i class="fa fa-sort"></i></a></span>
                                                <span class="th-day">Mon</span>
                                            </span>
                                        </th>
                                        <th>
                                            <span class="th-label small-title">
                                                <span class="th-date">Oct 11 <a href="#" class="sort-col"><i class="fa fa-sort"></i></a></span>
                                                <span class="th-day">Tue</span>
                                            </span>
                                        </th>
                                        <th>
                                            <span class="th-label small-title">
                                                <span class="th-date">Oct 12 <a href="#" class="sort-col"><i class="fa fa-sort"></i></a></span>
                                                <span class="th-day">Wed</span>
                                            </span>
                                        </th>
                                        <th>
                                            <span class="th-label small-title">
                                                <span class="th-date">Oct 13 <a href="#" class="sort-col"><i class="fa fa-sort"></i></a></span>
                                                <span class="th-day">Thu</span>
                                            </span>
                                        </th>
                                        <th>
                                            <span class="th-label small-title">
                                                <span class="th-date">Oct 14 <a href="#" class="sort-col"><i class="fa fa-sort"></i></a></span>
                                                <span class="th-day">Fri</span>
                                            </span>
                                        </th>
                                        <th>
                                            <span class="th-label small-title">
                                                <span class="th-date">Oct 15 <a href="#" class="sort-col"><i class="fa fa-sort"></i></a></span>
                                                <span class="th-day">Sat</span>
                                            </span>
                                        </th>
                                        <th>
                                            <span class="th-label small-title">
                                                <span class="th-date">Oct 16 <a href="#" class="sort-col"><i class="fa fa-sort"></i></a></span>
                                                <span class="th-day">Sun</span>
                                            </span>
                                        </th>
                                        <th>
                                            <span class="th-label small-title">
                                                <span class="th-date">Total <a href="#" class="sort-col"><i class="fa fa-sort"></i></a></span>
                                                <span class="th-day">&nbsp;</span>
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="">
                                        <td class="employee-id">
                                            <a href="#" class="employee-name" data-toggle="tooltip" data-html="true" data-placement="bottom" title="More details of this employee">
                                                <!-- <span class="th-day small-title">&nbsp;</span> -->
                                                <span class="th-total">Barbara Gordon</span>
                                                <!-- <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span> -->
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <!-- <span class="th-day small-title">Oct 10</span> -->
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <!-- <span class="th-day small-title">Oct 10</span> -->
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <!-- <span class="th-day small-title">Oct 10</span> -->
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <!-- <span class="th-day small-title">Oct 10</span> -->
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <!-- <span class="th-day small-title">Oct 10</span> -->
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day current" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <!-- <span class="th-day small-title">Oct 10</span> -->
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
                                                <!-- <span class="th-day visi-hide">Week's Total</span> -->
                                                <span class="th-total">44:10</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">39:13</span> -
                                                    <span class="th-break">6:13</span>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td class="employee-id">
                                            <a href="#" class="employee-name" data-toggle="tooltip" data-html="true" data-placement="bottom" title="More details of this employee">
                                                <!-- <span class="th-day small-title">&nbsp;</span> -->
                                                <span class="th-total">Barbara Gordon</span>
                                                <!-- <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span> -->
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <!-- <span class="th-day small-title">Oct 10</span> -->
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <!-- <span class="th-day small-title">Oct 10</span> -->
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <!-- <span class="th-day small-title">Oct 10</span> -->
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <!-- <span class="th-day small-title">Oct 10</span> -->
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <!-- <span class="th-day small-title">Oct 10</span> -->
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day current" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <!-- <span class="th-day small-title">Oct 10</span> -->
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
                                                <!-- <span class="th-day visi-hide">Week's Total</span> -->
                                                <span class="th-total">44:10</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">39:13</span> -
                                                    <span class="th-break">6:13</span>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td class="employee-id">
                                            <a href="#" class="employee-name" data-toggle="tooltip" data-html="true" data-placement="bottom" title="More details of this employee">
                                                <!-- <span class="th-day small-title">&nbsp;</span> -->
                                                <span class="th-total">Barbara Gordon</span>
                                                <!-- <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span> -->
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <!-- <span class="th-day small-title">Oct 10</span> -->
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <!-- <span class="th-day small-title">Oct 10</span> -->
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <!-- <span class="th-day small-title">Oct 10</span> -->
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <!-- <span class="th-day small-title">Oct 10</span> -->
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <!-- <span class="th-day small-title">Oct 10</span> -->
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day current" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <!-- <span class="th-day small-title">Oct 10</span> -->
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
                                                <!-- <span class="th-day visi-hide">Week's Total</span> -->
                                                <span class="th-total">44:10</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">39:13</span> -
                                                    <span class="th-break">6:13</span>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td class="employee-id">
                                            <a href="#" class="employee-name" data-toggle="tooltip" data-html="true" data-placement="bottom" title="More details of this employee">
                                                <!-- <span class="th-day small-title">&nbsp;</span> -->
                                                <span class="th-total">Barbara Gordon</span>
                                                <!-- <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span> -->
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <!-- <span class="th-day small-title">Oct 10</span> -->
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <!-- <span class="th-day small-title">Oct 10</span> -->
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <!-- <span class="th-day small-title">Oct 10</span> -->
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <!-- <span class="th-day small-title">Oct 10</span> -->
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <!-- <span class="th-day small-title">Oct 10</span> -->
                                                <span class="th-total">9:30</span>
                                                <span class="th-spilt">
                                                    <span class="th-work">9:45</span> -
                                                    <span class="th-break">19:15</span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="single-day current" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Tooltip">
                                                <!-- <span class="th-day small-title">Oct 10</span> -->
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
                                                <!-- <span class="th-day visi-hide">Week's Total</span> -->
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

                    <div class="stats-view hidden">
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

@endsection
