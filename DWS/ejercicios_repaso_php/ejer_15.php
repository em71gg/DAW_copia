<!--
Crea la clase Vehiculo, así como las clases Bicicleta y Coche como subclases de la primera. 
Para la  clase Vehiculo, crea los métodos de  clase getVehiculosCreados()  y getKmTotales(); 
así como el método de instancia getKmRecorridos(). Crea también algún método específico 
para cada una de las subclases. Prueba las clases creadas mediante una aplicación que realice, 
al menos, las siguientes acciones: 
◦ Anda con la bicicleta 
◦ Anda con el coche 
◦ Ver kilometraje de la bicicleta 
◦ Ver kilometraje del coche 
◦ Ver kilometraje total
-->

<?php
    class Vehiculo{
        private static int $vehiculosCreados= 0;
        private static float $kilometrosTotales =0;
        private float $kilometrosRecorridos=0;


        public function __construct($kilometrosRecorridos)
        {
            self::$vehiculosCreados ++;
            $this ->kilometrosRecorridos = $kilometrosRecorridos;

        }

        public static function getVehiculosTotales():float{
            return self::$vehiculosCreados;
        }

        public static function getKilometrosTotales() : float{
            return self::$kilometrosTotales;
            
        }
        public function getKilometrosRecorridos() : float {
            return $this -> kilometrosRecorridos;
        }
        public function circular($recorrido){
            $this -> kilometrosRecorridos += $recorrido;
            self::$kilometrosTotales += $recorrido;
        }
      
        public function setKilometrosRecorridos($kilometrosRecorridos)
        {
                $this->kilometrosRecorridos = $kilometrosRecorridos;

                return $this;
        }

    }

    class Coche extends Vehiculo{
        private $arrancado = false;

        public function __construct($kilometrosRecorridos, $arrancado)
        {
            parent::__construct($kilometrosRecorridos);
            $this -> arrancado = $arrancado;
        }
        
        public function arrancar(){
            $this ->arrancado = true;
            echo "El coche está arrancado.</br>"; 
        }

        public function circular($distancia){
            if($this -> arrancado === true){
                parent::circular($distancia);//usamos el método del parent
                echo "Recorriendo $distancia kilometros.</br>";
                $this -> setKilometrosRecorridos($this -> getKilometrosRecorridos() 
                + $distancia);
                

            }
            else{
                echo "Hay que arrancar el vehículo primero";
            }
            $this ->arrancado =false; //apagamos el coche al terminar 
        }
    }

    class Bicicleta extends Vehiculo{

        public function __construct($kilometrosRecorridos)
        {
            parent::__construct($kilometrosRecorridos);
        }

        public function circular($recorrido)
        {
            if ($recorrido >15){
                echo "Lo siento, $recorrido kilómetros son muchos, no puedo dar tantos pedales.";
            }
            else{
                parent::circular($recorrido);
                echo "Disfrutando del paseito de $recorrido kilometros.";
                $this -> setKilometrosRecorridos($this -> getKilometrosRecorridos() 
                + $recorrido);
            }
            
        }

       

        
    }

    $coche = new Coche(0, false);//creo un coche con nuevo sin kilómetros
    echo "Coche comprado comprobamos que es nuevo, tiene " . 
    $coche -> getKilometrosRecorridos() . " km.</br>".PHP_EOL;
    echo $coche -> circular(45) . "</br>".PHP_EOL;
    echo $coche -> arrancar();
    echo $coche -> circular(45);
    echo $coche -> getKilometrosRecorridos()."</br>".PHP_EOL;

    $bicicleta = new Bicicleta(24);

    echo $bicicleta -> circular(34)."</br>".PHP_EOL;;
    echo $bicicleta -> circular(3)."</br>".PHP_EOL;;
    echo $bicicleta -> getKilometrosRecorridos()."</br>".PHP_EOL;

    echo Vehiculo::getKilometrosTotales()."</br>".PHP_EOL;;




?>