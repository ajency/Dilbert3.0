import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';
import { Ng2Webstorage } from 'ng2-webstorage';

import { AppComponent } from './app.component';
import { HomeComponent } from './home/home.component';
import { UserDataService } from './providers/user-data.service';
import { AppUtilService } from './providers/app-util.service';
import { PopupComponent } from './popup/popup.component';

@NgModule({
  declarations: [
    AppComponent,
    HomeComponent,
    PopupComponent
  ],
  imports: [
    BrowserModule,
    FormsModule,
    HttpModule,
    Ng2Webstorage
  ],
  providers: [UserDataService, AppUtilService],
  bootstrap: [AppComponent]
})
export class AppModule { }
