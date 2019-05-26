import { Injectable } from '@angular/core';
import { Http, Response, Headers, RequestOptions } from "@angular/http";

import { Observable } from 'rxjs/Observable'
import 'rxjs/add/operator/map'
import "rxjs/add/operator/catch"
import "rxjs/add/operator/finally"
import "rxjs/add/observable/throw"


@Injectable()
export class RestService {

  public flagLoading = false;


  constructor(
    private http: Http,
  ) {}

  // Отправка заголовка
  getHeaders(headers={}): Headers {
    let a: undefined = undefined
    if (headers == 'rocket') {
      return
    }
    let _headers = new Headers();
    if (Object.values(headers)[0]){
      let token = Object.values(headers)[0];
      let lang = Object.values(headers)[1];
      if (lang) _headers.append('lang', lang.toString());
      _headers.append('Authorization', token.toString());
      _headers.append('Content-Type', 'application/json');
    }
    else if (Object.values(headers)) {
      let token = Object.values(headers);
      _headers.append('Authorization', token.toString());
      _headers.append('Content-Type', 'application/json');
    }
    else {
      Object.entries(headers).forEach((k, v) => {
        _headers.append(k.toString(), v.toString());
      });
    }
    return _headers;
  }

  getHeadersRocket(headers={}): Headers {
    let token = Object.values(headers)[0];
    let id = Object.values(headers)[1];
    let _headers = new Headers();
    _headers.append('x-auth-token', token.toString());
    _headers.append('x-user-id', id.toString());
    return _headers;
  }


  // Get запрос
  get(url, data = {}, headers={}): Observable<any> {
    if (Object.keys(data).length) {
      url = url + "?" + Object.entries(data).map(([k, v]) => {return `${k}=${v}`}).join(";")
    }
    this.flagLoading = true;
    return this.http.get(encodeURI(url), new RequestOptions({headers: this.getHeaders(headers)}))
      .map(this.mapper)
      .catch(this.handleError)
      .finally(()=>this.flagLoading = false)
  }

  // Post запрос
  post(url, body={}, headers={}):Observable<any> {
    this.flagLoading = true;
    return this.http.post(
      encodeURI(url),
      body,
      new RequestOptions({headers: this.getHeaders(headers)}))
      .map(this.mapper)
      .catch(this.handleError)
      .finally(()=>this.flagLoading = false)
  }

  getRocket(url, data = {}, headers={}): Observable<any> {
    if (Object.keys(data).length) {
      url = url + "?" + Object.entries(data).map(([k, v]) => {return `${k}=${v}`}).join("&")
    }
    this.flagLoading = true;
    return this.http.get(encodeURI(url), new RequestOptions({headers: this.getHeadersRocket(headers)}))
      .map(this.mapper)
      .catch(this.handleError)
      .finally(()=>this.flagLoading = false)
  }

  postRocket(url, body={}, headers={}):Observable<any> {
    this.flagLoading = true;
    return this.http.post(
      encodeURI(url),
      body,
      new RequestOptions({headers: this.getHeadersRocket(headers)}))
      .map(this.mapper)
      .catch(this.handleError)
      .finally(()=>this.flagLoading = false)
  }

  private handleError(error): Observable<any> {
    return Observable.throw(error.message || error)
  }

  private mapper(res: Response): any {
    const body: any = res.json();
    return body || {}
  }
}
