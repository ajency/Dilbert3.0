import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';

import { AppComponent } from './app.component';
import { HomeComponent } from './home/home.component';
import { UserDataService } from './providers/user-data.service';
import { AppUtilService } from './providers/app-util.service';
import { TodaysCardComponent } from './todays-card/todays-card.component';

@NgModule({
  declarations: [
    AppComponent,
    HomeComponent,
    TodaysCardComponent
  ],
  imports: [
    BrowserModule,
    FormsModule,
    HttpModule
  ],
  providers: [UserDataService, AppUtilService],
  bootstrap: [AppComponent]
})
export class AppModule { }
