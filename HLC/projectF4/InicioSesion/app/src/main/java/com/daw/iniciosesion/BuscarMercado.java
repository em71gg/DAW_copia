package com.daw.iniciosesion;

import android.content.Intent;
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

import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.OnFailureListener;
import com.google.android.gms.tasks.OnSuccessListener;
import com.google.android.gms.tasks.Task;
import com.google.firebase.firestore.DocumentSnapshot;
import com.google.firebase.firestore.FirebaseFirestore;
import com.google.firebase.firestore.QuerySnapshot;

import java.util.HashMap;
import java.util.Map;

public class BuscarMercado extends AppCompatActivity {
    EditText mdo_id;
    TextView mdo_name, mdo_place, mdo_ini, mdo_fin;
    FirebaseFirestore firestore;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_buscar_mercado);
        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main), (v, insets) -> {
            Insets systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars());
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom);
            return insets;
        });
        mdo_id = findViewById(R.id.insert_id_mdo);
        mdo_name = findViewById(R.id.view_name_mdo);
        mdo_place = findViewById(R.id.view_ub_mdo);
        mdo_ini = findViewById(R.id.view_inicio_mdo);
        mdo_fin = findViewById(R.id.view_fin_mdo);
        firestore = FirebaseFirestore.getInstance();
    }
    public void obtenerMercado(View view){
        String mdoBuscado = mdo_id.getText().toString().trim();

        firestore.collection("mercado").whereEqualTo("id", mdoBuscado).get()
                .addOnSuccessListener(queryDocumentSnapshots -> {
                    if(!queryDocumentSnapshots.isEmpty()){
                        //Obtengo el primer docuemtno que coincida
                        DocumentSnapshot documentSnapshot = queryDocumentSnapshots.getDocuments().get(0);
                        String nombreMdo = documentSnapshot.getString("nombre");
                        String ubiMdo = documentSnapshot.getString("ubicacion");
                        String iniMdo = documentSnapshot.getString("inicio");
                        String finMdo = documentSnapshot.getString("fin");


                        mdo_name.setText(nombreMdo);
                        mdo_place.setText(ubiMdo);
                        mdo_ini.setText(iniMdo);
                        mdo_fin.setText(finMdo);


                    }

                    else {

                        Toast.makeText(this, "No existe ese mercado", Toast.LENGTH_SHORT).show();}
                })
                .addOnFailureListener(new OnFailureListener() {
                    @Override
                    public void onFailure(@NonNull Exception e) {

                        Toast.makeText(BuscarMercado.this, "Error al obtener Registro" + e.getMessage(), Toast.LENGTH_SHORT).show();
                    }
                });
    }
    public void limpiarEntrada (View view){
        mdo_id.setText("");
        mdo_name.setText("");
        mdo_place.setText("");
        mdo_ini.setText("");
        mdo_fin.setText("");
    }
    public void listarMercados (View view) {
        startActivity(new Intent(this, ListarMercados.class));
    }
    public void editarMercado (View view) {
        String idMercado = mdo_id.getText().toString().trim();
        String nombreMercado = mdo_name.getText().toString().trim();
        String ubicacionMercado = mdo_place.getText().toString().trim();
        String inicioMercado = mdo_ini.getText().toString().trim();
        String finMercado = mdo_fin.getText().toString().trim();
        if (idMercado.isEmpty() || nombreMercado.isEmpty() || ubicacionMercado.isEmpty()
                || inicioMercado.isEmpty() || finMercado.isEmpty()){
            Toast.makeText(this, "Complete todos los campos", Toast.LENGTH_SHORT).show();
        }else{
            actualizarMercado(idMercado, nombreMercado, ubicacionMercado, inicioMercado, finMercado);
        }

    }
    private void actualizarMercado(String idMercado, String nombreMercado, String ubicacionMercado, String inicioMercado, String finMercado) {
        firestore.collection("mercado").whereEqualTo("id", idMercado).get()
                .addOnSuccessListener(queryDocumentSnapshots -> {
                    if (!queryDocumentSnapshots.isEmpty()) {
                        // Obtén el primer documento que coincida
                        DocumentSnapshot documentSnapshot = queryDocumentSnapshots.getDocuments().get(0);
                        String docId = documentSnapshot.getId(); // ID del documento en Firestore

                        // Crea el mapa con los datos a actualizar
                        Map<String, Object> map = new HashMap<>();
                        map.put("id", idMercado);
                        map.put("nombre", nombreMercado);
                        map.put("ubicacion", ubicacionMercado);
                        map.put("inicio", inicioMercado);
                        map.put("fin", finMercado);

                        // Actualiza el documento utilizando su ID
                        firestore.collection("mercado").document(docId).update(map)
                                .addOnCompleteListener(task -> {
                                    if (task.isSuccessful()) {
                                        Toast.makeText(BuscarMercado.this, "Mercado Actualizado", Toast.LENGTH_SHORT).show();
                                    } else {
                                        Toast.makeText(BuscarMercado.this, "Error al actualizar el mercado", Toast.LENGTH_SHORT).show();
                                    }
                                })
                                .addOnFailureListener(e -> {
                                    Toast.makeText(BuscarMercado.this, "Error al actualizar: " + e.getMessage(), Toast.LENGTH_LONG).show();
                                });
                    } else {
                        Toast.makeText(BuscarMercado.this, "No se encontró el mercado con el ID especificado", Toast.LENGTH_SHORT).show();
                    }
                })
                .addOnFailureListener(e -> {
                    Toast.makeText(BuscarMercado.this, "Error al buscar el mercado: " + e.getMessage(), Toast.LENGTH_LONG).show();
                });
    }
    public void borrarMercado (View view) {
        String idMercado = mdo_id.getText().toString().trim();

        firestore.collection("mercado").whereEqualTo("id", idMercado).get()
                .addOnSuccessListener(new OnSuccessListener<QuerySnapshot>() {
                    @Override
                    public void onSuccess(QuerySnapshot queryDocumentSnapshots) {
                        if (!queryDocumentSnapshots.isEmpty()) {
                            // Obtén el primer documento que coincida
                            DocumentSnapshot documentSnapshot = queryDocumentSnapshots.getDocuments().get(0);
                            String docId = documentSnapshot.getId(); // ID del documento en Firestore
                            firestore.collection("mercado").document(docId).delete()
                                    .addOnCompleteListener(new OnCompleteListener<Void>() {
                                        @Override
                                        public void onComplete(@NonNull Task<Void> task) {
                                            Toast.makeText(BuscarMercado.this, "Mercado eliminado", Toast.LENGTH_SHORT).show();
                                            limpiarEntrada(view);
                                        }

                                    });
                        }
                        else {
                            Toast.makeText(BuscarMercado.this, "Error al eliminar ", Toast.LENGTH_SHORT).show();
                        }
                    }
                })
                .addOnFailureListener(new OnFailureListener() {
                    @Override
                    public void onFailure(@NonNull Exception e) {
                        Toast.makeText(BuscarMercado.this, "Error no se ha encontrado el mercado pedido para borrar" + e.getMessage(), Toast.LENGTH_SHORT).show();
                    }
                });
    }
    public void scrollMercados (View view) {
        startActivity(new Intent(this, ScrollMercados.class));
    }
}


