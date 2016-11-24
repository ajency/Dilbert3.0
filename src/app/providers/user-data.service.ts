import { Injectable } from '@angular/core';

import { Http, Headers, Response } from '@angular/http';
// import { User } from '../classes/user';
import { Observable } from 'rxjs/Observable';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/catch';
import 'rxjs/add/operator/toPromise';
import 'rxjs/add/observable/throw';

const apiURL = 'http://dilbertapp.ajency.in/api/data';
@Injectable()
export class UserDataService {
  headers: any;
  constructor(private http: Http) {
      this.headers = new Headers();
      this.headers.append('Content-Type', 'application/json');
      this.headers.append('X-API-KEY', 'PlUMtCQd7qwthy8k0kEN2kpnwXsrhP2VtAhmBagvvc9Qy6tvWb00TIZZtCF4');

  }
  getUserData(id, date): Observable<any> {
    let fetchurl = `${apiURL}/user?user_id=${id}`;
    if (date) {
      fetchurl += `&start_date=${date.start_date}&end_date=${date.end_date}`;
    }
    return this.http.get(fetchurl, { headers: this.headers })
                    .map(this.extractData)
                    .catch(this.handleError);
  }
  private extractData(res: Response) {
    return res.json();
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
