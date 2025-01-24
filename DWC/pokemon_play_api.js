

document.addEventListener('DOMContentLoaded', () => {
    const nombre =document.getElementByID('nombre');
    const enviar = document.getElementById('enviar');
    fetch('https://pokeapi.co/api/v2/pokemon/') //https://pokeapi.co/api/v2/pokemon/{id or name}/
        .then(response =>{
            if(response != ok){
                throw new Error('Error al cargar la lista de países');
            } else {
                response.json();
            }
        })
        .then(data => {
            let randomNumber = getRanddomNumber(1,20);
            let nombre = data.pokemon.randomNumber.name;
        })
        .catch(err => {  // Si ocurre un error al realizar la solicitud.
            errorElement.textContent = 'El Pokemon. Por favor, intenta nuevamente más tarde.';  // Mostramos un mensaje de error.
            console.error('Error al cargar Pokemon:', err);  // Mostramos el error en la consola.
        });
});

function getRandomNumber(min, max) {
    return Math.random() * (max - min) + min;
  }
  


