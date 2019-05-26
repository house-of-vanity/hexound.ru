import { Component, OnInit } from '@angular/core';
import {CommonService} from '../../services/common.service';

@Component({
  selector: 'app-song',
  templateUrl: './song.component.html',
  styleUrls: ['./song.component.scss']
})
export class SongComponent implements OnInit {

  displayedColumns: string[] = ['No.', 'Name', 'Time'];

  constructor(
    public commonService: CommonService,
  ) { }

  ngOnInit() {
    this.commonService.getSong()
  }

}
