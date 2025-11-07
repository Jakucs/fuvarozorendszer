import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';

@Component({
  selector: 'app-register',
  imports: [FormsModule, CommonModule],
  templateUrl: './register.component.html',
  styleUrl: './register.component.css'
})
export class RegisterComponent {
    user = {
    name: '',
    email: '',
    password: '',
  };

  successMessage = '';
  errorMessage = '';

  constructor(private http: HttpClient, private router: Router) {}

  onSubmit() {
    console.log(this.user);
    this.http.post('http://localhost:8000/api/register', this.user).subscribe({
      next: (res: any) => {
        this.successMessage = 'Sikeres regisztráció!';
        this.errorMessage = '';
        this.goToLogin();
      },
      error: (err) => {
        this.errorMessage = 'Hiba történt a regisztráció során.';
        this.successMessage = '';
        console.error(err);
      },
    });
  }

  goToLogin(){
    this.router.navigate(['/login']);
  }
}
