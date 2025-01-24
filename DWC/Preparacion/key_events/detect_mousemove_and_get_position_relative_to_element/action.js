window.onload = init;
//document.addEventListener('DOMContentLoaded', init);

function init() {
  // page has been loaded
  canvas = document.querySelector('#myCanvas');
  
  canvas.addEventListener('mousemove', processMouseMouve)
}

function processMouseMouve(evt) {
  var mousePositions = document.querySelector('#mousePositions');
  var rect = evt.target.getBoundingClientRect()//target.getBoundingClientRect() method that returns the bounding rectangle that contains 
                                              //the element that fired the event. The return value has top, left, width, and height properties 
                                              //that describe this rectangle. We can use the top and left properties along with evt.clientX 
                                              //and evt.clientY to fix the mouse position and to get a real position relative to the top left 
                                              //corner of the canvas:
  var mouseX = evt.clientX - rect.left;
  var mouseY = evt.clientY - rect.top;
  
  mousePositions.innerHTML = "mouse pos X: " + mouseX +
                              " mouse pos Y: " + mouseY + 
                              "<br>" 
 }
