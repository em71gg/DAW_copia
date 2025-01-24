function calcularDiferenciaFechas(fechaInicial, fechaFinal) {
    // Convertir las fechas a objetos Date
    const inicio = new Date(fechaInicial);
    const fin = new Date(fechaFinal);

    // Calcular la diferencia en milisegundos
    const diferencia = fin - inicio;

    // Calcular los días
    const dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));

    // Calcular las horas restantes
    const horas = Math.floor((diferencia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));

    // Calcular los minutos restantes
    const minutos = Math.floor((diferencia % (1000 * 60 * 60)) / (1000 * 60));

    // Retornar el resultado como un objeto
    return {
        dias: dias,
        horas: horas,
        minutos: minutos
    };
}

// Ejemplo de uso:
const fechaInicial = "2024-12-01T12:00:00"; // Fecha inicial
const fechaFinal = "2024-12-05T15:30:00";  // Fecha final

const diferencia = calcularDiferenciaFechas(fechaInicial, fechaFinal);
console.log(`Días: ${diferencia.dias}, Horas: ${diferencia.horas}, Minutos: ${diferencia.minutos}`);
