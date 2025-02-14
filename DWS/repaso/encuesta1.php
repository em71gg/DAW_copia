<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        main {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        fieldset {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        fieldset>div {
            display: flex;
            flex-direction: row;
            gap: 2rem;
        }

        .radio {
            display: block;
        }
    </style>
</head>

<body>
    <header></header>
    <main>
        <div>
            <form action="./encuesta2.php" method="post">

                <fieldset>
                    <legend>Encuesta formulario</legend>
                    <p>Escriba el numero de preguntas (1<= numero<=10) y respuestas (2<=numero<=10) y mostraré una encuesta ficticia</p>


                            <div>

                                <!--inputs texts-->
                                <label for="preguntas">Número de preguntas</label>
                                <input type="number" name="preguntas" id="preguntas">
                                <label for="respuestas">Número de respuestas</label>
                                <input type="number" name="respuestas" id="respuestas">
                            </div>
                            <div>
                                <input type="submit" name="enviar" value="Mostrar">
                                <input type="reset" name="borrar" value="Borrar">
                            </div>
                        </fieldset>
             </form>
        </div>

        

    </main>
</body>

</html>