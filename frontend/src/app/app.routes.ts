import { Routes } from '@angular/router';
import { LoginComponent } from './login/login.component';
import { RegisterComponent } from './register/register.component';
import { CarrierComponent } from './carrier/carrier.component';
import { AdminComponent } from './admin/admin.component';

export const routes: Routes = [
  { path: '', component: RegisterComponent },
  { path: 'register', component: RegisterComponent },
  { path: 'login', component: LoginComponent },
  { path: 'carrier', component: CarrierComponent },
  { path: 'admin', component: AdminComponent}
];
