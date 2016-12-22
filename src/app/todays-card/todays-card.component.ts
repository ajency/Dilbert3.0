import { Component, OnInit, Input } from '@angular/core';
import { UserDataService } from '../providers/user-data.service';
import { AppUtilService } from '../providers/app-util.service';

@Component({
  selector: 'app-todays-card',
  templateUrl: './todays-card.component.html',
  styleUrls: ['./todays-card.component.css']
})
export class TodaysCardComponent implements OnInit {
  @Input() userid;
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
    console.log(this.userid);
    let todaysDate = this.appUtilService.formatDate(new Date());
    let date = {
      start_date: todaysDate,
      end_date: todaysDate,
    };
    this.getData(date);
    // window.setInterval(() => {
    //   this.getData(date);

    // }, 60000);
  }
  getData(date) {
    this.userDataService.getUserData(this.userid, date).subscribe( (response) => {
      console.log(response);

      this.d2 = this.appUtilService.describeArc(100, 130, 100, 240, 480);
      let t = response;
      if (response.length !== 0) {
        t = response[0].data[0];
        this.today = {
          date: new Date(),
        timeCovered : {
          hrs : t.total_time.split(':')[0],
          mins : t.total_time.split(':')[1]
        },
        start_time: t.start_time,
        end_time: t.end_time,

      };

      if ( t.total_time || t.total_time !== '' ) {
          let temp = t.total_time.split(':');
          if (parseInt(temp[0], 10) >= 10) {
            this.today.timeCompleted = 100.00;
            this.d = this.appUtilService.describeArc(100, 130, 100, 240, (this.today.timeCompleted * 2.4 ) + 240);
          }else {
            let hrs = parseInt(temp[0], 10);
            let mins = parseInt(temp[1], 10);
            let minInPercentage = (mins / 60);
            let hrsInPercentage = (hrs / 10) * 100;
            this.today.timeCompleted = (hrsInPercentage +  (10 * (minInPercentage ))).toFixed(2);
            console.log(this.today.timeCompleted);
            this.d = this.appUtilService.describeArc(100, 130, 100, 240, (this.today.timeCompleted * 2.4 ) + 240);
            // 240= 0% and 480 is 100%
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
    });
  }

}
