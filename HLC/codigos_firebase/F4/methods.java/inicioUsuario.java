private void inicioUsuario(String correoUsuario, String passUsuario) {
        auth.signInWithEmailAndPassword(correoUsuario, passUsuario).addOnCompleteListener(new OnCompleteListener<AuthResult>() {
            @Override
            public void onComplete(@NonNull Task<AuthResult> task) {
                if(task.isSuccessful()){
                    finish();
                    startActivity(new Intent(InicioSesion.this, MainActivity.class));
                    Toast.makeText(InicioSesion.this, "Bienvenido/a", Toast.LENGTH_SHORT).show();
                }else{
                    Toast.makeText(InicioSesion.this, "Error", Toast.LENGTH_SHORT).show();
                }
            }
        }).addOnFailureListener(new OnFailureListener() {
            @Override
            public void onFailure(@NonNull Exception e) {
                Toast.makeText(InicioSesion.this, "Error al iniciar sesion", Toast.LENGTH_SHORT).show();
            }
        });
    }