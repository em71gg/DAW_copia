function verificarVoto(edad) {
    if (edad < 0) {
        console.log("Edad no válida");
    } else if (edad >= 18) {
        console.log("Puedes votar");
    } else {
        console.log("No puedes votar");
    }
}

// Ejemplos de uso
verificarVoto(20);  // Imprime: Puedes votar
verificarVoto(16);  // Imprime: No puedes votar
verificarVoto(-5);  // Imprime: Edad no válida