 public void iniciarSesion(View view){
        String correoUsuario = correo.getText().toString().trim();
        String passUsuario = pass.getText().toString().trim();

        if(correoUsuario.isEmpty() || passUsuario.isEmpty()){
            Toast.makeText(this, "Ingrese los datos", Toast.LENGTH_SHORT).show();
        }else{
            inicioUsuario(correoUsuario,passUsuario);
        }
    }
