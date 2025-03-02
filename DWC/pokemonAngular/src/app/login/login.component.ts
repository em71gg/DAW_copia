import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatButtonModule } from '@angular/material/button';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [
    CommonModule,
    ReactiveFormsModule,
    MatFormFieldModule,
    MatInputModule,
    MatButtonModule
  ],
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {
  loginForm: FormGroup;
  errorMessage: string | null = null;

  private readonly validCredentials = {
    username: 'emilio',
    password: 'garruta'
  };

  constructor(private fb: FormBuilder, private router: Router) {
    this.loginForm = this.fb.group({
      username: ['', Validators.required],
      password: ['', Validators.required]
    });
  }

  ngOnInit() {
    console.log('LoginComponent initialized');
  }

  onSubmit() {
    this.errorMessage = null;
  
    if (this.loginForm.invalid) {
      if (this.loginForm.get('username')?.hasError('required') || this.loginForm.get('password')?.hasError('required')) {
        this.errorMessage = 'Por favor, completa todos los campos.';
      }
      return;
    }
  
    const { username, password } = this.loginForm.value;
    if (username === this.validCredentials.username && password === this.validCredentials.password) {
      localStorage.setItem('isLoggedIn', 'true');
      this.router.navigate(['/main']); // Redirige a /main
    } else {
      this.errorMessage = 'Nombre de usuario o contraseña incorrectos.';
    }
  }
}