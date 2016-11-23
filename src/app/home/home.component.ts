import { Component, OnInit } from '@angular/core';
import { UserDataService } from '../providers/user-data.service';

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
  constructor(private userDataService: UserDataService) {
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
      this.userDataService.getUserData(3, date).subscribe( (response) => {
        console.log(response, 'response');
      //  let dateFormat = /(^\d{1,4}[\.|\\/|-]\d{1,2}[\.|\\/|-]\d{1,4})(\s*(?:0?[1-9]:[0-5]|1(?=[012])\d:[0-5])\d\s*[ap]m)?$/;
         this.userData = response;
         this.userData.forEach( (data) => {
            console.log(data.work_date , this.formatDate(new Date()));
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
              if (parseInt(temp[0], 10) >= 9) {
                this.today.timeCompleted = 100.00;
              }else {
                let hrs = parseInt(temp[0], 10);
                let mins = parseInt(temp[1], 10);
                let minInPercentage = (mins / 60) * 100;
                let hrsInPercentage = (hrs / 9) * 100;
                this.today.timeCompleted = (hrsInPercentage + (minInPercentage / 100 )).toFixed(2);

                this.d = this.describeArc(100, 130, 100, 240, (this.today.timeCompleted * 2.2 ) + 260);
                this.d2 = this.describeArc(100, 130, 100, 240, 480);
                // 250= 0% and 470 is 100%
              }
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
          console.log(data);
          if (data.total_time !== '') {
            sec += this.toSeconds(data.total_time);
          }
         });
        this.totalHoursThisWeek =
        this.fill(Math.floor(sec / 3600), 2) + ':' +
        this.fill(Math.floor(sec / 60) % 60, 2);
        console.log(this.userData, 'USERDATA', this.today, this.totalHoursThisWeek, this.yesterday, this.oldData);
     });
    }

    toSeconds(s) {
      console.log(s);
      let p = s.split(':');
      return parseInt(p[0], 10) * 3600 + parseInt(p[1], 10) * 60 ;
    }

    fill(s, digits) {
      s = s.toString();
      while (s.length < digits) { s = '0' + s; };
      return s;
    }
    polarToCartesian(centerX, centerY, radius, angleInDegrees) {
      let angleInRadians = (angleInDegrees - 90) * Math.PI / 180.0;

      return {
        x: centerX + (radius * Math.cos(angleInRadians)),
        y: centerY + (radius * Math.sin(angleInRadians))
      };
    }

  describeArc(x, y, radius, startAngle, endAngle) {

    let start = this.polarToCartesian(x, y, radius, endAngle);
    let end = this.polarToCartesian(x, y, radius, startAngle);

    let largeArcFlag = endAngle - startAngle <= 180 ? '0' : '1';

    let d = [
        'M', start.x, start.y,
        'A', radius, radius, 0, largeArcFlag, 0, end.x, end.y
    ].join(' ');

    return d;
  }
}
