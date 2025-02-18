using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class DestruirFueraEscena : MonoBehaviour
{
    float limiteSuperior = 34;
    float limiteInferior = -12;
    static float vidas = 3f; // Variable estática que representa la cantidad de vidas del jugador
    // Start is called before the first frame update
    void Start()
    {
        
    }

    // Update is called once per frame
    void Update()
    {   
        // Si el objeto sale de los límites superior o inferior, se destruye
        if(transform.position.z>limiteSuperior || transform.position.z < limiteInferior){
            Destroy(gameObject);

        }
        // Si el objeto cruza el límite superior, se resta una vida al jugador
        if (transform.position.z > limiteSuperior){
            vidas -= 1;
            Debug.Log("vidas restantes: " + vidas);
        }
         // Si las vidas llegan a 0, se muestra "GAME OVER" y se cierra el juego
        if(vidas == 0){
            Debug.Log("GAME OVER");
            gameOver();
        }

    }

    void gameOver() { // Método para finalizar el juego cuando las vidas llegan a 0
        // Cierra la aplicación (solo funciona en una compilación real, no en el editor)
        Application.Quit();
        // Si estamos en el editor de Unity, detiene la ejecución de la escena
        #if UNITY_EDITOR
        UnityEditor.EditorApplication.isPlaying = false;
        #endif
    }
}
