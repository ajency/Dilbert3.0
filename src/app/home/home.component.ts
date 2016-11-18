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
  today: any = {
    timeCovered : {
      hrs: null,
      mins: null
    }
  };
  dropDownValue: number;
  constructor(private userDataService: UserDataService) {
    this.dropDownValue = 2;
    this.getUserDate(2);
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
        let first = curr.getDate() - curr.getDay(); // First day is the day of the month - the day of the week
        let last = first + 6; // last day is the first day + 6

        let firstDay = new Date(curr.setDate(first));
        let lastDay = new Date(curr.setDate(last));
        return {
          start : firstDay,
          end : lastDay
        };
      }
    }
    getUserDate(dropdownValue) {

      this.dropDownValue = dropdownValue;
      if (dropdownValue === 1) {
        let dates = this.getStartAndEndOfDate(new Date(), true);
        let date = {
          start_date: this.formatDate(dates.start),
          end_date: this.formatDate(dates.end)
        };
        this.getData(date);
        console.log('Get Month Data');
      }else if (dropdownValue === 2) {
          let dates = this.getStartAndEndOfDate(new Date(), false);
          let date = {
            start_date: this.formatDate(dates.start),
            end_date: this.formatDate(dates.end)
          };
          this.getData(date);
        console.log('Get Weekly Data');
      }else {
        console.log('ERROR');
      }
    };
    getData(date) {
      this.userDataService.getUserData(1, date).subscribe( (response) => {
      //  let dateFormat = /(^\d{1,4}[\.|\\/|-]\d{1,2}[\.|\\/|-]\d{1,4})(\s*(?:0?[1-9]:[0-5]|1(?=[012])\d:[0-5])\d\s*[ap]m)?$/;
       if (response.status !== 'Error') {
         this.userData = response;
         this.userData.forEach( (data) => {
            if (data.work_date === this.formatDate(new Date()) ) {
              this.todaysData = data;
            }
            if ( data.total_time) {
              //  let temp2 = new Date(new Date(temp).setHours(new Date(temp).getHours() + 9));
              // console.log(data, temp, temp2);
              // let timeRemaining = temp2.getTime() - new Date ().getTime();
              // console.log(Math.abs(timeRemaining));
              // let percentage = 100 - ((timeRemaining / 32400000) * 100) ;
              // data.timeCompleted = percentage.toFixed(0);
              // data.timeRemaining = this.timeConversion(Math.abs(timeRemaining));
              let temp = data.total_time.split(':');
              if (parseInt(temp[0], 10) >= 9) {
                data.timeCompleted = 100.00;
              }else {
                let hrs = parseInt(temp[0], 10);
                let mins = parseInt(temp[1], 10);
                let minInPercentage = (mins / 60) * 100;
                let hrsInPercentage = (hrs / 9) * 100;
                data.timeCompleted = (hrsInPercentage + (minInPercentage / 100 )).toFixed(2);
              }
            }
         });
         this.today = {
           timeCovered : {
             hrs : this.todaysData.total_time.split(':')[0],
              mins : this.todaysData.total_time.split(':')[1]
            },
            start_time: this.todaysData.start_time,
            end_time: this.todaysData.end_time,

          };
         console.log(this.userData, 'USERDATA', this.today );
       }
     });
    }
}
