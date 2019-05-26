import { CUSTOM_ELEMENTS_SCHEMA, NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import {routesSong} from './song.route';
import {SongComponent} from './song.component';
import {MaterialModule} from '../material/material.module';

@NgModule({
  imports: [
    CommonModule,
    MaterialModule,
    RouterModule.forChild(routesSong),
  ],
  declarations: [
    SongComponent
  ],
  exports: [
    SongComponent
  ],
  schemas: [CUSTOM_ELEMENTS_SCHEMA],
  providers: [],
})
export class SongModule { }
