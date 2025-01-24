setTimeout(() =>{
    console.log("fisrt");
}, 1000);
console.log("second");

/*setTimeout(() => { console.log("first"); }, 1000);
: Esta línea establece un temporizador que ejecutará la 
función que imprime "first" después de 1000 milisegundos (o 1 segundo). 
La función no se ejecuta de inmediato; solo se programa para 
que se ejecute más tarde.
imprimira second first (un segundo después)
*/

const array = [1 ,2, 3];

for(var i in array){
    setTimeout(() => {
        console.log(i);
    }, 1000);
}

/*dará 222 por 
Declaración de array y for-in:
array contiene tres elementos [1, 2, 3].
El bucle for-in itera sobre las propiedades enumerables de un objeto o array,
en este caso, los índices de array: 0, 1, y 2.

Entonces, en cada iteración, i tomará los valores 0, 1, y 2.

Uso de var:
La palabra clave var tiene ámbito de función, no de bloque. 
Esto significa que la variable i es la misma en todas las iteraciones del bucle. 
No se "crea una nueva copia" de i en cada iteración; todas las iteraciones 
comparten la misma i.

setTimeout es asíncrono:
La función pasada a setTimeout se ejecuta después de 1000 ms 
(1 segundo), pero no se ejecuta inmediatamente. Durante ese tiempo, 
el bucle for-in ya ha terminado, y i tiene su último valor (2).

Resultado del console.log(i):
Cuando el código dentro de setTimeout finalmente se ejecuta, 
la variable i ya tiene el valor 2 (el último valor del bucle). 
Por eso, todas las iteraciones de setTimeout imprimirán 2.

Si queremos que cada iteración tenga su propia copia de i, podemos usar let 
en lugar de var. Esto se debe a que let tiene un alcance de bloque, por lo que 
cada iteración del bucle tendrá su propia instancia de i:*/

const array = [1, 2, 3];

for (let i in array) {
    setTimeout(() => {
        console.log(i);
    }, 1000);
}

/*esto imprimirá 0 ,1 ,2

si quiero los valores del array debo acceder a él indexando*/
const array = [1, 2, 3];

for (let i in array) {
    setTimeout(() => {
        console.log(array[i]);
    }, 1000);
}
