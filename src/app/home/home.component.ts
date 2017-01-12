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
  withUrl: any;
  showLoader: boolean = true;
  yesterdaysData: any;
  totalHoursThisWeek: string;
  nextButtonDisabled: boolean;
  monthStartDay: number;
  displayDateRange: any;
  userid: any;
  empid: any;
  empname: any = null;
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
  todaysDate: any;
  constructor(private userDataService: UserDataService, public appUtilService: AppUtilService) {

  }

  ngOnInit() {
    this.dropDownValue = 2; // This is for the JSON response i.e. whether the Data exist in 1 level below or 2 level below
    // 220
    // let reg = /^\d+$/;
    let reg = /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/; // Checks if the URL has email ID
    this.todaysDate = this.appUtilService.formatDate(new Date());
    let path = window.location.href.split('/');
    let id = path[path.length - 1]; // Get the last data from the URL
    console.log('PARAMS', id, reg.test(id));
    this.empname = null;
    if (reg.test(id)) { // checks if the URL has emailID
      this.userid = this.appUtilService.user_data.id;
      this.empid = id;
      this.withUrl = true;
      this.userDataService.getOtherUserName(this.userid, this.empid).subscribe( (response) => {
        if (response.length !== 0) {
          this.empname = response[0].name;// Get Employee's Name using his/her Email-ID
        }else {
          this.empname = this.appUtilService.user_data.name; // Else replace it with User's Email ID
        }
      })
    } else {

      this.withUrl = false;
      this.userid = this.appUtilService.user_data.id;
      this.empid = undefined;//this.appUtilService.user_data.emp_email;
      this.empname = undefined;
    }
    this.getUserData(this.dropDownValue);
  }

  onDateChange(dateSlct) {
    console.log(dateSlct, 'DATE CHANGE');
    this.displayDateRange = this.appUtilService.getStartAndEndOfDate(dateSlct, this.dropDownValue === 1 ? true : false);
    console.log(this.displayDateRange, 'DATE RANGE');
  }
  getDateRangeData(dateSlct) {

    this.dateSelected = dateSlct;
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
      this.getUserData(this.dropDownValue);
    } else {
      let selectedWeek = new Date(this.dateSelected);
      if (next) {
        selectedWeek.setDate(selectedWeek.getDate() + 7 );
        this.dateSelected = new Date(selectedWeek);
      } else {

        selectedWeek.setDate(selectedWeek.getDate() - 7 );
        this.dateSelected = new Date(selectedWeek);
      }
      console.log('Week');
    }
    this.getUserData(this.dropDownValue);
  }
  getUserData(dropdownValue) {
    console.log(this.dateSelected, dropdownValue);
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
    if (new Date(date.end_date) >= new Date()) {
      this.nextButtonDisabled = true;
    }else {
      this.nextButtonDisabled = false;
    }
    this.getData(date);
    this.thisWeekDates = date;
  };
  getData(date) {
    this.showLoader = true;
    //console.log("Yeah");
    //console.log(this.appUtilService.user_data.hasOwnProperty('emp_id'));
    if(this.empid == undefined) { /*Emp's email Doesn't exist in the URL */
      this.userDataService.getUserData(this.userid, date).subscribe( (response) => {
        this.showLoader = false;
        this.userData = [];

        this.yesterdaysData = null;
        if (this.dropDownValue === 2) {
          if (response.length !== 0) {
            this.userData = response[0].data;
            this.formatWeekView(this.userData);
          }else {
            this.userData = [];
            this.oldData = [];
          }
        } else {
          if (response.length !== 0) {
            this.userData = response;
            this.formatMonthView(this.userData);
          }else {
            this.userData = [];
            this.oldData = [];
            this.formatDATA = [];
          }
        }
        console.log(response, 'response');
        let sec = 0;
        response.forEach( (week) => {
          week.data.forEach((day) => {
            if (day.total_time !== '') {
              sec += this.appUtilService.toSeconds(day.total_time);
            }
          });
        });
        this.totalHoursThisWeek =
          this.appUtilService.fill(Math.floor(sec / 3600), 2) + ':' +
          this.appUtilService.fill(Math.floor(sec / 60) % 60, 2);
        let totalSec = 0;
        totalSec += this.appUtilService.toSeconds(this.totalHoursThisWeek.toString());
        let tp = ((totalSec / this.userData.length) / 3600 ).toFixed(2);
        this.averageHours = tp.split('.')[0] + ':' + tp.split('.')[1];
        // this.dayStartDeviation = this.standardDeviation(this.userData, this.appUtilService);
      }, onerror => {
        this.userData = [];
        this.showLoader = false;
        this.oldData = [];
        this.yesterdaysData = null;
        this.totalHoursThisWeek = '00:00';
        console.log(onerror, 'ERROR');
      });
    } else { /* The URL has emp's emailID */
      this.userDataService.getOtherUserData(this.userid, this.empid, date).subscribe( (response) => {
        this.showLoader = false;
        this.userData = [];

        this.yesterdaysData = null;
        if (this.dropDownValue === 2) {
          if (response.length !== 0) {
            this.userData = response[0].data;
            this.formatWeekView(this.userData);
          }else {
            this.userData = [];
            this.oldData = [];
          }
        } else {
          if (response.length !== 0) {
            this.userData = response;
            this.formatMonthView(this.userData);
          }else {
            this.userData = [];
            this.oldData = [];
            this.formatDATA = [];
          }
        }
        console.log(response, 'response');
        let sec = 0;
        response.forEach( (week) => {
          week.data.forEach((day) => {
            if (day.total_time !== '') {
              sec += this.appUtilService.toSeconds(day.total_time);
            }
          });
        });
        this.totalHoursThisWeek =
          this.appUtilService.fill(Math.floor(sec / 3600), 2) + ':' +
          this.appUtilService.fill(Math.floor(sec / 60) % 60, 2);
        let totalSec = 0;
        totalSec += this.appUtilService.toSeconds(this.totalHoursThisWeek.toString());
        let tp = ((totalSec / this.userData.length) / 3600 ).toFixed(2);
        this.averageHours = tp.split('.')[0] + ':' + tp.split('.')[1];
        // this.dayStartDeviation = this.standardDeviation(this.userData, this.appUtilService);
      }, onerror => {
        this.userData = [];
        this.showLoader = false;
        this.oldData = [];
        this.yesterdaysData = null;
        this.totalHoursThisWeek = '00:00';
        console.log(onerror, 'ERROR');
      });
    }
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
    this.formatDATA = [];
    // userData.forEach( data => {
    //   // console.log (this.getStartAndEndOfDate(data.work_date, false));
    //   // switch (new Date(data.work_date).getDay()) {
    //   //   case 0 : formatDATA.Sunday.push(data); break;
    //   //   case 1 : formatDATA.Monday.push(data); break;
    //   //   case 2 : formatDATA.Tuesday.push(data); break;
    //   //   case 3 : formatDATA.Wednesday.push(data); break;
    //   //   case 4 : formatDATA.Thursday.push(data); break;
    //   //   case 5 : formatDATA.Friday.push(data); break;
    //   //   case 6 : formatDATA.Saturday.push(data); break;
    //   // }
    //   // data.isEmpty = false;
    //   data.day = new Date(data.work_date).getDay();
    //   this.formatDATA[this.appUtilService.getWeek(data.work_date) - getStartWeek].push(data);
    // });
    // let array = this.formatDATA.map(this.formatDATA, function(value, index) {
    //   return [value];
    // });
    this.formatDATA = userData;
    this.formatDATA.forEach( (week, key ) => {
      let sec = 0;
      week.data.forEach( (data2, index ) => {
        if (data2.total_time !== '') {
          sec += this.appUtilService.toSeconds(data2.total_time);
        }
        let t2 = null;
        if ( week.data[index + 1]) {
          t2 = new Date(week.data[index + 1].work_date).toDateString();

        }
        // let nDD = new Date(data2.work_date);
        let currD = new Date(data2.work_date);
        let trr = currD;
        let nxtD = new Date(trr.setDate( trr.getDate() + 1));
        let day = new Date(data2.work_date).getDay();
        if ( t2 !== nxtD.toDateString() && t2 !== null && nxtD.getDay() !== 0 && nxtD.getDay() !== 6) {
          week.data.push( {
            start_time: null,
            end_time: null,
            total_time: '00:00',
            work_date: nxtD.toDateString(),
            day: nxtD.getDay(),
            isEmpty: true
          });
        }
        data2.isEmpty = false;
        if (day === 0) {
          data2.day = 6;
        } else {
          data2.day = day - 1;
        }
      });
      console.log(week.data, 'WEEK');
      week.data.sort( (a, b) => {
        return new Date(a.work_date).getTime() - new Date(b.work_date).getTime();
      });
      week.data.totalHrs =
        this.appUtilService.fill(Math.floor(sec / 3600), 2) + ':' +
        this.appUtilService.fill(Math.floor(sec / 60) % 60, 2);
    });
    console.log(this.formatDATA, 'ARRAY');
  }
  formatWeekView(userData) {
      this.oldData = [];
      this.yesterdaysData = null;
      userData.forEach( (data) => {
         if ( data.total_time || data.total_time !== '' ) {
          let temp = data.total_time.split(':');
          if (parseInt(temp[0], 10) >= 10) {
            data.timeCompleted = '100';
          }else {
            let hrs = parseInt(temp[0], 10);
            let mins = parseInt(temp[1], 10);
            let minInPercentage = (mins / 60) * 100;
            let hrsInPercentage = (hrs / 10) * 100;
            data.timeCompleted = (hrsInPercentage + (minInPercentage / 100 )).toFixed(0);
          }
        }
        let nD = new Date();
        if (data.work_date === this.appUtilService.formatDate(new Date()) ) {
          this.todaysData = data;
        } else if (data.work_date === this.appUtilService.formatDate(new Date ( nD.getFullYear(), nD.getMonth(), nD.getDate() - 1 ))) {
          this.yesterdaysData = data;
        } else {
          this.oldData.push(data);
        }
      });
      if (this.yesterdaysData) {
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
      this.oldData.sort(function(a, b){
        return new Date(b.work_date).getTime() - new Date(a.work_date).getTime();
      });
      console.log(userData, 'DATA');
  }
}
