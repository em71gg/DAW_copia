import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MatExpansionModule } from '@angular/material/expansion';
import { MatButtonModule } from '@angular/material/button';
import { PokemonService } from '../services/pokemon.service';
import { Pokemon } from '../models/pokemon.model';
import { switchMap } from 'rxjs/operators';

@Component({
  selector: 'app-coleccion',
  standalone: true,
  imports: [CommonModule, MatExpansionModule, MatButtonModule],
  templateUrl: './coleccion.component.html',
  styleUrls: ['./coleccion.component.css']
})
export class ColeccionComponent implements OnInit {
  pokemons: Pokemon[] = []; // La propiedad se llama 'pokemons', NO 'records'

  constructor(private pokemonService: PokemonService) {}

  ngOnInit() {
    this.loadPokemons();
  }

  loadPokemons() {
    this.pokemonService.getPokemons(20).subscribe(pokemons => {
      this.pokemons = pokemons;
      this.pokemons.forEach(pokemon => {
        this.pokemonService.getPokemonDetails(pokemon.id).subscribe(details => {
          pokemon.types = details.types;
          pokemon.sprite = details.sprite;
        });
      });
    });
  }

  addToMyCollection(pokemon: Pokemon) {
    this.pokemonService.addToCollection(pokemon);
  }
}