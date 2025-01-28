public void editarMercado (View view) {
        String idMercado = mdo_id.getText().toString().trim();
        String nombreMercado = mdo_name.getText().toString().trim();
        String ubicacionMercado = mdo_place.getText().toString().trim();
        String inicioMercado = mdo_ini.getText().toString().trim();
        String finMercado = mdo_fin.getText().toString().trim();
        if (idMercado.isEmpty() || nombreMercado.isEmpty() || ubicacionMercado.isEmpty()
                || inicioMercado.isEmpty() || finMercado.isEmpty()){
            Toast.makeText(this, "Complete todos los campos", Toast.LENGTH_SHORT).show();
        }else{
            actualizarMercado(idMercado, nombreMercado, ubicacionMercado, inicioMercado, finMercado);
        }

    }