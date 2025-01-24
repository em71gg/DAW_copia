function verificarAgrupamiento(expresion) {
    const stack = [];
    const apertura = { '(': ')', '[': ']', '{': '}' };
    const cierre = { ')': '(', ']': '[', '}': '{' };

    for (let i = 0; i < expresion.length; i++) {
        const char = expresion[i];

        if (apertura[char]) {
            // Si es un carácter de apertura, apilarlo con su posición
            stack.push({ char, index: i });
        } else if (cierre[char]) {
            // Si es un carácter de cierre
            if (stack.length === 0 || stack[stack.length - 1].char !== cierre[char]) {
                // Si el cierre no tiene un apertura correspondiente, devolver la posición
                return i;
            }
            // Si está correctamente emparejado, desapilar
            stack.pop();
        }
    }

    // Si queda algo en el stack, significa que hay aperturas sin cerrar
    if (stack.length > 0) {
        return stack[0].index;
    }

    // Si todo está correcto
    return -1;
}

// Ejemplo de uso
const expresion = "{[()]}"; // Correcto
console.log(verificarAgrupamiento(expresion)); // -1

const expresion2 = "{[(])}"; // Incorrecto
console.log(verificarAgrupamiento(expresion2)); // 3 (posición del error)

const expresion3 = "{[()"; // Incorrecto
console.log(verificarAgrupamiento(expresion3)); // 0 (posición del primer carácter sin cerrar)
