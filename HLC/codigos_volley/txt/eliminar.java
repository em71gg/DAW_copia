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