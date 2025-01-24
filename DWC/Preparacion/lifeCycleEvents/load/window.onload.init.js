// Good practice: have a function
window.onload = init;

function init(evt) {
  // called when the page is loaded
  
  console.log("page loaded");
  
  let span = document.querySelector("#pageStatus");
  span.innerHTML = "PAGE LOADED"; 
  
  let b = document.querySelector("#myButton");
  b.addEventListener('mousemove', function(evt) {
    console.log("x = " + evt.clientX);
  });
  document.addEventListener('mousemove', function(evt){
    let mostrarCoor = document.querySelector('#coord');
    let coordSpan = mostrarCoor.firstElementChild.nextElementSibling;//estoy seleccionado el span que está con 2º elemento
    coordSpan.innerHTML = "x = " + evt.clientX + ", y = " + evt.clientY;
  });
}
