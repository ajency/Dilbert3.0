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
  weekBucket: any [] = [];
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
    this.getUserDate(1);
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
    getStartAndEndOfDate(date) {
      // if (isMonth) {
      //   let temp = new Date(date), y = temp.getFullYear(), m = temp.getMonth();
      //   let firstDay = new Date(y, m, 1);
      //   let lastDay = new Date(y, m + 1, 0);
      //   return {
      //     start : firstDay,
      //     end : lastDay
      //   };
      // }else {
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
    getUserDate(dropdownValue) {


          let dates = this.getStartAndEndOfDate(new Date());
          let date = {
            start_date: this.formatDate(dates.start),
            end_date: this.formatDate(dates.end)
          };
          this.getData(date);
          this.thisWeekDates = date;
    };
    getData(date) {
        let curr = new Date();
        let first = curr.getDate() - curr.getDay(); // First day is the day of the month - the day of the week

        let firstDay = new Date(curr.setDate(first));
        this.weekBucket = [];
        let i = 0;
        while (i !== 7) {
          let temp = new Date (firstDay);
          let nextD = new Date(temp.getFullYear(), temp.getMonth(), temp.getDate() + 1 );
          this.weekBucket.push(nextD);
          firstDay = nextD;
          i++;
        }
        console.log(this.weekBucket);
      // this.userDataService.getUserData(1, date).subscribe( (response) => {
        let response = [
          {
            name : 'Mohsin',
            data : [ {
              work_date : '2016-12-2',
              total_time : '9:00',
              start_time : '2016-12-2 9:36:35',
              end_time : '2016-12-2 6:36:35 '
            }, {
              work_date : '2016-12-1',
              total_time : '9:35',
              start_time : '2016-12-1 9:30:35',
              end_time : '2016-12-1 7:00:35 '
            }, {
              work_date : '2016-11-30',
              total_time : '9:35',
              start_time : '2016-11-30 9:30:35',
              end_time : '2016-11-30 7:00:35 '
            }, {
              work_date : '2016-11-29',
              total_time : '9:35',
              start_time : '2016-11-29 9:30:35',
              end_time : '2016-11-29 7:00:35 '
            }, {
              work_date : '2016-11-28',
              total_time : '9:35',
              start_time : '2016-11-28 9:30:35',
              end_time : '2016-11-28 7:00:35 '
            }]
          }, {
            name : 'Vaibhav',
            data : [{
              work_date : '2016-11-28',
              total_time : '9:35',
              start_time : '2016-11-28 9:30:35',
              end_time : '2016-11-28 7:00:35 '
            }, {
              work_date : '2016-12-2',
              total_time : '9:00',
              start_time : '2016-12-2 9:36:35',
              end_time : '2016-12-2 6:36:35 '
            }, {
              work_date : '2016-12-1',
              total_time : '9:35',
              start_time : '2016-12-1 9:30:35',
              end_time : '2016-12-1 7:00:35 '
            }, {
              work_date : '2016-11-30',
              total_time : '9:35',
              start_time : '2016-11-30 9:30:35',
              end_time : '2016-11-30 7:00:35 '
            }, {
              work_date : '2016-11-29',
              total_time : '9:35',
              start_time : '2016-11-29 9:30:35',
              end_time : '2016-11-29 7:00:35 '
            }, {
              work_date : '2016-11-28',
              total_time : '9:35',
              start_time : '2016-11-28 9:30:35',
              end_time : '2016-11-28 7:00:35 '
            }]
          }
        ];
        console.log(response, 'response');
      //  let dateFormat = /(^\d{1,4}[\.|\\/|-]\d{1,2}[\.|\\/|-]\d{1,4})(\s*(?:0?[1-9]:[0-5]|1(?=[012])\d:[0-5])\d\s*[ap]m)?$/;
         this.userData = response;
         this.userData.forEach( (user) => {
              let temp = [];
              for ( let i = 0; i < (6 - user.data.length); i++) {
                temp.push(i);
              }
              user.emptySpaces =  temp;
              console.log(user.emptySpaces);
            });
        
    }


}
