import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-admin',
  imports: [FormsModule, CommonModule],
  templateUrl: './admin.component.html',
  styleUrl: './admin.component.css'
})
export class AdminComponent {
  deliveries: any[] = [];
  carriers: any[] = [];
  selectedStatus: string = '';
  showNotifications = false;
  hasNewNotifications = true; // csak példa — lehet dinamikus
  notifications = [
  { message: 'Új fuvarfeladat érkezett!' },
  { message: 'Egy fuvar el lett végezve.' }
];
  newDelivery = {
    pickup_address: '',
    delivery_address: '',
    recipient_name: '',
    recipient_phone: '',
    carrier_id: null,
    carrier_name: ''
  };
  assignCarrierId = '';
  successMessage = '';
  errorMessage = '';
  showNewCarrier = false;
  newCarrierName = '';

  constructor(private http: HttpClient) {
    this.loadDeliveries();
    this.loadCarriers();
  }
  toggleNotifications() {
  this.showNotifications = !this.showNotifications;
  if (this.showNotifications) this.hasNewNotifications = false;
}

  toggleNewCarrier() {
  this.showNewCarrier = !this.showNewCarrier;
}

getAuthHeaders() {
  const token = localStorage.getItem('token');
  return {
    Authorization: `Bearer ${token}`,
  };
}


onStatusFilterChange() {
  this.http.get(`http://localhost:8000/api/admin/deliveries`, {
    params: this.selectedStatus ? { status: this.selectedStatus } : {},
    headers: this.getAuthHeaders()
  }).subscribe({
    next: (res: any) => {
    this.deliveries = res || []; // nem res.transport_jobs
  },
    error: err => console.error(err)
  });
}

  
  loadDeliveries() {
    this.http.get('http://localhost:8000/api/admin/deliveries').subscribe({
      next: (res: any) => {
        this.deliveries = res;
      },
      error: (err) => console.error(err),
    });
  }

  loadCarriers() {
  this.http.get('http://localhost:8000/api/admin/carriers').subscribe({
    next: (res: any) => {
      this.carriers = res;
    },
    error: (err) => console.error(err),
  });
}

  
    addCarrier() {
    const name = this.newCarrierName.trim();
    if (!name) {
      alert('Kérlek, adj meg egy nevet!');
      return;
    }

    this.http.post('http://localhost:8000/api/admin/storecarriers', { name }).subscribe({
      next: (res: any) => {
        const carrier = res.data; // backend a data mezőben adja vissza
        this.carriers.push(carrier); // frissítjük a listát
        this.newDelivery.carrier_id = carrier.id; // automatikusan kiválasztjuk
        this.newCarrierName = '';
        this.showNewCarrier = false;
      },
      error: (err) => {
        console.error(err);
        alert('Hiba történt a fuvarozó hozzáadásakor.');
      },
    });
  }



  
  createDelivery() {
    if (!this.newDelivery.carrier_id && !this.newDelivery.carrier_name) {
      alert('Válassz vagy adj meg új fuvarozót!');
      return;
    }

    this.http.post('http://localhost:8000/api/admin/deliveries', this.newDelivery).subscribe({
      next: (res: any) => {
        this.successMessage = 'Sikeresen létrehozva!';
        this.errorMessage = '';
            this.newDelivery = {
              pickup_address: '',
              delivery_address: '',
              recipient_name: '',
              recipient_phone: '',
              carrier_id: null,
              carrier_name: ''
            };
        this.loadDeliveries();
        this.loadCarriers(); // frissíti a fuvarozó listát
      },
      error: (err) => {
        this.errorMessage = 'Hiba a létrehozás során!';
        console.error(err);
      },
    });
  }



  
  editDelivery(delivery: any) {
    const updated = { ...delivery, recipient_name: prompt('Új címzett neve:', delivery.recipient_name) };
    this.http.put(`http://localhost:8000/api/admin/deliveries/${delivery.id}`, updated).subscribe({
      next: () => {
        this.successMessage = 'Sikeres módosítás!';
        this.loadDeliveries();
      },
      error: (err) => {
        this.errorMessage = 'Nem sikerült módosítani!';
        console.error(err);
      },
    });
  }

    updateCarrier(delivery: any) {
    const updated = { carrier_id: delivery.carrier_id };

    this.http.put(`http://localhost:8000/api/admin/deliveries/${delivery.id}`, updated).subscribe({
      next: () => {
        this.successMessage = 'Fuvarozó sikeresen módosítva!';
        this.loadDeliveries();
      },
      error: (err) => {
        this.errorMessage = 'Nem sikerült módosítani a fuvarozót!';
        console.error(err);
      },
    });
  }

  updateDelivery(delivery: any) {
    this.http.put(`http://localhost:8000/api/admin/deliveries/${delivery.id}`, delivery).subscribe({
      next: (res: any) => {
        delivery.showEdit = false; // bezárja a szerkesztőt
        delivery.carrier = res.data.carrier; // frissíti a kapcsolt fuvarozót
        this.successMessage = 'Munka sikeresen módosítva!';
      },
      error: (err) => {
        this.errorMessage = 'Nem sikerült módosítani!';
        console.error(err);
      }
    });
  }



  
  deleteDelivery(id: number) {
    if (confirm('Biztosan törlöd ezt a munkát?')) {
      this.http.delete(`http://localhost:8000/api/admin/deliveries/${id}`).subscribe({
        next: () => {
          this.successMessage = 'Sikeresen törölve!';
          this.loadDeliveries();
        },
        error: (err) => {
          this.errorMessage = 'Nem sikerült törölni!';
          console.error(err);
        },
      });
    }
  }

  
  assignToCarrier(id: number) {
    if (!this.assignCarrierId) {
      this.errorMessage = 'Adj meg egy fuvarozó ID-t!';
      return;
    }

    this.http.put(`http://localhost:8000/api/admin/deliveries/${id}/assign`, {
      carrier_id: this.assignCarrierId
    }).subscribe({
      next: () => {
        this.successMessage = 'Fuvarozó sikeresen hozzárendelve!';
        this.assignCarrierId = '';
      },
      error: (err) => {
        this.errorMessage = 'Hiba történt a hozzárendelés során!';
        console.error(err);
      },
    });
  }

  logout() {
    localStorage.removeItem('token');
    window.location.href = '/login';
  }
}
