import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
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

  // Kiosztott fuvarok betöltése
  loadDeliveries() {
    this.http.get('http://localhost:8000/api/carrier/deliveries').subscribe({
      next: (res: any) => {
        this.deliveries = res;
      },
      error: (err) => {
        console.error(err);
        this.errorMessage = 'Nem sikerült betölteni a fuvarokat.';
      },
    });
  }

  // Státusz módosítása
  updateStatus(delivery: any) {
    const payload = { status: delivery.status };

    this.http.put(`http://localhost:8000/api/carrier/deliveries/${delivery.id}/status`, payload)
      .subscribe({
        next: () => {
          this.successMessage = 'Státusz sikeresen frissítve!';
          this.errorMessage = '';
        },
        error: (err) => {
          console.error(err);
          this.errorMessage = 'Nem sikerült módosítani a státuszt.';
        },
      });
  }
}
