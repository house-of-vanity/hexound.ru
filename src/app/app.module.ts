import { BrowserModule } from '@angular/platform-browser';
import {CUSTOM_ELEMENTS_SCHEMA, NgModule} from '@angular/core';

import { AppComponent } from './app.component';
import { RestService } from './services/rest.services';
import {RouterModule} from '@angular/router';
import {appRoutes} from './routers.register';
import {HttpModule} from '@angular/http';
import {CommonService} from './services/common.service';
import {MaterialModule} from './modules/material/material.module';
import {FormsModule} from '@angular/forms';
import {CommonModule} from '@angular/common';
import {BrowserAnimationsModule} from '@angular/platform-browser/animations';

@NgModule({
  declarations: [
    AppComponent
  ],
  imports: [
    BrowserModule,
    MaterialModule,
    CommonModule,
    FormsModule,
    HttpModule,
    BrowserAnimationsModule,
    RouterModule.forRoot(
      appRoutes,
    )
  ],
  schemas: [CUSTOM_ELEMENTS_SCHEMA],
  providers: [
    RestService,
    CommonService,
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
