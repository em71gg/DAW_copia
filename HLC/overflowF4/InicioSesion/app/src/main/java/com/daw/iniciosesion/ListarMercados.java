package com.daw.iniciosesion;

import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.Task;
import com.google.firebase.firestore.FirebaseFirestore;
import com.google.firebase.firestore.QueryDocumentSnapshot;
import com.google.firebase.firestore.QuerySnapshot;

import java.util.ArrayList;

public class ListarMercados extends AppCompatActivity {
    private ListView listView;
    private ArrayList<String> mdosList;
    private ArrayAdapter<String> adapter;
    FirebaseFirestore firestore;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_listar_mercados);
        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main), (v, insets) -> {
            Insets systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars());
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom);
            return insets;
        });
        getSupportActionBar().show();
        if (getSupportActionBar() != null) {
            getSupportActionBar().setBackgroundDrawable(new ColorDrawable(Color.parseColor("#E57373")));
            getSupportActionBar().setDisplayShowHomeEnabled(true);
            getSupportActionBar().setIcon(R.mipmap.ic_launcher);
        }

        listView = findViewById(R.id.listView);
        firestore = FirebaseFirestore.getInstance();
        mdosList = new ArrayList<>();
        adapter = new ArrayAdapter<>(this, android.R.layout.simple_list_item_1, mdosList);
        listView.setAdapter(adapter);
        obtenerRegistros();
    }
    private void obtenerRegistros() {
        firestore.collection("mercado")
                .orderBy("id")
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
                                        "Fechas: " + inicio + " - " + fin;

                                // Añadir la información a la lista
                                mdosList.add(mercadoInfo);
                            }

                            // Notificar al adaptador que los datos han cambiado
                            adapter.notifyDataSetChanged();
                        } else {
                            Toast.makeText(ListarMercados.this, "Error al obtener los registros.", Toast.LENGTH_SHORT).show();
                        }
                    }
                });
    }
    @Override
    public boolean onCreateOptionsMenu(Menu menu){
        getMenuInflater().inflate(R.menu.overflow, menu);
        return true;
    }
    @Override
    public boolean onOptionsItemSelected(MenuItem item){
        int id = item.getItemId();
        if(id == R.id.irPpal){
            Intent i = new Intent(this, MainActivity.class);
            startActivity(i);
        }else if(id == R.id.buscMdo){
            Intent i = new Intent(this, BuscarMercado.class);
            startActivity(i);
        }else if(id == R.id.scrMdo){
            Intent i = new Intent(this, ScrollMercados.class);
            startActivity(i);
        }
        return super.onOptionsItemSelected(item);
    }
}