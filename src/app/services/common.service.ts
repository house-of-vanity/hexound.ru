import { Injectable } from '@angular/core';
import {Urls} from '../models/urls.models';
import {ModelSongs} from '../models/song.models';
import {RestService} from './rest.services';


@Injectable()
export class CommonService {

  flagSpinner: boolean = false;

  setting = {
    limit: 30,
    offset: 0
  };

  songArray: Array<any> = [];

  constructor(
    private restService: RestService,
  ) {}

  getSong() {
    this.flagSpinner = true;
    this.restService.get(Urls.song, this.setting).subscribe((response) => {
      this.songArray = this.songArray.concat(response.map(song => {
          let result = new ModelSongs();
          result.songName = song.filename;
          result.id = song.id;
          result.md5 = song.md5;
          result.time = song.time;
          return result;
        })
      );
      this.flagSpinner = false;
      console.log(this.songArray);
    });
  }

  onScroll() {
    this.setting.offset += 30;
    this.getSong()
  }

}
