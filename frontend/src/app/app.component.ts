import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { RouterOutlet } from '@angular/router';

@Component({
  selector: 'app-root',
  imports: [RouterOutlet, FormsModule, CommonModule],
  templateUrl: './app.component.html',
  styleUrl: './app.component.css'
})
export class AppComponent {
  title = 'fuvarozorendszer';
    user = {
    name: '',
    email: '',
    password: '',
  };

  successMessage = '';
  errorMessage = '';

  constructor(private http: HttpClient) {}

  onSubmit() {
    this.http.post('http://localhost:8000/api/register', this.user).subscribe({
      next: (res: any) => {
        this.successMessage = 'Sikeres regisztráció!';
        this.errorMessage = '';
      },
      error: (err) => {
        this.errorMessage = 'Hiba történt a regisztráció során.';
        this.successMessage = '';
        console.error(err);
      },
    });
  }
}
