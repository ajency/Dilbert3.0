import { Component, OnInit } from '@angular/core';
import { UserDataService } from '../providers/user-data.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {
  userData: any[] = [];
  constructor(private userDataService: UserDataService) {
     this.userDataService.getUserData(1, null).subscribe( (response) => {
       if (response.status !== 'Error') {
         this.userData = response;
         this.userData.forEach( (data) => {
            let temp = new Date(new Date().setHours(new Date().getHours() - 4));
            console.log(data, temp );
            let timeRemaining = temp.getTime() - new Date ().getTime();
            console.log(Math.abs(timeRemaining));
            // data.timeRemaining = Math.ceil((timeRemaining / (1000 * 60 * 60)) % 24) + ':'
            //   + Math.abs((timeRemaining / (1000 * 60)) % 60);
            data.timeRemaining = this.timeConversion(Math.abs(timeRemaining));
         });
         console.log(this.userData, 'USERDATA');
       }
     });
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

}
