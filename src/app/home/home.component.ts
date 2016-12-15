import { Component, OnInit } from '@angular/core';
import { UserDataService } from '../providers/user-data.service';
import { AppUtilService } from '../providers/app-util.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {
  userData: any[] = [];
  todaysData: any;
  oldData: any[] = [];
  yesterdaysData: any;
  totalHoursThisWeek: string;
  monthStartDay: number;
  formatDATA: any;
  thisWeekDates: any = {};
  today: any = {
    timeCovered : {
      hrs: null,
      mins: null
    }
  };
  dateSelected: Date = new Date();
  yesterday: any;
  dropDownValue: number;
  d: any;
  d2: any;
  averageHours: any;
  dayStartDeviation: any;
  constructor(private userDataService: UserDataService, public appUtilService: AppUtilService) {
    this.dropDownValue = 2;
    this.getUserData(2);
    // 220
  }

  ngOnInit() {

  }

  onDateChange() {
    this.getUserData(this.dropDownValue);
  }
  fetchData(next) {
    console.log(this.dateSelected, next, this.dropDownValue);
    if (this.dropDownValue === 1) {
      let selectedMonth = new Date(this.dateSelected);
      if (next) {
        this.dateSelected = new Date(selectedMonth.getFullYear(), selectedMonth.getMonth() + 1, selectedMonth.getDate());
      } else {
        this.dateSelected = new Date(selectedMonth.getFullYear(), selectedMonth.getMonth() - 1, selectedMonth.getDate());
      }
      console.log('Month');
    } else {
      let selectedWeek = new Date(this.dateSelected);
      if (next) {
        this.dateSelected = new Date(selectedWeek.getFullYear(), selectedWeek.getMonth(), selectedWeek.getDate() - 7);
      } else {
        this.dateSelected = new Date(selectedWeek.getFullYear(), selectedWeek.getMonth(), selectedWeek.getDate() - 7);
      }
      console.log('Month');
      console.log('Week');
    }
    this.getUserData(this.dropDownValue);
  }
  getUserData(dropdownValue) {
    console.log(this.dateSelected);
    this.dropDownValue = dropdownValue;
    let dates = null;
    if (dropdownValue === 1) {
      dates = this.appUtilService.getStartAndEndOfDate(this.dateSelected, true);
    } else {
      dates = this.appUtilService.getStartAndEndOfDate(this.dateSelected, false);
    }
    let date = {
      start_date: this.appUtilService.formatDate(dates.start),
      end_date: this.appUtilService.formatDate(dates.end)
    };
    this.getData(date);
    this.thisWeekDates = date;
  };
  getData(date) {
    this.userDataService.getUserData(2, date).subscribe( (response) => {
      // if (this.dropDownValue === 1) {
      //   response = [
      //     {
      //       work_date : '2016-12-14',
      //       total_time : '9:00',
      //       start_time : '2016-12-14 9:36:35',
      //       end_time : '2016-12-14 6:36:35 '
      //     },
      //     {
      //       work_date : '2016-12-1',
      //       total_time : '9:00',
      //       start_time : '2016-12-1 9:36:35',
      //       end_time : '2016-12-1 6:36:35 '
      //     }, {
      //       work_date : '2016-12-2',
      //       total_time : '9:00',
      //       start_time : '2016-12-2 9:36:35',
      //       end_time : '2016-12-2 6:36:35 '
      //     }, {
      //       work_date : '2016-12-3',
      //       total_time : '9:35',
      //       start_time : '2016-12-3 9:30:35',
      //       end_time : '2016-12-3 7:00:35 '
      //     }, {
      //       work_date : '2016-12-5',
      //       total_time : '9:35',
      //       start_time : '2016-12-5 9:30:35',
      //       end_time : '2016-12-5 7:00:35 '
      //     }, {
      //       work_date : '2016-12-6',
      //       total_time : '9:35',
      //       start_time : '2016-12-6 9:30:35',
      //       end_time : '2016-12-6 7:00:35 '
      //     }, {
      //       work_date : '2016-12-7',
      //       total_time : '9:35',
      //       start_time : '2016-12-7 9:30:35',
      //       end_time : '2016-12-7 7:00:35 '
      //     }
      //   ];
      // }
      // else {
      //   response = [
      //     {
      //       work_date : '2016-12-2',
      //       total_time : '9:30',
      //       start_time : '2016-12-2 9:36:35',
      //       end_time : '2016-12-2 6:36:35 '
      //     }, {
      //       work_date : '2016-12-3',
      //       total_time : '9:35',
      //       start_time : '2016-12-3 9:30:35',
      //       end_time : '2016-12-3 7:00:35 '
      //     }, {
      //       work_date : '2016-12-5',
      //       total_time : '10:35',
      //       start_time : '2016-12-5 9:30:35',
      //       end_time : '2016-12-5 7:00:35 '
      //     }, {
      //       work_date : '2016-12-6',
      //       total_time : '9:35',
      //       start_time : '2016-12-6 9:30:35',
      //       end_time : '2016-12-6 7:00:35 '
      //     }
      //   ];
      // }
      console.log(response, 'response');
    //  let dateFormat = /(^\d{1,4}[\.|\\/|-]\d{1,2}[\.|\\/|-]\d{1,4})(\s*(?:0?[1-9]:[0-5]|1(?=[012])\d:[0-5])\d\s*[ap]m)?$/;
      this.userData = response;
      this.userData.forEach( (data) => {
        let nD = new Date();
        if (data.work_date === this.appUtilService.formatDate(new Date()) ) {
          this.todaysData = data;
        }
        else if (data.work_date === this.appUtilService.formatDate(new Date ( nD.getFullYear(), nD.getMonth(), nD.getDate() - 1 ))) {
          this.yesterdaysData = data;
        }else {
          this.oldData.push(data);
        }
      });
      if (this.yesterdaysData) {
        console.log(this.yesterdaysData, 'YESTERDAY');
        this.yesterday = {
        date: this.yesterdaysData.work_date,
        total_time : {
          hrs: this.yesterdaysData.total_time.split(':')[0],
          mins: this.yesterdaysData.total_time.split(':')[1]
        },
        start_time: this.yesterdaysData.start_time,
        end_time: this.yesterdaysData.end_time

      };
      }else {
        this.yesterday = {
          date: new Date ( new Date().getFullYear(), new Date().getMonth(), new Date().getDate() - 1 ),
          total_time : '00:00'
        };
      }
      let sec = 0;
      this.userData.forEach( (data) => {
        if (data.total_time !== '') {
          sec += this.appUtilService.toSeconds(data.total_time);
        }
      });
      this.totalHoursThisWeek =
        this.appUtilService.fill(Math.floor(sec / 3600), 2) + ':' +
        this.appUtilService.fill(Math.floor(sec / 60) % 60, 2);
      this.oldData.sort(function(a, b){
        return new Date(b.work_date).getTime() - new Date(a.work_date).getTime();
      });
      let totalSec = 0;
      totalSec += this.appUtilService.toSeconds(this.totalHoursThisWeek.toString());
      let tp = ((totalSec / this.userData.length) / 3600 ).toFixed(2);
      this.averageHours = tp.split('.')[0] + ':' + tp.split('.')[1];
      // this.dayStartDeviation = this.standardDeviation(this.userData, this.appUtilService);
      if (this.dropDownValue === 1) {
        this.formatMonthView(this.userData);
      }
    });
  }
  standardDeviation(values, appUtilService) {
    let avg = this.averageHours.toString();
    let squareDiffs = values.map(function(value){
      let s = new Date (value.start_time).getHours() + ':' + new Date (value.start_time).getMinutes();
      let val1 = appUtilService.toSeconds(s.toString());
      let val2 = appUtilService.toSeconds(avg.split('.')[0] + ':' + avg.split('.')[1]);
      let diff =  val1 - val2;
      let sqrDiff = diff * diff;
      return sqrDiff;
    });
    let avgSquareDiff = this.average2(squareDiffs);

    let stdDev = Math.sqrt(avgSquareDiff  * 0.0166667);
    console.log(stdDev);
    return stdDev;
  }
  average2(data) {
   let sum = data.reduce(function(sum1, value){
      return sum1 + value;
    }, 0);

    let avg = sum / data.length;
    return avg;
  }
  formatMonthView(userData) {
    let month_start = this.appUtilService.getStartAndEndOfDate(userData[0].work_date, true).start;
    let month_end = this.appUtilService.getStartAndEndOfDate(userData[0].work_date, true).end;
    let used = month_start.getDay() + month_end.getDate();
    let getStartWeek = this.appUtilService.getWeek(month_start);
    let weekCount =  Math.ceil( used / 7);
    console.log(new Date(month_start).getDay(), weekCount, getStartWeek);
    this.monthStartDay = new Date(month_start).getDay();
    console.log();
    this.formatDATA = [];
    userData.forEach( data => {
      this.formatDATA[this.appUtilService.getWeek(data.work_date) - getStartWeek] = [];
    });
    // for (let i = 0; i < 7 - new Date(month_start).getDay(); i++) {
    //   this.formatDATA['0'].push({
    //     isEmpty : true
    //   });
    // }
    userData.forEach( data => {
      // console.log (this.getStartAndEndOfDate(data.work_date, false));
      // switch (new Date(data.work_date).getDay()) {
      //   case 0 : formatDATA.Sunday.push(data); break;
      //   case 1 : formatDATA.Monday.push(data); break;
      //   case 2 : formatDATA.Tuesday.push(data); break;
      //   case 3 : formatDATA.Wednesday.push(data); break;
      //   case 4 : formatDATA.Thursday.push(data); break;
      //   case 5 : formatDATA.Friday.push(data); break;
      //   case 6 : formatDATA.Saturday.push(data); break;
      // }
      // data.isEmpty = false;
      data.day = new Date(data.work_date).getDay();
      this.formatDATA[this.appUtilService.getWeek(data.work_date) - getStartWeek].push(data);
    });
    // let array = this.formatDATA.map(this.formatDATA, function(value, index) {
    //   return [value];
    // });
    this.formatDATA.forEach( (data, key ) => {
      console.log(data, key);
      let sec = 0;
      data.forEach( data2 => {
        if (data2.total_time !== '') {
          sec += this.appUtilService.toSeconds(data2.total_time);
        }
      });
      data.totalHrs =
        this.appUtilService.fill(Math.floor(sec / 3600), 2) + ':' +
        this.appUtilService.fill(Math.floor(sec / 60) % 60, 2);
    });

    console.log(this.formatDATA, 'ARRAY');
  }
}
