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
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class Buscar extends AppCompatActivity {

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
        setContentView(R.layout.activity_buscar);
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

        etId = findViewById(R.id.et1);
        etNombre = findViewById(R.id.et2);
        etUbicacion = findViewById(R.id.et3);
        etFechaIni = findViewById(R.id.et4);
        etFechaFin = findViewById(R.id.et5);
    }

    public void buscar(View view){
        JsonArrayRequest jsonArrayRequest = new JsonArrayRequest("http://10.0.2.2/hlcTarde/buscar.php?id=" + etId.getText().toString(), new Response.Listener<JSONArray>() {
            @Override
            public void onResponse(JSONArray response) {
                JSONObject jsonObject = null;
                for (int i = 0; i < response.length(); i++) {
                    try {
                        jsonObject = response.getJSONObject(i);
                        etNombre.setText(jsonObject.getString("nombre"));
                        etUbicacion.setText(jsonObject.getString("ubicacion"));
                        etFechaIni.setText(jsonObject.getString("fecha_ini"));
                        etFechaFin.setText(jsonObject.getString("fecha_fin"));
                    } catch (JSONException e) {
                        Toast.makeText(getApplicationContext(), e.getMessage(), Toast.LENGTH_SHORT).show();
                    }
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(getApplicationContext(), error.toString(), Toast.LENGTH_SHORT).show();
            }
        });
        requestQueue = Volley.newRequestQueue(this);
        requestQueue.add(jsonArrayRequest);
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