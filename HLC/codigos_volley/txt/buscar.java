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