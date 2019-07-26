import { Component, OnInit } from '@angular/core';
import { AdminAuthService } from '../../services/admin-auth.service';
declare var require:any;
var toastr = require('toastr');

@Component({
  selector: 'app-admin-login',
  templateUrl: './admin-login.component.html',
  styleUrls: ['./admin-login.component.scss']
})
export class AdminLoginComponent implements OnInit {

  public admin:any = {};

  constructor(protected adminAuth:AdminAuthService) { }

  ngOnInit() {
  }

  onloginFormSubmit(form){
    if(!form.valid){
      toastr.error('Please provide valid data!');
      return false;
    }
    console.log(this.admin);
   this.adminAuth.login(this.admin).subscribe(
      (res:any)=>{
        console.log('ok');
        toastr.success(res.message);
        this.adminAuth.saveUser(res.user,res.token);
        window.location.href = "#/dashboard";
      },    
    (res:any)=>{
      if(res.error){        
        toastr.error(res.error.message);
      }
    });

    

  }

}
