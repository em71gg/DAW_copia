package com.daw.productossqlitetarde;

import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.os.Bundle;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import java.util.ArrayList;

public class ListarProductos extends AppCompatActivity {

    private ListView lv1;
    private ArrayList<String> listaProductos;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_listar_productos);
        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main), (v, insets) -> {
            Insets systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars());
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom);
            return insets;
        });

        lv1 = findViewById(R.id.lv1);
        listaProductos = new ArrayList<>();

        cargarProductos();
    }

    private void cargarProductos(){
        AdminSQLiteOpenHelper admin = new AdminSQLiteOpenHelper(this, "administracion", null, 1);
        SQLiteDatabase baseDeDatos = admin.getReadableDatabase();

        Cursor cursor = baseDeDatos.rawQuery("SELECT descripcion FROM articulos", null);
        if(cursor.moveToFirst()){
            do{
                listaProductos.add(cursor.getString(0));
            }while(cursor.moveToNext());

            ArrayAdapter<String> adapter = new ArrayAdapter<>(this, android.R.layout.simple_list_item_1, listaProductos);
            lv1.setAdapter(adapter);
        }else{
            Toast.makeText(this, "No hay productos", Toast.LENGTH_SHORT).show();
        }

        cursor.close();
        baseDeDatos.close();
    }
}