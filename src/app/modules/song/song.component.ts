import { Component, OnInit } from '@angular/core';
import {RestService} from '../../services/rest.services';
import {Urls} from '../../models/urls.models';
import {CommonService} from '../../services/common.service';
import {ModelSongs} from '../../models/song.models';

@Component({
  selector: 'app-song',
  templateUrl: './song.component.html',
  styleUrls: ['./song.component.scss']
})
export class SongComponent implements OnInit {

  constructor(
    private restService: RestService,
    public commonService: CommonService,
  ) { }

  ngOnInit() {
    this.restService.get(Urls.song).subscribe((response) => {
      this.commonService.songArray = response.map(song => {
        let result = new ModelSongs();
        result.songName = song.filename;
        result.id = song.id;
        result.md5 = song.md5;
        result.time = song.time;
        return result
      })
      console.log(this.commonService.songArray)
    })
  }

}
