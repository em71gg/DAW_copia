using System.Collections;
using UnityEngine;

public class AvancePizza : MonoBehaviour
{
    public float velocidad = 40.0f;
    public float incrementoVelocidad = 5.0f;
    public float intervaloAumento = 10.0f;
    private float tiempoTranscurrido = 0.0f;

    void Update()
    {
        transform.Translate(Vector3.forward * Time.deltaTime * velocidad);
        
        tiempoTranscurrido += Time.deltaTime;
        if (tiempoTranscurrido >= intervaloAumento)
        {
            velocidad += incrementoVelocidad;
            tiempoTranscurrido = 0.0f;
            Debug.Log("Nueva velocidad: " + velocidad);
        }
    }
}