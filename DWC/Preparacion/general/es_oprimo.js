function esPrimo(numero) {
    // Los números menores o iguales a 1 no son primos
    if (numero <= 1) {
        return false;
    }

    // Verificamos si el número es divisible por algún número entre 2 y la raíz cuadrada del número
    for (let i = 2; i <= Math.sqrt(numero); i++) {
        if (numero % i === 0) {
            return false; // Si es divisible, no es primo
        }
    }
    return true; // Si no es divisible por ningún número, es primo
}

// Ejemplo de uso
/*const numero = parseInt(prompt("Introduce un número:"));
if (esPrimo(numero)) {
    console.log(
${numero} es un número primo.
);
} else {
    console.log(
${numero} no es un número primo.
);
}*/