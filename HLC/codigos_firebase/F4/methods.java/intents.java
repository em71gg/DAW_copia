public void registrarse(View view){
        startActivity(new Intent(InicioSesion.this, Registro.class));
    }


 public void buscarMercado (View view) {
        startActivity(new Intent(this, BuscarMercado.class));
    }


public void listarMercados (View view) {
startActivity(new Intent(this, ListarMercados.class));
    }

public void scrollMercados (View view) {
        startActivity(new Intent(this, ScrollMercados.class));
    }