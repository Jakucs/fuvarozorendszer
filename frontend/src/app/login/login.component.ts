import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';

@Component({
  selector: 'app-login',
  imports: [FormsModule, CommonModule],
  templateUrl: './login.component.html',
  styleUrl: './login.component.css'
})
export class LoginComponent {
  credentials = {
    email: '',
    password: '',
  };

  successMessage = '';
  errorMessage = '';

  constructor(private http: HttpClient, private router: Router) {}

  onLogin() {
    this.http.post('http://localhost:8000/api/login', this.credentials).subscribe({
      next: (res: any) => {
        this.successMessage = 'Sikeres bejelentkezés!';
        this.errorMessage = '';

        if (res.token) {
          localStorage.setItem('token', res.token);
          localStorage.setItem('user', JSON.stringify(res.user));
        }

        
        if (res.user.role === 'carrier') {
          this.router.navigate(['/carrier']);
        } else if (res.user.role === 'admin') {
          this.router.navigate(['/admin']);
        } else {
          this.router.navigate(['/']);
        }
      },
      error: (err) => {
        this.errorMessage = 'Hibás e-mail vagy jelszó!';
        this.successMessage = '';
        console.error(err);
      },
    });
  }


  goToRegister(){
    this.router.navigate(['/register']);
  }
}
