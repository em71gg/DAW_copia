using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class Avance : MonoBehaviour
{
    public float velocidad = 40.0f;
    private float tiempoTranscurrido = 0f;
    private float intervalo = 30f; // Intervalo de 30 segundos
    private float factorIncremento = 1.3f; // 30% de incremento

    void Update()
    {
        // Mover el objeto
        transform.Translate(Vector3.forward * Time.deltaTime * velocidad);

        // Actualizar el tiempo transcurrido
        tiempoTranscurrido += Time.deltaTime;

        // Si han pasado 30 segundos, aumentar la velocidad
        if (tiempoTranscurrido >= intervalo)
        {
            velocidad *= factorIncremento;
            tiempoTranscurrido = 0f; // Reiniciar el contador
        }
    }
}
