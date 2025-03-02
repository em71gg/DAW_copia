
                                <label
                                    for="categoria"
                                    class="block text-sm font-bold ml-1 mb-2 dark:text-white">Categoría de la actividad</label>
                                <div class="relative">
                                    <select
                                        id="categoria"
                                        name="categoria"
                                        class="py-3 px-4 block w-full border-2 border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                        aria-describedby="email-error"
                                        >
                                        <?php
                                            $getCategorias = ('SELECT
                                            id,
                                            categoria
                                            FROM catagorias');
                                            
                                            $rdoGetCategorias = resultadoConsulta($conexion, $getCategorias);

                                            while ($row = $rdoGetCategorias -> fetch(PDO::FETCH_ASSOC)):
                                        ?>
                                        <option value="<?php echo $row['id'] ?>" <?php ?>><?php echo $row['categoria']?></option>
                                        <?php
                                            endwhile;
                                        ?>
                                    </select>
                                </div>

                            

                                <?php
        $conexion = conectarPDO($host, $user, $password, $bbdd);



        $consulta = $conexion -> prepare('INSERT 
                                            INTO ofertas 
                                            (usuario_id, 
                                            categoria_id,
                                            nombre, 
                                            descripcion,
                                            fecha_actividad, 
                                            aforo, 
                                            visada,
                                            created_at,
                                            updated_at ) 
                                            VALUES
                                            (1, 
                                            ?, 
                                            "Setas y Gurumelos", 
                                            "Paseo por la sierra a la caza de tesoros micológicos",
                                            "2025-03-14", 
                                            10,
                                            0,
                                            CURRENT_TIMESTAMP, 
                                            CURRENT_TIMESTAMP');
        $consulta -> bindParam(1, $_SESSION['id']);
        $consulta -> bindParam(2, $select);
        ?>