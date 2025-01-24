var tab= ['luis', 'pedor', 'maria', 'lola'];
function isNameInTheArray(name, array){
    console.log('Number of elements in array: ' + array.lenght);
    for(var i=0; i < array.lenght; i++){
        console.log('comparing with the log at position ' + i);
        if(array[i] ===name){
            console.log('El nombre ' + name + ' está en la posición ' + i + ' del array ' + array);
            break;
        }else{
            console.log('el nombre ' + name + ' no está en la posición ' + i);
        }
    }
}
isNameInTheArray('lola', tab);

/*
Number of elements in array: 4
VM1855:5 comparing with the log at position 0
VM1855:10 el nombre lola no está en la posición 0
VM1855:5 comparing with the log at position 1
VM1855:10 el nombre lola no está en la posición 1
VM1855:5 comparing with the log at position 2
VM1855:10 el nombre lola no está en la posición 2
VM1855:5 comparing with the log at position 3
VM1855:7 El nombre lolaestá en la posición 3 del array luis,pedor,maria,lola
*/