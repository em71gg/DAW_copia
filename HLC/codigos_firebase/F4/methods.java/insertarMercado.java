public void insertarMercado (View view) {
        String idMercado = id.getText().toString().trim();
        String nombreMercado = nombre.getText().toString().trim();
        String ubicacionMercado = ubicacion.getText().toString().trim();
        String inicioMercado = inicio.getText().toString().trim();
        String finMercado = fin.getText().toString().trim();

        if (idMercado.isEmpty() || nombreMercado.isEmpty() || ubicacionMercado.isEmpty()
         || inicioMercado.isEmpty() || finMercado.isEmpty()){
            Toast.makeText(this, "Complete todos los campos", Toast.LENGTH_SHORT).show();
        }
        else{
             agregarMercado(idMercado, nombreMercado, ubicacionMercado, inicioMercado, finMercado);
        }
}