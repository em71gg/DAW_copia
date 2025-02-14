import {sumar, restar, PI} from './operaciones.js';

let display = document.querySelector('#display');
const suma = document.querySelector('#sumar');
const resta = document.querySelector('#restar');
const varP = document.querySelector('#pi');

suma.addEventListener('click', () =>{
    let sum1 = Number(document.querySelector('#num1').value);
    let sum2 = Number(document.querySelector('#num2').value);
    let rdo = sumar(sum1, sum2);

    display.innerHTML = `Resultado = ${rdo}` ;
});

resta.addEventListener('click', () => {
    let sum1 = Number(document.querySelector('#num1').value);
    let sum2 = Number(document.querySelector('#num2').value);
    let rdo = restar(sum1, sum2);
    display.innerHTML = `Resultado = ${rdo}` ;
});

varP.addEventListener('click', () => {
    display.innerHTML = `Resultado = ${PI}`;
});



