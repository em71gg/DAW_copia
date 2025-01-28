  @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_anadir);
        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main), (v, insets) -> {
            Insets systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars());
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom);
            return insets;
        });
        /*dentro del onCreate*/
        getSupportActionBar().show();
        if (getSupportActionBar() != null) {
            getSupportActionBar().setBackgroundDrawable(new ColorDrawable(Color.parseColor("red")));
            getSupportActionBar().setDisplayShowHomeEnabled(true);
            getSupportActionBar().setIcon(R.mipmap.ic_launcher);
        }

        /*etId = findViewById(R.id.edt1);
        etNombre = findViewById(R.id.edt2);
        etUbicacion = findViewById(R.id.edt3);
        etFechaIni = findViewById(R.id.edt4);
        etFechaFin = findViewById(R.id.edt5);*/
    }

    /*como métodos*/
     @Override
    public boolean onCreateOptionsMenu(Menu menu){
        getMenuInflater().inflate(R.menu.overflow, menu);
        return true;
    }
     @Override
    public boolean onOptionsItemSelected(MenuItem item){
        int id = item.getItemId();
        if(id == R.id.item1){
            Intent i = new Intent(this, Anadir.class);
            startActivity(i);
        }else if(id == R.id.item2){
            Intent i = new Intent(this, MostrarTodos.class);
            startActivity(i);
        }else if(id == R.id.item3){
            Intent i = new Intent(this, Buscar.class);
            startActivity(i);
        }
        return super.onOptionsItemSelected(item);
    }
