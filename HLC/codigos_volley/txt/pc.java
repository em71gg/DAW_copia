package com.daw.productosvolley;

import android.os.Bundle;
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
import java.util.jar.JarException;

public class MainActivity extends AppCompatActivity {

    //atributo para realizar el crud a través de microservicio
    RequestQueue requestQueue;
    private EditText etCodigo, etPrecio, etNombre, etFabricante;


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
        etCodigo = findViewById(R.id.et_codigo);
        etFabricante = findViewById(R.id.et_fabricante);
        etNombre = findViewById(R.id.et_producto);
        etPrecio = findViewById(R.id.et_precio);
    }
    //el microservicio es una llamada a un recurso externo
    public void insertar(View view){
        //necwsitamos un objeto string request que lanzará la peticion
        StringRequest stringRequest = new StringRequest(Request.Method.POST, "http://10.0.2.2/registroMyAdmyn/insertar.php", new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {//ha respùesto correctamente lo que le hempos pedido
                Toast.makeText(getApplicationContext(), "Operación exitosa", Toast.LENGTH_SHORT).show();//GETAPLICATION CONTERXT NOTA CUANDO EL ONRESPONSE HABLA
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(getApplicationContext(), error.toString(), Toast.LENGTH_SHORT).show();
            }
        }){
            @Override
            protected Map<String,String> getParams() throws AuthFailureError{//mapa porque tenemos que construir pares de clave valor. Toto qlo que se ha hecho hasta ahora es para enviar los parametros .put por el microservicio
                Map<String,String> parametros = new HashMap<String,String>();

                parametros.put("codigo", etCodigo.getText().toString());//aqui se comunica con el código del post//KE ESW LA CLAVE DEL MICROSERVICIO Y VALOR LO QUE RECOJO DE MI PARTE GRÁFICA
                parametros.put("nombre", etNombre.getText().toString());
                parametros.put("precio", etPrecio.getText().toString());
                parametros.put("fabricante", etFabricante.getText().toString());



                return parametros;
            }
        };
        requestQueue = Volley.newRequestQueue(this);//eSTA ES LA COLA DE APLICACIONES esta creada pero falta añadir algo
        requestQueue.add(stringRequest);// LO que faltaba es el string request
    }

    public void buscar(View view){
        JsonArrayRequest jsonArrayRequest = new JsonArrayRequest("http://10.0.2.2/registroMyAdmyn/buscar.php?codigo=" + etCodigo.getText().toString(), new Response.Listener<JSONArray>() {
            @Override
            public void onResponse(JSONArray response) { //la response está llena de json objects. autoimaticamente me mete3 la direccion de mi JsopnArrayRequest
                JSONObject jsonObject = null;
                for (int i = 0; i < response.length(); i++) {//por lo anterior aquí tenog un length
                    try {
                        jsonObject = response.getJSONObject(i);
                        etNombre.setText(jsonObject.getString("nombre"));
                        etPrecio.setText(jsonObject.getString("precio"));
                        etFabricante.setText(jsonObject.getString("fabricante"));
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
        requestQueue =Volley.newRequestQueue(this);
        requestQueue.add(jsonArrayRequest);

    }
    public void editar(View view){
        StringRequest stringRequest = new StringRequest(Request.Method.POST, "http://10.0.2.2/registroMyAdmyn/editar.php", new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                Toast.makeText(getApplicationContext(), "Producto Editado", Toast.LENGTH_SHORT).show();
                limpiarFormulario();
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(getApplicationContext(), error.toString(), Toast.LENGTH_SHORT).show();

            }
        }){
            @Override
            protected Map<String, String> getParams() throws AuthFailureError{
                Map<String, String> parametros = new HashMap<String, String>();
                parametros.put("codigo", etCodigo.getText().toString());
                parametros.put("nombre", etNombre.getText().toString());
                parametros.put("precio" ,etPrecio.getText().toString());
                parametros.put("fabricante", etFabricante.getText().toString());

                return parametros;
            }
        };

        requestQueue = Volley.newRequestQueue(this);
        requestQueue.add(stringRequest);


    }

    public void eliminar(View view){
        StringRequest stringRequest = new StringRequest(Request.Method.POST, "http://10.0.2.2/registroMyAdmyn/eliminar.php", new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                Toast.makeText(getApplicationContext(), "Eliminación realizada", Toast.LENGTH_SHORT).show();
                limpiarFormulario();
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(getApplicationContext(), error.toString(), Toast.LENGTH_SHORT).show();
            }
        }){
            @Override
            protected Map<String, String> getParams() throws AuthFailureError{
                Map<String, String> parametros = new HashMap<String, String>();
                parametros.put("codigo", etCodigo.getText().toString());

                return parametros;

            }
        };
        requestQueue = Volley.newRequestQueue(this);
        requestQueue.add(stringRequest);
    }

    private void limpiarFormulario(){
        etCodigo.setText("");
        etNombre.setText("");
        etPrecio.setText("");
        etFabricante.setText("");
    }
}