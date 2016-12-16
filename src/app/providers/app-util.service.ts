import { Injectable } from '@angular/core';
import { LocalStorageService } from 'ng2-webstorage';

@Injectable()
export class AppUtilService {
  // @LocalStorage() public user_data: any = {
  //   name: 'mohsin'
  // };
  user_data: any = {};
  constructor( private localStorage: LocalStorageService ) {
    this.user_data = this.localStorage.retrieve('user_data');
  }
  toSeconds(timeString) {
    let p = timeString.split(':');
    return (parseInt(p[0], 10) * 3600) + (parseInt(p[1], 10) * 60 );
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
  timeConversion(milliseconds) {

        // Get hours from milliseconds
      let hours = milliseconds / (1000 * 60 * 60);
      let absoluteHours = Math.floor(hours);
      let h = absoluteHours > 9 ? absoluteHours : '0' + absoluteHours;

      // Get remainder from hours and convert to minutes
      let minutes = (hours - absoluteHours) * 60;
      let absoluteMinutes = Math.floor(minutes);
      let m = absoluteMinutes > 9 ? absoluteMinutes : '0' +  absoluteMinutes;
      return h + ':' + m;
  }
  formatDate(date) {
    let temp = new Date(date);
    return temp.getFullYear() + '-' + (temp.getMonth() + 1) + '-' + temp.getDate();
  }
  getWeek (date) {
    let temp = new Date(date);
    let onejan = new Date(temp.getFullYear(), 0 , 1);
    let temp2 = temp.getTime() - onejan.getTime();
    return Math.ceil(((( temp2) / 86400000) + onejan.getDay() + 1) / 7);
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
        let curr = new Date(date);
        let firstDay = new Date(curr.setDate(curr.getDate() - curr.getDay() + 1));
        let lastDay = new Date (curr.setDate(curr.getDate() - curr.getDay() + 7 ));
        return {
          start : firstDay,
          end : lastDay
        };
      }
  }
}
