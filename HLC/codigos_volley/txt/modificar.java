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