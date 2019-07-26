import { Component, OnDestroy, Inject } from '@angular/core';
import { DOCUMENT } from '@angular/common';
import { navItems } from '../../_nav';
import { AdminAuthService } from '../../services/admin-auth.service';
import { Router } from '@angular/router';
declare var require:any;
var toastr = require('toastr');

@Component({
  selector: 'app-dashboard',
  templateUrl: './default-layout.component.html'
})
export class DefaultLayoutComponent implements OnDestroy {
  public navItems = navItems;
  public sidebarMinimized = true;
  private changes: MutationObserver;
  public element: HTMLElement;
  constructor(
      public adminAuth:AdminAuthService,
      public router:Router,
      @Inject(DOCUMENT) _document?: any,
    ){
      
      this.changes = new MutationObserver((mutations) => {
        this.sidebarMinimized = _document.body.classList.contains('sidebar-minimized');
      });
      this.element = _document.body;
      this.changes.observe(<Element>this.element, {
        attributes: true,
        attributeFilter: ['class']
      });
    }
    
    ngOnDestroy(): void {
      this.changes.disconnect();
    }
    
    logout(){
      this.adminAuth.logOut().subscribe(res=>{
        localStorage.removeItem("_token");
        localStorage.removeItem("_user");
        this.router.navigate(["admin/login"]);
      },
      (res:any)=>{
        if(res.error){        
          toastr.error(res.error.message);
        }
      });
    }
    
  }
  