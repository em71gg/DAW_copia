package com.daw.iniciosesion;

import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.OnFailureListener;
import com.google.android.gms.tasks.Task;
import com.google.firebase.auth.AuthResult;
import com.google.firebase.auth.FirebaseAuth;

public class InicioSesion extends AppCompatActivity {

    EditText correo, pass;
    FirebaseAuth auth;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_inicio_sesion);
        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main), (v, insets) -> {
            Insets systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars());
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom);
            return insets;
        });

        getSupportActionBar().show();
        if (getSupportActionBar() != null) {
            getSupportActionBar().setBackgroundDrawable(new ColorDrawable(Color.parseColor("#E57373")));
            getSupportActionBar().setDisplayShowHomeEnabled(true);
            getSupportActionBar().setIcon(R.mipmap.ic_launcher);
        }

        correo = findViewById(R.id.correoInicio);
        pass = findViewById(R.id.passInicio);
        auth = FirebaseAuth.getInstance();
    }

    public void iniciarSesion(View view){
        String correoUsuario = correo.getText().toString().trim();
        String passUsuario = pass.getText().toString().trim();

        if(correoUsuario.isEmpty() || passUsuario.isEmpty()){
            Toast.makeText(this, "Ingrese los datos", Toast.LENGTH_SHORT).show();
        }else{
            inicioUsuario(correoUsuario,passUsuario);
        }
    }

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

    public void registrarse(View view){
        startActivity(new Intent(InicioSesion.this, Registro.class));
    }
}