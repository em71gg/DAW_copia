<?php
/**
 * Método que valida si un número es positivo
 * @param {string} - Número a validar
 * @return {bool}
 */
    function validar_numero_positivo(string $numero): bool
    {
        // Verifica si el número es un entero o un decimal positivo
        return (filter_var($numero, FILTER_VALIDATE_FLOAT) === false || $numero <= 0) ? false : true;
    }

    $precio="23.5";
    $precio2 = 12.3;

    echo "precio cadena = " .validar_numero_positivo($precio) ."</br>";
    echo "precio numero = " .validar_numero_positivo($precio2) ."</br>";
?>