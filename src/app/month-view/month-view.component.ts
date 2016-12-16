import { Component, OnInit, Input } from '@angular/core';
// import { AppUtilService } from '../providers/app-util.service';

@Component({
  selector: 'app-month-view',
  templateUrl: './month-view.component.html',
  styleUrls: ['./month-view.component.css']
})
export class MonthViewComponent implements OnInit {
  // @Input() userData: any[];
  monthStartDay: any;
  @Input() monthData: any[] = [];
  constructor() {

  }

  ngOnInit() {
    console.log(this.monthData);

    // let month_start = this.appUtilService.getStartAndEndOfDate(this.monthData[0].data[0].work_date, true).start;
    // let month_end = this.appUtilService.getStartAndEndOfDate(userData[0].data[0].work_date, true).end;
    // let used = month_start.getDay() + month_end.getDate();
    // let getStartWeek = this.appUtilService.getWeek(month_start);
    // let weekCount =  Math.ceil( used / 7);
    // this.monthStartDay = new Date(month_start).getDay();
  }
}
