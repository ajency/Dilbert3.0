import { Component, OnInit } from '@angular/core';
import { UserDataService } from '../providers/user-data.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {

  constructor(private userDataService: UserDataService) {
     this.userDataService.getUserData(1, null).then( (user) => {
       console.log(user);
     });
  }

  ngOnInit() {

  }

}
