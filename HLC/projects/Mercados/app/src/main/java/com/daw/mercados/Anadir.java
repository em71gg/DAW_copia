package com.daw.mercados;

import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.EditText;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import java.util.HashMap;
import java.util.Map;

public class Anadir extends AppCompatActivity {

    RequestQueue requestQueue;
    EditText etId;
    EditText etNombre;
    EditText etUbicacion;
    EditText etFechaIni;
    EditText etFechaFin;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_anadir);
        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main), (v, insets) -> {
            Insets systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars());
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom);
            return insets;
        });
        getSupportActionBar().show();
        if (getSupportActionBar() != null) {
            getSupportActionBar().setBackgroundDrawable(new ColorDrawable(Color.parseColor("red")));
            getSupportActionBar().setDisplayShowHomeEnabled(true);
            getSupportActionBar().setIcon(R.mipmap.ic_launcher);
        }

        etId = findViewById(R.id.edt1);
        etNombre = findViewById(R.id.edt2);
        etUbicacion = findViewById(R.id.edt3);
        etFechaIni = findViewById(R.id.edt4);
        etFechaFin = findViewById(R.id.edt5);
    }

    public void insertar(View view){
        StringRequest stringRequest = new StringRequest(Request.Method.POST, "http://10.0.2.2/hlcTardeExamen/insertar.php", new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                Toast.makeText(getApplicationContext(), "Operacion existosa", Toast.LENGTH_SHORT).show();

            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(getApplicationContext(), error.toString(), Toast.LENGTH_SHORT).show();
            }
        }){
            @Override
            protected Map<String,String> getParams() throws AuthFailureError {
                Map<String, String> parametros = new HashMap<String, String>();
                parametros.put("id", etId.getText().toString());
                parametros.put("nombre", etNombre.getText().toString());
                parametros.put("ubicacion", etUbicacion.getText().toString());
                parametros.put("fecha_ini", etFechaIni.getText().toString());
                parametros.put("fecha_fin", etFechaFin.getText().toString());
                return parametros;
            }
        };
        requestQueue = Volley.newRequestQueue(this);
        requestQueue.add(stringRequest);
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu){
        getMenuInflater().inflate(R.menu.overflow, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item){
        int id = item.getItemId();
        if(id == R.id.item1){
            Intent i = new Intent(this, Anadir.class);
            startActivity(i);
        }else if(id == R.id.item2){
            Intent i = new Intent(this, MostrarTodos.class);
            startActivity(i);
        }else if(id == R.id.item3){
            Intent i = new Intent(this, Buscar.class);
            startActivity(i);
        }
        return super.onOptionsItemSelected(item);
    }


}