private void obtenerRegistros() {
firestore.collection("mercado")
        .orderBy("id")
        .get()
        .addOnCompleteListener(new OnCompleteListener<QuerySnapshot>() {
            @Override
            public void onComplete(Task<QuerySnapshot> task) {
                if (task.isSuccessful()) {
                    for (QueryDocumentSnapshot document : task.getResult()) {
                        // Obtener los datos de cada documento
                        String id = document.getString("id");
                        String nombre = document.getString("nombre");
                        String ubicacion = document.getString("ubicacion");
                        String inicio = document.getString("inicio");
                        String fin = document.getString("fin");

                        // Crear un TextView para mostrar la información del mercado
                        TextView mercadoView = new TextView(ScrollMercados.this);
                        mercadoView.setText(
                                "ID: " + id + "\n" +
                                        "Nombre: " + nombre + "\n" +
                                        "Ubicación: " + ubicacion + "\n" +
                                        "Fechas: " + inicio + " - " + fin
                        );
                        mercadoView.setPadding(16, 16, 16, 16);
                        //mercadoView.setBackgroundResource(android.R.drawable.dialog_holo_light_frame);

                        // Agregar el TextView al contenedor
                        scroll.addView(mercadoView);
                    }
                } else {
                    Toast.makeText(ScrollMercados.this, "Error al obtener los registros.", Toast.LENGTH_SHORT).show();
                }
            }
        });
    }