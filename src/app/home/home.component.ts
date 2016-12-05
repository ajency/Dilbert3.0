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
  thisWeekDates: any = {};
  today: any = {
    timeCovered : {
      hrs: null,
      mins: null
    }
  };
  yesterday: any;
  dropDownValue: number;
  d: any;
  d2: any;
  constructor(private userDataService: UserDataService, private appUtilService: AppUtilService) {
    this.dropDownValue = 1;
    this.getUserData(1);
    // 220
  }

  ngOnInit() {

  }
  timeConversion(milliseconds) {

        // Get hours from milliseconds
      let hours = milliseconds / (1000 * 60 * 60);
      let absoluteHours = Math.floor(hours);
      let h = absoluteHours > 9 ? absoluteHours : '0' + absoluteHours;

      // Get remainder from hours and convert to minutes
      let minutes = (hours - absoluteHours) * 60;
      let absoluteMinutes = Math.floor(minutes);
      let m = absoluteMinutes > 9 ? absoluteMinutes : '0' +  absoluteMinutes;

      // // Get remainder from minutes and convert to seconds
      // let seconds = (minutes - absoluteMinutes) * 60;
      // let absoluteSeconds = Math.floor(seconds);
      // let s = absoluteSeconds > 9 ? absoluteSeconds : '0' + absoluteSeconds;


      return h + ':' + m;
  }
  formatDate(date) {
    let temp = new Date(date);
    return temp.getFullYear() + '-' + (temp.getMonth() + 1) + '-' + temp.getDate();
  }
  getStartAndEndOfDate(date, isMonth) {
      if (isMonth) {
        let temp = new Date(date), y = temp.getFullYear(), m = temp.getMonth();
        let firstDay = new Date(y, m, 1);
        let lastDay = new Date(y, m + 1, 0);
        return {
          start : firstDay,
          end : lastDay
        };
      }else {
        let curr = new Date();
        let first = curr.getDate() - curr.getDay() + 1; // First day is the day of the month - the day of the week
        let last = first + 6; // last day is the first day + 6

        let firstDay = new Date(curr.setDate(first));
        let lastDay = new Date(curr.setDate(last));
        return {
          start : firstDay,
          end : lastDay
        };
      }
  }
  getUserData(dropdownValue) {
    console.log(dropdownValue);
    this.dropDownValue = dropdownValue;
    let dates = null;
    if (dropdownValue === 1) {
      dates = this.getStartAndEndOfDate(new Date(), true);
    }
    else{
      dates = this.getStartAndEndOfDate(new Date(), false);
    }
    let date = {
      start_date: this.formatDate(dates.start),
      end_date: this.formatDate(dates.end)
    };
    this.getData(date);
    this.thisWeekDates = date;
  };
  getData(date) {
    // this.userDataService.getUserData(1, date).subscribe( (response) => {
      let response = [];
      if (this.dropDownValue === 1) {
        response = [
          {
            work_date : '2016-12-1',
            total_time : '9:00',
            start_time : '2016-12-1 9:36:35',
            end_time : '2016-12-1 6:36:35 '
          }, {
            work_date : '2016-12-2',
            total_time : '9:00',
            start_time : '2016-12-2 9:36:35',
            end_time : '2016-12-2 6:36:35 '
          }, {
            work_date : '2016-12-3',
            total_time : '9:35',
            start_time : '2016-12-3 9:30:35',
            end_time : '2016-12-3 7:00:35 '
          }, {
            work_date : '2016-12-4',
            total_time : '9:35',
            start_time : '2016-12-4 9:30:35',
            end_time : '2016-12-4 7:00:35 '
          }, {
            work_date : '2016-12-5',
            total_time : '9:35',
            start_time : '2016-12-5 9:30:35',
            end_time : '2016-12-5 7:00:35 '
          }
        ];
      }
      else {
        response = [
          {
            work_date : '2016-12-2',
            total_time : '9:00',
            start_time : '2016-12-2 9:36:35',
            end_time : '2016-12-2 6:36:35 '
          }, {
            work_date : '2016-12-3',
            total_time : '9:35',
            start_time : '2016-12-3 9:30:35',
            end_time : '2016-12-3 7:00:35 '
          }, {
            work_date : '2016-12-4',
            total_time : '9:35',
            start_time : '2016-12-4 9:30:35',
            end_time : '2016-12-4 7:00:35 '
          }, {
            work_date : '2016-12-5',
            total_time : '9:35',
            start_time : '2016-12-5 9:30:35',
            end_time : '2016-12-5 7:00:35 '
          }
        ];
      }
      console.log(response, 'response');
    //  let dateFormat = /(^\d{1,4}[\.|\\/|-]\d{1,2}[\.|\\/|-]\d{1,4})(\s*(?:0?[1-9]:[0-5]|1(?=[012])\d:[0-5])\d\s*[ap]m)?$/;
        this.userData = response;
        this.userData.forEach( (data) => {
          if (data.work_date === this.formatDate(new Date()) ) {
            this.todaysData = data;
          }
          else if (data.work_date === this.formatDate(new Date ( new Date().getFullYear(), new Date().getMonth(), new Date().getDate() - 1 ))) {
            this.yesterdaysData = data;
          }else {
            this.oldData.push(data);
          }
        });
        if (this.todaysData) {
          this.today = {
            date: new Date(),
          timeCovered : {
            hrs : this.todaysData.total_time.split(':')[0],
            mins : this.todaysData.total_time.split(':')[1]
          },
          start_time: this.todaysData.start_time,
          end_time: this.todaysData.end_time,

        };
        if ( this.todaysData.total_time || this.todaysData.total_time !== '' ) {
            let temp = this.todaysData.total_time.split(':');
            if (parseInt(temp[0], 10) >= 10) {
              this.today.timeCompleted = 100.00;
              this.d = this.appUtilService.describeArc(100, 130, 100, 240, (this.today.timeCompleted * 2.4 ) + 240);
            }else {
              let hrs = parseInt(temp[0], 10);
              let mins = parseInt(temp[1], 10);
              let minInPercentage = (mins / 60) * 100;
              let hrsInPercentage = (hrs / 10) * 100;
              this.today.timeCompleted = (hrsInPercentage + (minInPercentage / 100 )).toFixed(2);

              this.d = this.appUtilService.describeArc(100, 130, 100, 240, (this.today.timeCompleted * 2.4 ) + 240);
              // 240= 0% and 480 is 100%
            }
            this.d2 = this.appUtilService.describeArc(100, 130, 100, 240, 480);
          }
        }else {
          this.today = {
            date: new Date(),
            timeCovered : {
            hrs : 0,
            mins : 0
          },
          start_time: 0,
          end_time: 0,
          };
        }
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
        return  new Date(b.work_date).getTime() - new Date(a.work_date).getTime(); });
      console.log(this.userData, 'USERDATA', this.today, this.totalHoursThisWeek, this.yesterday, this.oldData);
  //  }, (onerror) => {
  //    console.log(onerror);
  //  });
  }


}
