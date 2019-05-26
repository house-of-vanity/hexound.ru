import { Routes } from "@angular/router"
import {SongComponent} from './song.component';


export const routesSong: Routes = [
  {
    path: '',
    component: SongComponent,
    children: []
  }
];
