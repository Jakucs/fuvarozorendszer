import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class AuthapiService {

    private apiUrl = 'http://127.0.0.1:8000/api'; // pontos backend URL

    constructor(private http: HttpClient) {}

    register(userData: any): Observable<any> {
      return this.http.post(`${this.apiUrl}/register`, userData);
  }
}
