package com.daw.iniciosesion;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.OnFailureListener;
import com.google.android.gms.tasks.Task;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.firestore.DocumentReference;
import com.google.firebase.firestore.FirebaseFirestore;
import com.google.firebase.ktx.Firebase;

import java.util.HashMap;
import java.util.Map;

public class MainActivity extends AppCompatActivity {
    FirebaseAuth auth;
    EditText id, nombre, ubicacion, inicio, fin;
    FirebaseFirestore firestore;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_main);
        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main), (v, insets) -> {
            Insets systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars());
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom);
            return insets;
        });

        auth = FirebaseAuth.getInstance(); //le estamos dando un valor al atributo
        firestore = FirebaseFirestore.getInstance();
        id = findViewById(R.id.idMercado);
        nombre = findViewById(R.id.nombreMercado);
        ubicacion = findViewById(R.id.ubiMercado);
        inicio = findViewById(R.id.inicioMercado);
        fin = findViewById(R.id.finMercado);
    }
    public void cerrarSesion (View view) {
        auth.signOut(); // Con esto hemos cerrado la sesión. Pero nos faltaría redirigir a inicio.
        finish();
        startActivity(new Intent(this, InicioSesion.class));
    }

    public void insertarMercado (View view) {
        String idMercado = id.getText().toString().trim();
        String nombreMercado = nombre.getText().toString().trim();
        String ubicacionMercado = ubicacion.getText().toString().trim();
        String inicioMercado = inicio.getText().toString().trim();
        String finMercado = fin.getText().toString().trim();

        if (idMercado.isEmpty() || nombreMercado.isEmpty() || ubicacionMercado.isEmpty()
         || inicioMercado.isEmpty() || finMercado.isEmpty()){
            Toast.makeText(this, "Complete todos los campos", Toast.LENGTH_SHORT).show();
        }else{
             agregarMercado(idMercado, nombreMercado, ubicacionMercado, inicioMercado, finMercado);
        }
    }

    private void agregarMercado(String idMercado, String nombreMercado, String ubicacionMercado, String inicioMercado, String finMercado) {
        Map<String, Object> map = new HashMap<>();
        map.put("id", idMercado);
        map.put("nombre", nombreMercado);
        map.put("ubicacion", ubicacionMercado);
        map.put("inicio", inicioMercado);
        map.put("fin", finMercado);

        firestore.collection("mercado").add(map).addOnCompleteListener(new OnCompleteListener<DocumentReference>() {
            @Override
            public void onComplete(@NonNull Task<DocumentReference> task) {
                Toast.makeText(MainActivity.this, "Mercado Registrado", Toast.LENGTH_SHORT).show();
            }
        }).addOnFailureListener(new OnFailureListener() {
            @Override
            public void onFailure(@NonNull Exception e) {
                Toast.makeText(MainActivity.this, "Error al insertar", Toast.LENGTH_SHORT).show();
            }
        });
    }
    public void buscarMercado (View view) {
        startActivity(new Intent(this, BuscarMercado.class));
    }

}