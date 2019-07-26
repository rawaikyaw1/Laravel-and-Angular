import { Injectable } from '@angular/core';
import { CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot, UrlTree, Router } from '@angular/router';
import { Observable } from 'rxjs';
import { AdminAuthService } from './services/admin-auth.service';

@Injectable({
  providedIn: 'root'
})
export class AdminGuard implements CanActivate {

  constructor(public adminAuthSvc: AdminAuthService, public router : Router){

  }

  canActivate(
    next: ActivatedRouteSnapshot,
    state: RouterStateSnapshot): Observable<boolean | UrlTree> | Promise<boolean | UrlTree> | boolean | UrlTree {

    if(!this.adminAuthSvc.isLoggedIn()){
      return this.router.navigate(['admin/login']);
    }

    return this.adminAuthSvc.isLoggedIn();
    
  }
  
}
