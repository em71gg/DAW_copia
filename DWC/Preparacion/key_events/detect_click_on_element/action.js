window.onclick = processClick;

function processClick(evt) {
  var clicks = document.querySelector('#clicks');

  var target = evt.target.id; //La propiedad id del target devuelve el valor del atributo id del elemento HTML que disparó el evento.
                            //Este enfoque es útil para manejar eventos genéricos en múltiples elementos y luego determinar el 
                            //comportamiento específico según el elemento que los disparó
  
  if(target === "") {
    clicks.innerHTML += "You clicked on the window, not on a particular element!<br>";
  } else {
    clicks.innerHTML += "Element clicked id: " + target + "<br>";
   
  }
  
  
  evt.stopPropagation(); // try commenting it and click on the button or div
}
/*
En tu código HTML, tienes un botón (`#button1`) y un `div` (`#myDiv`) que ambos llaman a la función `processClick(event)` cuando se hace clic en ellos. Esto puede dar lugar a que la función se ejecute más de una vez debido a la **propagación de eventos**, como mencioné antes.

### **Por qué ocurre el problema**
- Cuando haces clic en el botón (`#button1`), el evento `click` se dispara en el botón y luego se propaga al padre, que en este caso es el `div` (`#myDiv`).
- Como ambos elementos están usando la misma función (`processClick`), esta se ejecuta tanto para el botón como para el div en la fase de burbuja.

### **Solución con `stopPropagation`**
Si no quieres que el evento `click` del botón se propague al div, debes llamar a `event.stopPropagation()` dentro de la función `processClick` **cuando el clic se origine en el botón**.

Aquí tienes cómo ajustar la función `processClick` en tu archivo `action.js`:

#### **Archivo `action.js`**

```javascript
function processClick(event) {
    // Detén la propagación si el clic ocurrió en el botón
    if (event.target.id === "button1") {
        console.log("Button clicked");
        event.stopPropagation(); // Evita que el evento llegue al div
    } else if (event.target.id === "myDiv") {
        console.log("Div clicked");
    }

    // Opcional: muestra información del evento
    const clicksDiv = document.getElementById("clicks");
    clicksDiv.textContent = `You clicked on: ${event.target.id}`;
}
```

---

### **Cómo funciona**
1. Cuando haces clic en `#button1`:
   - Se ejecuta `processClick(event)`.
   - Se detecta que el `event.target.id` es `"button1"`, por lo que se detiene la propagación usando `event.stopPropagation()`.
   - Esto evita que el evento alcance el manejador asociado a `#myDiv`.

2. Cuando haces clic en `#myDiv` directamente:
   - La función `processClick(event)` se ejecuta.
   - Como el `event.target.id` es `"myDiv"`, no se llama a `stopPropagation()`, y se maneja normalmente.

---

### **Sin `stopPropagation`**
Si decides no usar `stopPropagation`, al hacer clic en `#button1`, tanto el botón como el div ejecutarán la función, y podrías ver mensajes como estos en la consola:

```
Button clicked
Div clicked
```

Esto ocurre porque el evento burbujea desde el botón hasta el div. 

---

### **Otras Mejores Prácticas**
Si el uso de `onclick` en el HTML no es obligatorio, puedes separar completamente la lógica del evento y usar JavaScript puro para manejar los clics:

```javascript
document.getElementById("button1").addEventListener("click", (event) => {
    console.log("Button clicked");
    event.stopPropagation();
});

document.getElementById("myDiv").addEventListener("click", () => {
    console.log("Div clicked");
});
```

Esto es más modular y facilita la lectura y el mantenimiento del código.*/