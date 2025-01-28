 private void registroUsuario(String nombreUsuario, String correoUsuario, String passUsuario) {
        auth.createUserWithEmailAndPassword(correoUsuario, passUsuario).addOnCompleteListener(new OnCompleteListener<AuthResult>() {
            @Override
            public void onComplete(@NonNull Task<AuthResult> task) {
                String id = auth.getCurrentUser().getUid();
                Map<String, Object> map = new HashMap<>();
                map.put("id", id);
                map.put("nombre", nombreUsuario);
                map.put("correo", correoUsuario);
                map.put("pass", passUsuario);

                firestore.collection("usuario").document(id).set(map).addOnSuccessListener(new OnSuccessListener<Void>() {
                    @Override
                    public void onSuccess(Void unused) {
                        finish();
                        startActivity(new Intent(Registro.this, MainActivity.class));
                        Toast.makeText(Registro.this, "Usuario registrado", Toast.LENGTH_SHORT).show();
                    }
                }).addOnFailureListener(new OnFailureListener() {
                    @Override
                    public void onFailure(@NonNull Exception e) {
                        Toast.makeText(Registro.this, "Error al guardar", Toast.LENGTH_SHORT).show();
                    }
                });
            }
        }).addOnFailureListener(new OnFailureListener() {
            @Override
            public void onFailure(@NonNull Exception e) {
                Toast.makeText(Registro.this, "Error al registrar", Toast.LENGTH_SHORT).show();
            }
        });
    }