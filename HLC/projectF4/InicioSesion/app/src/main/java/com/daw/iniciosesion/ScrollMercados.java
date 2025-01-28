package com.daw.iniciosesion;

import android.os.Bundle;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.Task;
import com.google.firebase.firestore.FirebaseFirestore;
import com.google.firebase.firestore.QueryDocumentSnapshot;
import com.google.firebase.firestore.QuerySnapshot;

public class ScrollMercados extends AppCompatActivity {
    private LinearLayout scroll;
    FirebaseFirestore firestore;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_scroll_mercados);
        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main), (v, insets) -> {
            Insets systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars());
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom);
            return insets;
        });

        scroll = findViewById(R.id.scr_view);
        firestore = FirebaseFirestore.getInstance();
        obtenerRegistros();
    }

    private void obtenerRegistros() {
        firestore.collection("mercado")
                .orderBy("id")
                .get()
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

                                // Crear un TextView para mostrar la información del mercado
                                TextView mercadoView = new TextView(ScrollMercados.this);
                                mercadoView.setText(
                                        "ID: " + id + "\n" +
                                                "Nombre: " + nombre + "\n" +
                                                "Ubicación: " + ubicacion + "\n" +
                                                "Fechas: " + inicio + " - " + fin
                                );
                                mercadoView.setPadding(16, 16, 16, 16);
                                //mercadoView.setBackgroundResource(android.R.drawable.dialog_holo_light_frame);

                                // Agregar el TextView al contenedor
                                scroll.addView(mercadoView);
                            }
                        } else {
                            Toast.makeText(ScrollMercados.this, "Error al obtener los registros.", Toast.LENGTH_SHORT).show();
                        }
                    }
                });
    }

}