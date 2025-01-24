/*El método map crea un nuevo array tomando cada elemento del array 
original (arr) y aplicando una función a ese elemento. La función que 
se pasa a map debe devolver un valor para incluirlo en el nuevo array. 
Si no se devuelve ningún valor (es decir, la función retorna undefined), 
ese elemento del nuevo array será undefined.
*/

const arr=  [1,2,3];
const result = arr.map(num => {
    if(num % 2 === 0) {
        return num *2;
    }
});
console.log(result);


const numeros = [10, 20, 30, 40, 50]; // Array con 5 números

// Usamos reduce para sumar los elementos
const sumaTotal = numeros.reduce((acumulador, numero) => acumulador + numero, 0);

console.log("La suma total es:", sumaTotal);



const numeros = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]; // Array del 1 al 10

// Usamos filter para obtener solo los números pares
/**
filter(num => num % 2 === 0):

filter recorre cada elemento del array y aplica una condición.
La condición es num % 2 === 0, que verifica si el número es divisible 
entre 2 (es decir, si es par).
Solo los elementos que cumplan la condición 
se incluirán en el nuevo array.
 */
const numerosPares = numeros.filter(num => num % 2 === 0);

console.log("Números pares:", numerosPares);