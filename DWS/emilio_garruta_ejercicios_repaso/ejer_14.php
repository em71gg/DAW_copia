<!--
Crea la clase DadoPoker. Las caras de un dado de poker tienen las siguientes figuras: As, K, 
Q, J, 7 y 8 . Crea el método tira() que no hace otra cosa que tirar el dado, es decir, genera un 
valor  aleatorio  para  el  objeto  al  que  se  le  aplica  el  método.  Crea  también  el  método 
nombreFigura(),  que  diga  cuál  es  la  figura  que  ha  salido  en  la  última  tirada  del  
dado  en cuestión. Crea, por último, el método getTiradasTotales() que debe mostrar el número total de 
tiradas entre todos los dados. Realiza una aplicación que permita tirar un cubilete con cinco 
dados de poker.
-->

<?php
/*
<!--
Crea la clase DadoPoker. Las caras de un dado de poker tienen las siguientes figuras: As, K, 
Q, J, 7 y 8 . Crea el método tira() que no hace otra cosa que tirar el dado, es decir, genera un 
valor  aleatorio  para  el  objeto  al  que  se  le  aplica  el  método.  Crea  también  el  método 
nombreFigura(),  que  diga  cuál  es  la  figura  que  ha  salido  en  la  última  tirada  del  
dado  en cuestión. Crea, por último, el método getTiradasTotales() que debe mostrar el número total de 
tiradas entre todos los dados. Realiza una aplicación que permita tirar un cubilete con cinco 
dados de poker.
-->
*/
    class DadoPoker{
        
        private static $tiradasTotales = 0;//atributo de clase que almacenará las tiradas de todos los dados incluidos en el cubilete
        private array $carasDado = ['AS', 'K', 'Q', 'J', '8', '7'];
        //private int $tiradasDado = 0; No la necesito para el ejercicio
        private string $ultimaTirada = "";
        
        public function tirar(): string{
            $tirada = $this -> carasDado[rand(0,5)];//elige un vlor del array al seudo azar
            //$this -> tiradasDado ++;//cada vez que tiro actualizo la variable de instancia
            self::$tiradasTotales ++;//cada vez que tiro actualizo la variable estática
            $this -> ultimaTirada = $tirada;//actualizo el valor de última tirada
             
            return $tirada;
        }

        public function nombreFigura(): string{
            
            switch($this -> ultimaTirada){//En función del valor de la última tirada devuelvo un string u otro
                case $this -> carasDado[0]:
                    return "Ha salido un as";
                    break;
                case $this -> carasDado[1]:
                    return "Ha salido un rey";
                    break; 
                case $this -> carasDado[2]:
                    return "Ha salido una dama";
                    break;
                case $this -> carasDado[3]:
                    return "Ha salido una jota";
                    break;
                case $this -> carasDado[4]:
                    return "Ha salido un ocho";
                    break;
                case $this -> carasDado[5]:
                    return "ha salido un siete";
                    break;
            }
            
        }
        
        public static function getTiradasTotales() : int {
            return self::$tiradasTotales;
        }
    }

   
    /**
     * tirarCubilete toma un número de dados pasados como argumento y devuleve los resultados de lanzarlos
     * @param {...argv}
     * @return {string}
     */
    function tirarCubilete(...$argv):string{//con esta función puedo llenar el cubilete con el número de dados que necesite, 
        //posiblemente podía haber hecho lo mismo de forma más simple pasándole un número, en funcion de los dados que quisiera
        $cubilete =[];
        $rdo = "";

        foreach($argv as $dado){//lleno el cubilete con tantos dados como argumentos paso a la función
           
            $cubilete[] = new DadoPoker();
            
        }
        $counter = 1; //contador para numerar los dados
        foreach($cubilete as $dado){//itero el cubilete para tirar sus dados
            $dado ->tirar();

            $rdo .= "En el dado $counter " . $dado -> nombreFigura() . ".</br>".PHP_EOL;
            $counter ++;
             
        }

        return "Se han tirado " . DadoPoker::getTiradasTotales() .  " dados.</br> $rdo."; //retorna el número de daods totales y sus resultados

    }

   echo  tirarCubilete("dado1", "dado2", "dado3", "dado4", "dado5"); //llmada a la función
?>