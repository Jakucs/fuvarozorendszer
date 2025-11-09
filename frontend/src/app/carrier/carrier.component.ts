import { CommonModule } from '@angular/common';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-carrier',
  imports: [FormsModule, CommonModule],
  templateUrl: './carrier.component.html',
  styleUrl: './carrier.component.css'
})
export class CarrierComponent {
  deliveries: any[] = [];
  successMessage = '';
  errorMessage = '';

  constructor(private http: HttpClient) {
    this.loadDeliveries();
  }

  
  private getAuthHeaders(): HttpHeaders {
    const token = localStorage.getItem('token');
    return new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
  }

  
    loadDeliveries() {
    this.http.get('http://localhost:8000/api/carrier/deliveries', {
      headers: this.getAuthHeaders()
    }).subscribe({
      next: (res: any) => {
        let jobs = res.transport_jobs ?? (Array.isArray(res) ? res : []);
        
        // Minden fuvarhoz beállítjuk a státuszt, ha üres
        this.deliveries = jobs.map((d: any) => ({
          ...d,
          status: d.status || 'Kiosztva'  // alapértelmezett státusz
        }));
      },
      error: (err) => {
        console.error(err);
        this.errorMessage = 'Nem sikerült betölteni a fuvarokat.';
      },
    });
  }


  
  updateStatus(delivery: any) {
    const payload = { status: delivery.status };

    this.http.put(`http://localhost:8000/api/carrier/deliveries/${delivery.id}`, payload, {
      headers: this.getAuthHeaders()
    }).subscribe({
      next: (res: any) => {
        this.successMessage = 'Státusz sikeresen frissítve!';
        this.errorMessage = '';
      },
      error: (err) => {
        console.error(err);
        this.errorMessage = 'Nem sikerült módosítani a státuszt.';
      },
    });
  }

    logout() {
    localStorage.removeItem('token');
    window.location.href = '/login';
  }
}
