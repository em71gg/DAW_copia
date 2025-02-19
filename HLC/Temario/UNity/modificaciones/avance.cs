using System.Collections;
using UnityEngine;

public class AvancePizza : MonoBehaviour
{
    public float velocidad = 40.0f;
    public float incrementoVelocidad = 5.0f;
    public float intervaloAumento = 10.0f;

    void Start()
    {
        StartCoroutine(AumentarVelocidad());
    }

    void Update()
    {
        transform.Translate(Vector3.forward * Time.deltaTime * velocidad);
    }

    IEnumerator AumentarVelocidad()
    {
        while (true)
        {
            yield return new WaitForSeconds(intervaloAumento);
            velocidad += incrementoVelocidad;
            Debug.Log("Nueva velocidad: " + velocidad);
        }
    }
}
