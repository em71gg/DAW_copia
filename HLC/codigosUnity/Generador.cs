using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class Generador : MonoBehaviour
{
    // Arreglo que almacena los prefabs de los animales a generar
    public GameObject[] animalesPrefab;

    // Rango lateral en el que se pueden generar los animales
    private float rangoXGenerador = 20f;

    // Distancia en el eje Z donde aparecerán los animales (cercanía al jugador)
    private float posZGenerador = 15f;

    // Tiempo de espera antes de que comience la generación de animales
    private float retardoInicial = 2.0f;

    // Intervalo de tiempo entre la aparición de cada animal
    private float intervaloGeneracion = 1.5f;

    void Start()
    {
        // Llama repetidamente al método "GenerarAnimalAleatorio" con un retraso inicial y luego en intervalos definidos
        InvokeRepeating("GenerarAnimalAleatorio", retardoInicial, intervaloGeneracion);
    }

    void Update()
    {
        /*
        // Si queremos generar animales manualmente al presionar una tecla
        if (Input.GetKeyDown(KeyCode.S)) // Se usa la tecla "S" para generar un nuevo animal en la escena
        { 
            GenerarAnimalAleatorio();
        }
        */
    }

    void GenerarAnimalAleatorio()
    {
        // Selecciona un animal aleatorio dentro del arreglo de prefabs
        int indexAnimal = Random.Range(0, animalesPrefab.Length);

        // Genera una posición aleatoria en el eje X dentro del rango permitido
        // La posición en Z es fija para controlar la cercanía al jugador
        Vector3 posicionGenerador = new Vector3(Random.Range(-rangoXGenerador, rangoXGenerador), 0, posZGenerador);

        // Instancia (crea) el animal en la posición generada con su rotación original
        Instantiate(animalesPrefab[indexAnimal], posicionGenerador, animalesPrefab[indexAnimal].transform.rotation);
    }
}
