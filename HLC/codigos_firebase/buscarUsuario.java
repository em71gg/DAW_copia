package com.daw.iniciosesion;

import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import com.google.android.gms.tasks.OnFailureListener;
import com.google.android.gms.tasks.OnSuccessListener;
import com.google.firebase.Firebase;
import com.google.firebase.firestore.DocumentSnapshot;
import com.google.firebase.firestore.FirebaseFirestore;

public class BuscarUsuario extends AppCompatActivity {
    EditText id;
    TextView nombre, correo;
    FirebaseFirestore firestore;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_buscar_usuario);
        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main), (v, insets) -> {
            Insets systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars());
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom);
            return insets;
        });

        id = findViewById(R.id.id_usuario);
        correo= findViewById(R.id.correo_usuario);
        nombre = findViewById(R.id.nombre_usuario);
        firestore = FirebaseFirestore.getInstance();

    }
    public void obtenerUsuario(View view){
        String nombreBuscado = id.getText().toString().trim();
        Log.d("FirestoreDebug", "Nombre buscado: " + nombreBuscado);
        firestore.collection("usuario").whereEqualTo("nombre", nombreBuscado).get()
                .addOnSuccessListener(queryDocumentSnapshots -> {
                    if(!queryDocumentSnapshots.isEmpty()){
                        //Obtengo el primer docuemtno que coincida
                        DocumentSnapshot documentSnapshot = queryDocumentSnapshots.getDocuments().get(0);
                        String nombreUsuario = documentSnapshot.getString("nombre");
                        String correoUsuario = documentSnapshot.getString("correo");

                        Log.d("FirestoreDebug", "Usuario encontrado: " + nombreUsuario + ", correo: " + correoUsuario);
                        nombre.setText(nombreUsuario);
                        correo.setText(correoUsuario);


                    }

                    else {
                        Log.d("FirestoreDebug", "No se encontraron coincidencias");
                        Toast.makeText(BuscarUsuario.this, "No existe un usuario con ese nombre", Toast.LENGTH_SHORT).show();}
                })
                .addOnFailureListener(new OnFailureListener() {
                    @Override
                    public void onFailure(@NonNull Exception e) {
                        Log.e("FirestoreDebug", "Error al obtener el registro", e);
                        Toast.makeText(BuscarUsuario.this, "Error al obtener Registro" + e.getMessage(), Toast.LENGTH_SHORT).show();
                    }
                });
    }
}