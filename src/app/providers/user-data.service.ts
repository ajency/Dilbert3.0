import { Injectable } from '@angular/core';

import { Http, Headers, Response } from '@angular/http';
// import { User } from '../classes/user';
import { Observable }     from 'rxjs/Observable';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/toPromise';

const apiURL = 'http://dilbertapp.ajency.in/api/data';
@Injectable()
export class UserDataService {
  headers: any;
  constructor(private http: Http) {
      this.headers = new Headers();
      this.headers.append('Content-Type', 'application/json');
      this.headers.append('X-API-KEY', 'VzIBF33quCPo53PiLey9BxSIZwlh9zvYmyQzistxBWXDLu3hcGW6tZ3e5w1y');

  }
  getUserData(id, date) {
    let fetchurl = `${apiURL}/user?user_id=${id}`;
    if (date) {
      fetchurl += `&start_date=${date.start_date}&end_date=${date.end_date}`;
    }
    return this.http.get(fetchurl, { headers: this.headers })
                    .toPromise();
  }
  private extractData(res: Response) {
    let body = res.json();
    return body.data || { };
  }
  private handleError (error: Response | any) {
    // In a real world app, we might use a remote logging infrastructure
    let errMsg: string;
    if (error instanceof Response) {
      const body = error.json() || '';
      const err = body.error || JSON.stringify(body);
      errMsg = `${error.status} - ${error.statusText || ''} ${err}`;
    } else {
      errMsg = error.message ? error.message : error.toString();
    }
    console.error(errMsg);
    return Observable.throw(errMsg);
  }
}
