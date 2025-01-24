/**
 * bucle para limpiar un array de huecos 
 */
function eraseHolesFromArray (array) {
    for (let i of array) {
        if(array[i] === undefined) arr.splice(i,1);
    }
    return array;
}

