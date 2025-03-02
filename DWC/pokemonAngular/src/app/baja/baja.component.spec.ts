import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MatListModule } from '@angular/material/list';
import { MatIconModule } from '@angular/material/icon';
import { MatButtonModule } from '@angular/material/button';
import { Dax40Service } from '../services/pokemon.service';
import { Dax40Record } from '../models/dax40-record.model';

@Component({
  selector: 'app-baja',
  standalone: true,
  imports: [CommonModule, MatListModule, MatIconModule, MatButtonModule],
  templateUrl: './baja.component.html',
  styleUrls: ['./baja.component.css']
})
export class BajaComponent implements OnInit {
  records: Dax40Record[] = [];

  constructor(private dax40Service: Dax40Service) {}

  ngOnInit() {
    this.dax40Service.getRecords().subscribe(data => {
      this.records = data;
    });
  }

  eliminarRecord(id: string) {
    if (confirm('¿Estás seguro de que quieres eliminar este registro?')) {
      this.dax40Service.deleteRecord(id).subscribe(() => {
        this.records = this.records.filter(record => record.id !== id);
      });
    }
  }
}