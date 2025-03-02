import { Component, OnInit } from '@angular/core';
import { MatListModule } from '@angular/material/list';
import { MatNavList } from '@angular/material/list';
import { RouterModule } from '@angular/router';

@Component({
  selector: 'app-menu-lateral',
  standalone: true,
  imports: [MatListModule, MatNavList, RouterModule],
  templateUrl: './menu-lateral.component.html',
  styleUrls: ['./menu-lateral.component.css']
})
export class MenuLateralComponent implements OnInit {
  ngOnInit() {
    console.log('MenuLateralComponent initialized');
  }
}