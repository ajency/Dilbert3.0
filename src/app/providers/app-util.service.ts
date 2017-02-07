import { Injectable } from '@angular/core';
import { LocalStorageService } from 'ng2-webstorage';

@Injectable()
export class AppUtilService {
  user_data: any = {};
  constructor(private localStorage: LocalStorageService) {
    this.user_data = this.localStorage.retrieve('user_data'); // Read Data from localStorage key - "ng2-webstorage|user_data"
  }
  toSeconds(s) {
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
  formatDate(date) {
    var temp = new Date(date);
    return temp.getFullYear() + '-' + (temp.getMonth() + 1) + '-' + temp.getDate();
  }
  getWeek (date) {
    var temp = new Date(date);
    var onejan = new Date(temp.getFullYear(), 0 , 1);
    var temp2 = temp.getTime() - onejan.getTime();
    return Math.ceil(((( temp2) / 86400000) + onejan.getDay() + 1) / 7);
  }
  getStartAndEndOfDate(date) { // Get the start & end date for the API
    var curr = new Date(date);
    var firstDay = new Date(curr.setDate(curr.getDate() - curr.getDay() + 1));
    var lastDay = new Date (curr.setDate(curr.getDate() - curr.getDay() + 7 ));
    return {
      start : firstDay,
      end : lastDay
    };
  }
  getSumofTime(sumTime, newTime) { // Get 2 times, sum it up & return the summedUp value
    var temp1 = newTime.split(":");
    var temp2 = sumTime.split(":");
    var time2Mins = (parseInt(temp2[0]) + parseInt(temp1[0])) * 60 + (parseInt(temp2[1]) + parseInt(temp1[1])); // Convert the Summed-Up time to Mins
    sumTime = ((time2Mins / 60) < 10 ? "0" : "") + Math.floor(time2Mins / 60).toString() + ":" + ((time2Mins % 60) < 10 ? "0" : "") + Math.floor(time2Mins % 60).toString();

    return sumTime;
  }
}
