// Pedir al usuario que introduzca un número
let numero = parseInt(prompt("Introduce un número para ver su tabla de multiplicar:"));

// Inicializar el contador
let i = 1;

// Comenzar el bucle while
while (i <= 10) {
    // Imprimir la multiplicación
    console.log(`
        ${numero} x ${i} = ${numero * i}`); //la sistaxis de plantilla de cadenas 
                                            //debe estar enviuelta en backstiks
    // Incrementar el contador
    i++;
}