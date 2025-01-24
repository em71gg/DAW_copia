<!--
Escribe una función que convierta un número determinado de minutos 
al formato horas:minutos. Por ejemplo, 156 minutos sería 02:36.
-->

<?php
    function convertirHorasAminutos(int $minutos){
        $restoMinutos = $minutos % 60;//minutos que restan a las hora completas
        $horasCompletas = intdiv($minutos,60);//calculo las horas completas
        $hora="";
        $minuto="";
        //Formateo los minuots y las horas si no tienen dos dígitos
        if($restoMinutos<10){
            $minuto = "0$restoMinutos";

        }else{
            $minuto = "$restoMinutos";
        }
        if($horasCompletas<10){
            $hora = "0$horasCompletas";

        }else{
            $hora = "$horasCompletas";
        }


        return $hora. ":" . $minuto;

    }

    echo convertirHorasAminutos(156);
?>