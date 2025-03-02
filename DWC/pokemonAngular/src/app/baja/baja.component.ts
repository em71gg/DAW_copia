import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MatListModule } from '@angular/material/list';
import { MatIconModule } from '@angular/material/icon';
import { MatButtonModule } from '@angular/material/button';
import { PokemonService } from '../services/pokemon.service';
import { Pokemon } from '../models/pokemon.model';

@Component({
  selector: 'app-baja',
  standalone: true,
  imports: [CommonModule, MatListModule, MatIconModule, MatButtonModule],
  templateUrl: './baja.component.html',
  styleUrls: ['./baja.component.css']
})
export class BajaComponent implements OnInit {
  myCollection: Pokemon[] = [];

  constructor(private pokemonService: PokemonService) {}

  ngOnInit() {
    this.myCollection = this.pokemonService.getMyCollection();
  }

  removeFromCollection(id: number) {
    if (confirm(`¿Estás seguro de que quieres eliminar este Pokémon (ID: ${id})?`)) {
      this.pokemonService.removeFromCollection(id);
      this.myCollection = this.pokemonService.getMyCollection();
    }
  }
}