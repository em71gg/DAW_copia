

document.addEventListener('DOMContentLoaded', () =>{
    const limpiarForm = document.querySelector('#resetModificar');

    limpiarForm.addEventListener ('click' , () => {
        const formulario = document.querySelector('form');

        document.querySelector('#nombre').value = '';
        document.querySelector('#descripcion').value = '';
        document.querySelector('#fecha').value ='';
        document.querySelector('#aforo').value = '';

        const categoriaSelec = document.querySelector('#categoria');
        categoriaSelec.selectedIndex = 0; // Fija el valor a su primera opción ("Seleccione una categoria")
    });
});
