 private void agregarMercado(String idMercado, String nombreMercado, String ubicacionMercado, String inicioMercado, String finMercado) {
        Map<String, Object> map = new HashMap<>();
        map.put("id", idMercado);
        map.put("nombre", nombreMercado);
        map.put("ubicacion", ubicacionMercado);
        map.put("inicio", inicioMercado);
        map.put("fin", finMercado);

        firestore.collection("mercado").add(map).addOnCompleteListener(new OnCompleteListener<DocumentReference>() {
            @Override
            public void onComplete(@NonNull Task<DocumentReference> task) {
                Toast.makeText(MainActivity.this, "Mercado Registrado", Toast.LENGTH_SHORT).show();
            }
        }).addOnFailureListener(new OnFailureListener() {
            @Override
            public void onFailure(@NonNull Exception e) {
                Toast.makeText(MainActivity.this, "Error al insertar", Toast.LENGTH_SHORT).show();
            }
        });
}