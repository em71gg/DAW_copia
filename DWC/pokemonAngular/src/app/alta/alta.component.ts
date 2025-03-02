import { Component, ChangeDetectorRef } from '@angular/core'; // Importar ChangeDetectorRef
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatButtonModule } from '@angular/material/button';
import { CommonModule } from '@angular/common';
import { PokemonService } from '../services/pokemon.service';
import { Pokemon } from '../models/pokemon.model';

@Component({
  selector: 'app-alta',
  standalone: true,
  imports: [
    ReactiveFormsModule,
    MatFormFieldModule,
    MatInputModule,
    MatButtonModule,
    CommonModule
  ],
  templateUrl: './alta.component.html',
  styleUrls: ['./alta.component.css']
})
export class AltaComponent {
  pokemonForm: FormGroup;
  feedbackMessage: string | null = null;

  constructor(
    private fb: FormBuilder,
    private pokemonService: PokemonService,
    private cdr: ChangeDetectorRef // Inyectar ChangeDetectorRef
  ) {
    this.pokemonForm = this.fb.group({
      id: ['', [Validators.required, Validators.min(1)]]
    });
  }

  onSubmit() {
    this.feedbackMessage = null;

    if (this.pokemonForm.invalid) {
      if (this.pokemonForm.get('id')?.hasError('required')) {
        this.feedbackMessage = 'El ID es obligatorio';
      }
      return;
    }

    const id = this.pokemonForm.get('id')?.value;
    this.pokemonService.getPokemonDetails(id).subscribe({
      next: (pokemon: Pokemon) => {
        this.pokemonService.addToCollection(pokemon);
        this.feedbackMessage = `${pokemon.name.charAt(0).toUpperCase() + pokemon.name.slice(1)} agregado a la lista`;
        // Reiniciar el formulario
        this.pokemonForm.reset();
        // Establecer explícitamente el valor como vacío
        this.pokemonForm.get('id')?.setValue('');
        // Marcar como no tocado y pristino
        Object.keys(this.pokemonForm.controls).forEach(key => {
          this.pokemonForm.get(key)?.markAsUntouched();
          this.pokemonForm.get(key)?.markAsPristine();
        });
        // Forzar la detección de cambios
        this.cdr.detectChanges();
      },
      error: (err) => {
        console.error('Error al buscar Pokémon:', err);
        this.feedbackMessage = 'No se encontró un Pokémon con ese ID.';
      }
    });
  }
}