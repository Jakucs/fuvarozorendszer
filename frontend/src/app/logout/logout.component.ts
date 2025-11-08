import { Component } from '@angular/core';
import { AuthapiService } from '../authapi.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-logout',
  imports: [],
  templateUrl: './logout.component.html',
  styleUrl: './logout.component.css'
})
export class LogoutComponent {


  constructor(private authapi: AuthapiService, private router: Router){

  }

  ngOnInit(): void {
    
    this.authapi.logout();
    
    setTimeout(() => {
      this.router.navigate(['/login']);
    }, 1000);
  }
}
