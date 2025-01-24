window.onload = init;

var progressBar;

function init() {
  progressBar = document.querySelector(".progress div");

  window.addEventListener("scroll", function() {
      var max = document.body.scrollHeight - window.innerHeight;
      var percent = (window.scrollY / max) * 100;
      progressBar.style.width = percent + "%";
  });
}

/*
La función `init()` que muestras está diseñada para mostrar una barra de progreso que se llena a 
medida que el usuario se desplaza por la página. Vamos a desglosarla paso a paso:

### 1. **`progressBar = document.querySelector(".progress div");`**

Esta línea selecciona el primer `<div>` que está dentro de un elemento con la clase `progress`. 
Esto normalmente sería un contenedor de la barra de progreso, y el `div` dentro de él sería el 
que realmente muestra el progreso. 

Por ejemplo, en HTML, esto podría verse así:

```html
<div class="progress">
    <div></div> <!-- Este div es el que se usa para mostrar el progreso -->
</div>
```

La variable `progressBar` ahora hace referencia a ese `<div>`, que es el que cambiará su ancho (`width`) para mostrar el progreso de desplazamiento.

### 2. **`window.addEventListener("scroll", function() { ... });`**

Este código agrega un **evento de escucha** para el evento `scroll` en el objeto `window`. Esto significa que cada vez que el usuario haga scroll (desplazamiento) en la página, se ejecutará la función proporcionada como segundo argumento.

### 3. **Dentro de la función de `scroll`**:
La función interna que se ejecuta cada vez que se hace scroll tiene el siguiente código:

#### a) **`var max = document.body.scrollHeight - window.innerHeight;`**
Esta línea calcula el máximo desplazamiento posible en la página, es decir, la distancia total que el usuario puede desplazarse hacia abajo antes de llegar al final de la página.

- `document.body.scrollHeight`: La altura total del contenido de la página.
- `window.innerHeight`: La altura de la ventana del navegador visible.

La diferencia entre estas dos propiedades (`scrollHeight - innerHeight`) da el valor máximo del desplazamiento hacia abajo.

#### b) **`var percent = (window.scrollY / max) * 100;`**
Aquí se calcula el **porcentaje** de la página que se ha desplazado. 

- `window.scrollY`: La cantidad de píxeles que el usuario ha desplazado desde la parte superior de la página (distancia de scroll actual).
- `max`: La distancia máxima que puede desplazarse en la página (calculada en el paso anterior).

Dividiendo la distancia desplazada (`window.scrollY`) entre la distancia máxima (`max`) y luego multiplicando por 100, obtienes el porcentaje de desplazamiento que ha realizado el usuario.

#### c) **`progressBar.style.width = percent + "%";`**
Finalmente, se actualiza el **ancho de la barra de progreso** (`progressBar`). El ancho de la barra de progreso se establece en el porcentaje calculado anteriormente. Es decir, si el usuario ha desplazado el 50% de la página, el ancho de la barra de progreso será del 50%.

### Ejemplo completo de cómo funcionaría:

Imagina que tienes el siguiente HTML:

```html
<div class="progress">
  <div></div>
</div>
```

Y el siguiente CSS:

```css
.progress {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 5px;
  background-color: #ccc;
}

.progress div {
  height: 100%;
  background-color: green;
  width: 0%; /* Inicialmente la barra está vacía */
/*}*/
```

Y el JavaScript:

```
/*javascript
function init() {
  progressBar = document.querySelector(".progress div");

  window.addEventListener("scroll", function() {
      var max = document.body.scrollHeight - window.innerHeight; // Máximo desplazamiento
      var percent = (window.scrollY / max) * 100; // Porcentaje de desplazamiento
      progressBar.style.width = percent + "%"; // Actualiza el ancho de la barra
  });
}
*/
/*```
Qué sucede en este caso?

1. Cuando se carga la página, la función `init()` se ejecuta.
2. Al hacer scroll, se calcula el porcentaje de desplazamiento.
3. El ancho de la barra de progreso se actualiza en función de ese porcentaje, lo que da la sensación de que la barra se va llenando a medida que el usuario navega por la página.

### Resumen:

La función `/*init()` está configurada para que, cada vez que el usuario haga scroll en la página, se actualice el ancho de una barra de progreso, mostrándole visualmente cuánto ha desplazado hacia abajo en la página. El ancho de la barra aumenta en proporción al porcentaje de la página que el usuario ha recorrido.
*/