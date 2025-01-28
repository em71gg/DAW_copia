 public void cerrarSesion (View view) {
        auth.signOut(); // Con esto hemos cerrado la sesión. Pero nos faltaría redirigir a inicio.
        finish();
        startActivity(new Intent(this, InicioSesion.class));
    }