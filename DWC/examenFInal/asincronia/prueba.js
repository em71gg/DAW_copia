document.addEventListener("DOMContentLoaded", () => {
    let contenedor = document.querySelector("#container");
    let controles = document.querySelector("#botonesJuego");
    let finalizar = document.querySelector("#finJuego");
    let puntuacion = 0;
    let indiceCartaActual = 0;
    let mazo = [];

    document.querySelector("#iniciarJuego").addEventListener("click", () => {
        iniciarJuego();
    });

    function iniciarJuego() {
        contenedor.innerHTML = "";
        controles.innerHTML = "";
        finalizar.innerHTML = "";
        puntuacion = 0;
        indiceCartaActual = 0;

        fetch("https://deckofcardsapi.com/api/deck/new/shuffle/?deck_count=1")
            .then(response => response.json())
            .then(data => fetch(`https://deckofcardsapi.com/api/deck/${data.deck_id}/draw/?count=20`))
            .then(response => response.json())
            .then(data => {
                mazo = data.cards.map(carta => {
                    return {
                        imagen: carta.image,
                        valor: convertirValor(carta.value),
                        palo: traducirPalo(carta.suit)
                    };
                });
                mostrarCartaActual();
                crearBotones();
            })
            .catch(error => {
                contenedor.innerHTML = `<p>Error al obtener los datos del juego: ${error}</p>`;
            });
    }

    function mostrarCartaActual() {
        contenedor.innerHTML = "";
        let carta = mazo[indiceCartaActual];
        let verValorCarta = document.createElement("p");
        verValorCarta.textContent = `La carta es el ${carta.valor} de ${carta.palo}.`;
        contenedor.append(verValorCarta);

        let verImgCarta = document.createElement("img");
        verImgCarta.setAttribute("src", carta.imagen);
        verImgCarta.setAttribute("alt", `Carta: ${carta.valor} de ${carta.palo}`);
        contenedor.append(verImgCarta);

        actualizarPuntuacion();
    }

    function crearBotones() {
        let botonMas = document.createElement("button");
        botonMas.textContent = "La carta será mayor.";
        botonMas.addEventListener("click", () => jugar("mayor"));
        controles.append(botonMas);

        let botonMenos = document.createElement("button");
        botonMenos.textContent = "La carta será menor.";
        botonMenos.addEventListener("click", () => jugar("menor"));
        controles.append(botonMenos);

        let botonFin = document.createElement("button");
        botonFin.textContent = "Finalizar Juego.";
        botonFin.addEventListener("click", finalizarJuego);
        finalizar.append(botonFin);
    }

    function jugar(eleccion) {
        if (indiceCartaActual >= mazo.length - 1) return;
        let cartaActual = mazo[indiceCartaActual];
        let cartaSiguiente = mazo[++indiceCartaActual];

        if ((eleccion === "mayor" && cartaSiguiente.valor > cartaActual.valor) ||
            (eleccion === "menor" && cartaSiguiente.valor < cartaActual.valor)) {
            puntuacion++;
        } else if (cartaSiguiente.valor === cartaActual.valor) {
            // Puntuación se mantiene igual
        } else {
            puntuacion--;
        }
        mostrarCartaActual();
        if (indiceCartaActual === mazo.length - 1) finalizarJuego();
    }

    function finalizarJuego() {
        controles.innerHTML = "";
        finalizar.innerHTML = "";
        let mensaje = document.createElement("p");
        mensaje.textContent = `Juego finalizado. Puntuación final: ${puntuacion}`;
        contenedor.append(mensaje);
    }

    function actualizarPuntuacion() {
        let puntuacionDisplay = document.querySelector("#puntuacion");
        if (!puntuacionDisplay) {
            puntuacionDisplay = document.createElement("p");
            puntuacionDisplay.setAttribute("id", "puntuacion");
            contenedor.append(puntuacionDisplay);
        }
        puntuacionDisplay.textContent = `Puntuación: ${puntuacion}`;
    }

    function convertirValor(valor) {
        let valores = { "ACE": 14, "KING": 13, "QUEEN": 12, "JACK": 11 };
        return valores[valor] || parseInt(valor);
    }

    function traducirPalo(palo) {
        let palos = { "SPADES": "PICAS", "HEARTS": "CORAZONES", "DIAMONDS": "DIAMANTES", "CLUBS": "TRÉBOLES" };
        return palos[palo];
    }
});
