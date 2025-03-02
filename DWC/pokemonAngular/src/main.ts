import { bootstrapApplication } from '@angular/platform-browser';
import { AppComponent } from './app/app.component';
import { provideHttpClient, withFetch } from '@angular/common/http';
import { provideRouter, Routes } from '@angular/router';
import { LoginComponent } from './app/login/login.component';
import { AuthGuard } from './app/auth.guard';
import { ColeccionComponent } from './app/coleccion/coleccion.component';
import { AltaComponent } from './app/alta/alta.component';
import { BajaComponent } from './app/baja/baja.component';
import { MainLayoutComponent } from './app/main-layout/main-layout.component';
import { provideAnimations } from '@angular/platform-browser/animations'; // Importar provideAnimations

const routes: Routes = [
  { path: 'login', component: LoginComponent },
  { 
    path: 'main', 
    component: MainLayoutComponent,
    canActivate: [AuthGuard],
    children: [
      { path: '', redirectTo: 'coleccion', pathMatch: 'full' },
      { path: 'coleccion', component: ColeccionComponent },
      { path: 'alta', component: AltaComponent },
      { path: 'baja', component: BajaComponent }
    ]
  },
  { path: '', redirectTo: '/login', pathMatch: 'full' },
  { path: '**', redirectTo: '/login' }
];

bootstrapApplication(AppComponent, {
  providers: [
    provideHttpClient(withFetch()),
    provideRouter(routes),
    AuthGuard,
    provideAnimations() // Agregar provideAnimations para habilitar animaciones
  ]
}).catch(err => console.error(err));