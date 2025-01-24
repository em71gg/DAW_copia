public void listar(View view) {
    private listview ltv;//esto iria en el oncreate
    ltv = findViewById(R.id.lv_productos);//tb

    JsonArrayRequest jsonArrayRequest = new JsonArrayRequest("http://10.0.2.2/registroMyAdmyn/listar.php";, new Response.Listener<JSONArray>() {
        @Override
        public void onResponse(JSONArray response) {
            ArrayList<String> productos = new ArrayList<>();

            for (int i = 0; i < response.length(); i++) {
                try {
                    JSONObject jsonObject = response.getJSONObject(i);
                    String codigo = jsonObject.getString("codigo");
                    String nombre = jsonObject.getString("nombre");
                    String precio = jsonObject.getString("precio");
                    String fabricante = jsonObject.getString("fabricante");

                    // Formatear cada producto como una línea
                    productos.add("Código: " + codigo + "\n" +
                                  "Nombre: " + nombre + "\n" +
                                  "Precio: " + precio + "\n" +
                                  "Fabricante: " + fabricante);
                } catch (JSONException e) {
                    Toast.makeText(getApplicationContext(), e.getMessage(), Toast.LENGTH_SHORT).show();
                }
            }

            // Crear y asignar el adaptador al ListView en  el main fuera del oncrate
            ArrayAdapter<String> adapter = new ArrayAdapter<>(this, R.layout.layout_mio, productos);
            ltv.setAdapter(adapter);
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
