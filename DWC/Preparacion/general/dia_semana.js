// Función para obtener el día de la semana
function obtenerDiaDeLaSemana(numero) {
    switch (numero) {
        case 1:
            return "Lunes";
        case 2:
            return "Martes";
        case 3:
            return "Miércoles";
        case 4:
            return "Jueves";
        case 5:
            return "Viernes";
        case 6:
            return "Sábado";
        case 7:
            return "Domingo";
        default:
            return "Por favor, introduce un número del 1 al 7.";
    }
}

// Solicitar al usuario un número
let numero = parseInt(prompt("Introduce un número del 1 al 7:"));

// Obtener y mostrar el día correspondiente
let dia = obtenerDiaDeLaSemana(numero);
alert(dia);