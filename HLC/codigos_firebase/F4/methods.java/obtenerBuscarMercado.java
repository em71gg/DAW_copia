public void obtenerMercado(View view){
        String mdoBuscado = mdo_id.getText().toString().trim();

        firestore.collection("mercado").whereEqualTo("id", mdoBuscado).get()
                .addOnSuccessListener(queryDocumentSnapshots -> {
                    if(!queryDocumentSnapshots.isEmpty()){
                        //Obtengo el primer docuemtno que coincida
                        DocumentSnapshot documentSnapshot = queryDocumentSnapshots.getDocuments().get(0);
                        String nombreMdo = documentSnapshot.getString("nombre");
                        String ubiMdo = documentSnapshot.getString("ubicacion");
                        String iniMdo = documentSnapshot.getString("inicio");
                        String finMdo = documentSnapshot.getString("fin");

                        mdo_name.setText(nombreMdo);
                        mdo_place.setText(ubiMdo);
                        mdo_ini.setText(iniMdo);
                        mdo_fin.setText(finMdo);
                    }
                    else {

                        Toast.makeText(this, "No existe ese mercado", Toast.LENGTH_SHORT).show();}
                })
                .addOnFailureListener(new OnFailureListener() {
                    @Override
                    public void onFailure(@NonNull Exception e) {

                        Toast.makeText(BuscarMercado.this, "Error al obtener Registro" + e.getMessage(), Toast.LENGTH_SHORT).show();
                    }
                });
    }