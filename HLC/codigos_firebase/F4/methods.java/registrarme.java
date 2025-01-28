  public void registrarme(View view){
        String nombreUsuario = nombre.getText().toString().trim();
        String correoUsuario = correo.getText().toString().trim();
        String passUsuario = pass.getText().toString().trim();

        if (nombreUsuario.isEmpty() || correoUsuario.isEmpty() || passUsuario.isEmpty()){
            Toast.makeText(this, "Complete todos los campos", Toast.LENGTH_SHORT).show();
        }else{
            registroUsuario(nombreUsuario, correoUsuario, passUsuario);
        }
    }