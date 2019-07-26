import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { API_URL } from '../../environments/environment';

const httpOptions = {
  headers: new HttpHeaders({ //headers must contain S .
    'Content-Type':'application/json',
    'Authorization':localStorage.getItem("_token")
  })
};

@Injectable({
  providedIn: 'root'
})
export class AdminAuthService {

  constructor(private http:HttpClient) { }

  login(data){
    let url = API_URL+"/admin/login";  
    console.log(url);  
    return this.http.post(url, data, httpOptions);
  }

  saveUser(user,token){
    localStorage.setItem("_user", JSON.stringify(user));
    localStorage.setItem("_token",token);
  }

  isLoggedIn(){
    let token = localStorage.getItem("_token");
    return token ? true : false;
  }

  logOut(){    
    let url = API_URL+"/admin/logout";
    return this.http.post(url, {}, httpOptions);
  }
  
}
