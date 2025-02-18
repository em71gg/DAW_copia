using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class CONTROLJUGADOR : MonoBehaviour
{
    // Variable para almacenar el desplazamiento horizontal del jugador
    public float desplazamientoHorizontal;

    // Velocidad de movimiento del jugador
    public float velocidad = 10.0f;

    // Rango máximo de movimiento en el eje X para evitar que el jugador salga de la pantalla
    public float rangoX = 24.0f;

    // Prefab del proyectil que se disparará al presionar la tecla Espacio
    public GameObject proyectilPreFab;

    // Prefab del segundo proyectil que se disparará al presionar la tecla Enter
    public GameObject proyectil2PreFab;

    // Método Start, se ejecuta una vez al iniciar el juego
    void Start()
    {
        // No hay inicialización en este caso
    }

    // Método Update, se ejecuta una vez por frame
    void Update()
    {   
        // Si el jugador presiona la tecla Espacio, se instancia el proyectil 1 en la posición del jugador
        if (Input.GetKeyDown(KeyCode.Space)){
            Instantiate(proyectilPreFab, transform.position, proyectilPreFab.transform.rotation);
        }

        // Si el jugador presiona la tecla Enter, se instancia el proyectil 2 en la posición del jugador
        if (Input.GetKeyDown(KeyCode.Return)){
            /*
            proyectilPreFab → Es el objeto (Prefab) que se quiere instanciar. Debe estar asignado en el Inspector de Unity.
            transform.position → Es la posición en la que se generará el nuevo objeto, en este caso, la misma posición del jugador (transform.position).
            proyectilPreFab.transform.rotation → Se usa la rotación predeterminada del prefab para que el proyectil tenga la orientación correcta.
            */
            Instantiate(proyectil2PreFab, transform.position, proyectil2PreFab.transform.rotation);
        }

        // Evita que el jugador se salga por la izquierda del rango permitido
        if(transform.position.x < -rangoX){
            transform.position = new Vector3 (-rangoX, transform.position.y, transform.position.z);
        }

        // Evita que el jugador se salga por la derecha del rango permitido
        if (transform.position.x > rangoX) {
            transform.position = new Vector3 (rangoX, transform.position.y, transform.position.z);
        }
        
        // Captura el input del eje horizontal ("A" o "D" en teclado, o joystick en un gamepad)
        desplazamientoHorizontal = Input.GetAxis("Horizontal"); //Se usa Input.GetAxis("Horizontal") 
                                                                // para detectar la entrada del usuario 
                                                                // y mover al jugador en el eje X. multiplica por 
                                                                // 1 o menos 1 según se use una tecla u otra por 
                                                                // eso se usa right y no right y left o left sólo

        // Mueve el jugador horizontalmente basado en el input, la velocidad y el tiempo transcurrido
        transform.Translate(Vector3.right * desplazamientoHorizontal * Time.deltaTime * velocidad);
        /*
        transform.Translate(...)
        Este método mueve el objeto en función de un vector de desplazamiento.
        
        Vector3.right
        Es un vector unitario ((1, 0, 0)) que representa el eje X positivo en Unity.
        Mover en Vector3.right desplaza el objeto hacia la derecha y en -Vector3.right hacia la izquierda.
        
        desplazamientoHorizontal
        Es un valor que proviene de Input.GetAxis("Horizontal"), que puede ser:
        -1 cuando se presiona "A" (o la flecha izquierda) → Movimiento a la izquierda.
        1 cuando se presiona "D" (o la flecha derecha) → Movimiento a la derecha.
        0 si no hay entrada del jugador.
        
        Time.deltaTime
        Representa el tiempo transcurrido desde el último frame.
        Se usa para que el movimiento sea independiente de la velocidad de los fotogramas (frame rate).
        Sin Time.deltaTime, el objeto se movería a diferentes velocidades según el rendimiento del dispositivo.
        
        velocidad
        Es una variable pública que define qué tan rápido se mueve el objeto.
        */

        /*
        ¿Por qué se usa Time.deltaTime en transform.Translate() si ya estamos usando Input.GetAxis("Horizontal")? 
        es para que el movimiento sea consistente y no dependa de la velocidad de fotogramas (FPS).

        Input.GetAxis("Horizontal") devuelve un valor entre -1 y 1, dependiendo de la entrada del usuario:
        -1 → si presionas "A" o flecha izquierda (mover a la izquierda).
        1 → si presionas "D" o flecha derecha (mover a la derecha).
        Valores intermedios (0 a ±1) → si usas un joystick o presionas ligeramente una tecla.
        📌 Importante: Input.GetAxis() no tiene en cuenta el tiempo transcurrido entre frames.


        Time.deltaTime representa el tiempo transcurrido desde el último frame.
        En PCs rápidos con alto FPS, los frames se procesan más rápido, por lo que sin Time.deltaTime, el objeto se movería más rápido de lo esperado.
        En PCs lentos con bajo FPS, los frames se procesan más lento, y sin Time.deltaTime, el objeto se movería más despacio de lo esperado.
        ✅ Multiplicar por Time.deltaTime hace que el movimiento sea uniforme en todas las computadoras y dispositivos, sin importar el rendimiento.
        */
    }
}
