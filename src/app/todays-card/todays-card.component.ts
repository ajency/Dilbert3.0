import { Component, OnInit } from '@angular/core';
import { UserDataService } from '../providers/user-data.service';
import { AppUtilService } from '../providers/app-util.service';

@Component({
  selector: 'app-todays-card',
  templateUrl: './todays-card.component.html',
  styleUrls: ['./todays-card.component.css']
})
export class TodaysCardComponent implements OnInit {
  today: any = {
    timeCovered : {
      hrs: null,
      mins: null
    }
  };
  d: any;
  d2: any;
  constructor(public userDataService: UserDataService, public appUtilService: AppUtilService ) { }

  ngOnInit() {
    let todaysDate = this.appUtilService.formatDate(new Date());
    let date = {
      start_date: todaysDate,
      end_date: todaysDate,
    };
    this.getData(date);
  }
  getData(date) {
    this.userDataService.getUserData(2, date).subscribe( (response) => {
      console.log(response);
      this.today = response[0];
      if (response[0]) {
        this.today = {
          date: new Date(),
        timeCovered : {
          hrs : response[0].total_time.split(':')[0],
          mins : response[0].total_time.split(':')[1]
        },
        start_time: response[0].start_time,
        end_time: response[0].end_time,

      };
      if ( response[0].total_time || response[0].total_time !== '' ) {
          let temp = response[0].total_time.split(':');
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
    });
  }

}
