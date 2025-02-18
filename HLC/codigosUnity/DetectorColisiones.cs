using System.Collections;
using System.Collections.Generic;
using UnityEngine;

// Esta clase detecta colisiones con enemigos y actualiza la puntuación del jugador
public class DetectorColisiones : MonoBehaviour
{
    // Variable estática para almacenar la puntuación global del jugador
    private static float puntuacion = 0f;

    // Método que se activa cuando este objeto colisiona con otro objeto que tiene un Collider con "IsTrigger" activado
    private void OnTriggerEnter(Collider other)
    {
        /* Verifica si el objeto con el que colisionó tiene la etiqueta "Enemigo"
            1.	OnTriggerEnter(Collider other)
                o	Se ejecuta automáticamente cuando este objeto entra en contacto con otro objeto con un Collider en modo Trigger.
                o	La variable other representa el objeto con el que colisionó.
            2.	if (other.CompareTag("Enemigo"))
                o	Comprueba si el objeto con el que colisionó tiene la etiqueta "Enemigo".
                o	Si es así, suma 1 punto a la puntuación y muestra el valor en la consola.
            3.	Destroy(other.gameObject);
                o	Destruye el enemigo cuando lo golpea un proyectil u otro objeto con este script.
            4.	Destroy(gameObject);
                o   Destruye el objeto que tiene este script adjunto, como un proyectil que golpea a un enemigo. 
        */
        
        if (other.CompareTag("Enemigo"))
        {
            puntuacion += 1f; // Aumenta la puntuación en 1
            Debug.Log("Puntuación: " + puntuacion); // Muestra la puntuación actual en la consola

            Destroy(other.gameObject); // Destruye el objeto enemigo con el que colisionó
        }

        // Destruye el objeto actual (el que tiene este script adjunto)
        Destroy(gameObject);
    }

    // Método Start (se ejecuta una sola vez al inicio del juego)
    void Start()
    {
        // En este caso, no es necesario inicializar nada en Start
    }

    // Método Update (se ejecuta en cada frame)
    void Update()
    {
        // No se realiza ninguna acción en cada frame
    }
}
