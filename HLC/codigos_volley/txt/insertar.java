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