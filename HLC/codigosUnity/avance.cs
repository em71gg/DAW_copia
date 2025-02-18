using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class avance : MonoBehaviour
{
    /*
    Declara una variable pública velocidad que determina la rapidez con la que se mueve el objeto.
    Es pública, lo que permite modificarla desde el Inspector de Unity sin necesidad de cambiar el código.
    */
   public float velocidad = 40.0f; //cada segundo avanzará 40 unidades

    // Start is called before the first frame update
    void Start()
    {
        
    }

    // Update is called once per frame por lo que el código dentro de esta función se ejecuta de forma continua.
    void Update()
    {
        /*
        por lo qutransform.Translate(...) mueve el objeto en la dirección especificada.

        Vector3.forward
        Es un vector unitario ((0, 0, 1)) que representa el eje Z positivo en Unity.
        Esto significa que el objeto se moverá hacia adelante en la dirección de su orientación.
        
        Time.deltaTime
        Es el tiempo transcurrido desde el último frame.
        Hace que el movimiento sea independiente del framerate (para que no sea más rápido en PCs con FPS altos).

        💡 Regla general: Siempre que hagas movimientos en Update(), usa Time.deltaTime para que el comportamiento 
        del juego sea fluido y uniforme en cualquier hardware. 🚀
        
        velocidad
        Es un multiplicador que determina qué tan rápido se mueve el objeto.
        */
        transform.Translate(Vector3.forward * Time.deltaTime *velocidad);
    }
}
