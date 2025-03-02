import { Injectable, PLATFORM_ID, Inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, map } from 'rxjs';
import { isPlatformBrowser } from '@angular/common';
import { Pokemon } from '../models/pokemon.model';

@Injectable({
  providedIn: 'root'
})
export class PokemonService {
  private apiUrl = 'https://pokeapi.co/api/v2/pokemon';
  private collectionUrl = 'http://localhost:5000/collection';
  private myCollection: Pokemon[] = [];

  constructor(
    private http: HttpClient,
    @Inject(PLATFORM_ID) private platformId: Object // Inyectar PLATFORM_ID
  ) {
    // Cargar la colección desde localStorage solo si estamos en el navegador
    if (isPlatformBrowser(this.platformId)) {
      this.loadFromLocalStorage();
    }
  }

  // Cargar la colección desde localStorage
  private loadFromLocalStorage(): void {
    if (!isPlatformBrowser(this.platformId)) {
      this.myCollection = []; // Valor por defecto en el servidor
      return;
    }

    const storedCollection = localStorage.getItem('myPokemonCollection');
    if (storedCollection) {
      try {
        this.myCollection = JSON.parse(storedCollection);
      } catch (e) {
        console.error('Error al cargar la colección desde localStorage:', e);
        this.myCollection = [];
      }
    } else {
      this.myCollection = [];
    }
  }

  // Guardar la colección en localStorage
  private saveToLocalStorage(): void {
    if (!isPlatformBrowser(this.platformId)) {
      return; // No hacer nada en el servidor
    }

    try {
      localStorage.setItem('myPokemonCollection', JSON.stringify(this.myCollection));
    } catch (e) {
      console.error('Error al guardar la colección en localStorage:', e);
    }
  }

  getPokemons(limit: number = 20, offset: number = 0): Observable<Pokemon[]> {
    return this.http.get<any>(`${this.apiUrl}?limit=${limit}&offset=${offset}`).pipe(
      map(response => {
        return response.results.map((result: any, index: number) => ({
          id: offset + index + 1,
          name: result.name,
          types: [],
          sprite: `https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${offset + index + 1}.png`
        }));
      })
    );
  }

  getPokemonDetails(id: number): Observable<Pokemon> {
    return this.http.get<any>(`${this.apiUrl}/${id}`).pipe(
      map(response => ({
        id: response.id,
        name: response.name,
        types: response.types.map((type: any) => type.type.name),
        sprite: response.sprites.front_default
      }))
    );
  }

  addToCollection(pokemon: Pokemon): void {
    if (!this.myCollection.some(p => p.id === pokemon.id)) {
      this.myCollection.push(pokemon);
      this.saveToLocalStorage();
    }
  }

  removeFromCollection(id: number): void {
    this.myCollection = this.myCollection.filter(p => p.id !== id);
    this.saveToLocalStorage();
  }

  getMyCollection(): Pokemon[] {
    return this.myCollection;
  }
}