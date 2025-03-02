import { Injectable } from '@angular/core';
import { CanActivate, Router } from '@angular/router';
import { Observable, of } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class AuthGuard implements CanActivate {
  constructor(private router: Router) {}

  canActivate(): Observable<boolean> | Promise<boolean> | boolean {
    console.log('AuthGuard: Checking if user is logged in');
    const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
    if (isLoggedIn) {
      console.log('AuthGuard: User is logged in, allowing access');
      return true;
    } else {
      console.log('AuthGuard: User is not logged in, redirecting to /login');
      this.router.navigate(['/login']);
      return false;
    }
  }
}