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
  saveData: any [ ]= [];
  sorting: any = {
    name: {
      isAsc: false
    },
    date: {
      date: false,
      isAsc: false
    },
    total: {
      isAsc: false
    }

  };
  yesterday: any;
  dropDownValue: number;
  d: any;
  d2: any;
  todaysDate: any;
  userid: any;
  dateSelected: Date = new Date(); // Set initially to Today's date
  nxtBtnDisable: boolean;

  constructor(private userDataService: UserDataService, private appUtilService: AppUtilService) {
    this.dropDownValue = 1;
    this.todaysDate = this.appUtilService.formatDate(new Date());
    this.userid = this.appUtilService.user_data.id;
    console.log(this.appUtilService.user_data);
    this.getUserDate(1, new Date());
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
  onDateChange(date) {
    if (date) {
      this.dateSelected = new Date(date);
      this.getData(date);
    }
    console.log(date);
  }
  onTextChange(text) {
    console.log(text, this.saveData);
    this.userData = this.saveData.filter(item => item.name.toLowerCase().indexOf(text.toLowerCase()) !== -1); // LowerCase all the names & keyword so that it cover all the possibilities
  }
  getUserDate(dropdownValue, dateswnt) {

        let dates = this.appUtilService.getStartAndEndOfDate(new Date());
        let date = {
          start_date: this.formatDate(dates.start),
          end_date: this.formatDate(dates.end)
        };
        this.getData(date);
        this.thisWeekDates = date;
  };
  getData(date) {
    console.log(date);
    let curr;
    if(date.hasOwnProperty('start_date'))
      curr = new Date(date.start_date);
    else
      curr = new Date(date);
    
    console.log("Calendar's Date " + this.dateSelected);

    let first = curr.getDate() - curr.getDay(); // First day is the day of the month - the day of the week

    let firstDay = new Date(curr.setDate(first));
    this.weekBucket = [];
    let i = 0;

    while (i !== 7) {
      console.log(i);
      let temp = new Date (firstDay);
      let nextD = new Date(temp.getFullYear(), temp.getMonth(), temp.getDate() + 1 );// Get Next Date
      this.weekBucket.push(nextD);
      firstDay = nextD;
      i++;
    }
    if(!date.hasOwnProperty('start_date')) {
      date = {
        start_date: this.formatDate(this.weekBucket[0]),
        end_date: this.formatDate(this.weekBucket[this.weekBucket.length - 2])
      };
    }

    if (new Date(date.end_date) >= new Date()) {
      this.nxtBtnDisable = true;
    }else {
      this.nxtBtnDisable = false;
    }
  

    console.log(date);
    console.log(this.userid);
    this.userDataService.getAllUsersData(this.userid, date).subscribe( (response) => {
      console.log(response, 'response');
      //  let dateFormat = /(^\d{1,4}[\.|\\/|-]\d{1,2}[\.|\\/|-]\d{1,4})(\s*(?:0?[1-9]:[0-5]|1(?=[012])\d:[0-5])\d\s*[ap]m)?$/;
      this.userData = response;
      this.saveData = response;
      this.userData.forEach( (user) => {
        if(user.summary.length !== 0 && user.summary.data.length != 0) { // Checks if summary has Length greater than 0, if so the week's data is present
          user.total_time = "00:00";
          for ( i = 0; i < user.summary.data.length; i++) {
            if((user.summary.data[i].status == null || user.summary.data[i].status.toLowerCase() == 'present') && user.summary.data[i].total_time != null) // Check the status before calculating
              user.total_time = this.appUtilService.getSumofTime(user.total_time, user.summary.data[i].total_time);
          }
        } else { // No summary data found related to that user
          user.total_time = "00:00";
        }
      });
    });
  }
  fetchData(next) {
    var selectedWeek = new Date(this.dateSelected);
    if (next) { // Get Next week's data
      selectedWeek.setDate(selectedWeek.getDate() + 7 );
      this.dateSelected = new Date(selectedWeek);
    } else { // Get previous week's data
      selectedWeek.setDate(selectedWeek.getDate() - 7 );
      this.dateSelected = new Date(selectedWeek);
    }
    this.getData(this.dateSelected);
  }
  sortBy(property, date, index) {
    if (property === 'name') {

      this.userData.sort( ( a, b) => {
          let textA = a.name.toUpperCase();
          let textB = b.name.toUpperCase();
          if (this.sorting.name.isAsc) {
            return (textA < textB) ? -1 : (textA > textB) ? 1 : 0;
          }else {
            return (textA > textB) ? -1 : (textA < textB) ? 1 : 0;
          }
      });

      this.sorting.name.isAsc = !this.sorting.name.isAsc;
    }
    else if (property === 'date') {
      this.sorting.date.date = new Date(date);
      this.userData.sort( ( a, b) => {
          console.log(a);
          console.log(b);
          console.log(index);
          /*let dateA = a.summary.length > 0 && a.summary.data[index] ? a.summary.data[index].total_time : '0' ;
          let dateB = b.summary.length > 0 && b.summary.data[index] ? b.summary.data[index].total_time : '0' ;*/

          // If summary has object 'data' & has values, then consider those, else consider them as -1
          let dateA = a.summary.hasOwnProperty('data') && a.summary.data[index] ? parseInt(a.summary.data[index].total_time.split(':')[0]) * 60 + parseInt(a.summary.data[index].total_time.split(':')[1]) : 0;
          let dateB = b.summary.hasOwnProperty('data') && b.summary.data[index] ? parseInt(b.summary.data[index].total_time.split(':')[0]) * 60 + parseInt(b.summary.data[index].total_time.split(':')[1]) : 0;
          
          console.log(a.summary);console.log("dateB" + dateB);

          if (this.sorting.date.isAsc) {
            return (dateA < dateB) ? -1 : (dateA > dateB) ? 1 : 0;
          }else {
            return (dateA > dateB) ? -1 : (dateA < dateB) ? 1 : 0;
          }
      });
      this.sorting.date.isAsc = !this.sorting.date.isAsc;
    }
    else if (property === 'total') {
      this.userData.sort( ( a, b) => {
        let dataA = parseInt(a.total_time.split(':')[0]) * 60 + parseInt(a.total_time.split(':')[1]);//parseFloat(a.total_time);
        let dataB = parseInt(b.total_time.split(':')[0]) * 60 + parseInt(b.total_time.split(':')[1]);//parseFloat(b.total_time);
        if (this.sorting.total.isAsc) {
          return (dataA < dataB) ? -1 : (dataA > dataB) ? 1 : 0;
        }else {
          return (dataA > dataB) ? -1 : (dataA < dataB) ? 1 : 0;
        }
      });

      this.sorting.total.isAsc = !this.sorting.total.isAsc;
    }
  }
  onFormatDate(dateValue) { // Format any form Date to 'yyyy-mm-dd'
    var date = new Date(dateValue);

    var month = (date.getMonth() + 1) < 10 ? '0' + (date.getMonth() + 1).toString() : (date.getMonth() + 1).toString();
    var day = (date.getDate()) < 10 ? '0' + (date.getDate()).toString() : (date.getDate()).toString();

    return date.getFullYear() + '-' + month + '-' + day;
  }
}
