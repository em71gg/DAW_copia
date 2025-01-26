import android.os.Bundle;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.Task;
import com.google.firebase.firestore.FirebaseFirestore;
import com.google.firebase.firestore.QueryDocumentSnapshot;
import com.google.firebase.firestore.QuerySnapshot;

import java.util.ArrayList;

public class MainActivity extends AppCompatActivity {

    private ListView listView;
    private ArrayList<String> mercadosList; // Lista para almacenar los datos como cadenas de texto
    private ArrayAdapter<String> adapter;
    private FirebaseFirestore firestore;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        // Inicializar el ListView y Firestore
        listView = findViewById(R.id.listView);
        firestore = FirebaseFirestore.getInstance();
        
        // Crear lista vacía para almacenar los mercados como cadenas de texto
        mercadosList = new ArrayList<>();

        // Crear un adaptador para el ListView
        adapter = new ArrayAdapter<>(this, android.R.layout.simple_list_item_1, mercadosList);
        listView.setAdapter(adapter);

        // Llamar al método para obtener los registros
        obtenerRegistros();
    }

    // Método para obtener los registros de Firestore y mostrarlos en el ListView
    private void obtenerRegistros() {
        firestore.collection("mercado")
                .get()  // Obtener todos los documentos de la colección "mercado"
                .addOnCompleteListener(new OnCompleteListener<QuerySnapshot>() {
                    @Override
                    public void onComplete(Task<QuerySnapshot> task) {
                        if (task.isSuccessful()) {
                            for (QueryDocumentSnapshot document : task.getResult()) {
                                // Obtener los datos de cada documento
                                String id = document.getString("id");
                                String nombre = document.getString("nombre");
                                String ubicacion = document.getString("ubicacion");
                                String inicio = document.getString("inicio");
                                String fin = document.getString("fin");

                                // Crear una cadena que contenga los datos del mercado
                                String mercadoInfo = "ID: " + id + "\n" +
                                                      "Nombre: " + nombre + "\n" +
                                                      "Ubicación: " + ubicacion + "\n" +
                                                      "Horario: " + inicio + " - " + fin;

                                // Añadir la información a la lista
                                mercadosList.add(mercadoInfo);
                            }

                            // Notificar al adaptador que los datos han cambiado
                            adapter.notifyDataSetChanged();
                        } else {
                            Toast.makeText(MainActivity.this, "Error al obtener los registros.", Toast.LENGTH_SHORT).show();
                        }
                    }
                });
    }
}
