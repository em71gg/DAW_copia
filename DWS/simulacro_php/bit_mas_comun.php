<?php
    /**
     * Takes an number argument an create an random array of numbers
     * between two given mumbers
     * 
     */
    
    function createRandomArray($number, $start, $end){
        for ($i = 0 ; $i < $number; $i++){
            $array[] = rand($start, $end);
        }
        return $array;
    };
    
    /**
     * Toma como argumentos un número variable de arrays.
     * si se proporcionan menos de tres o un muero par se da un aviso
     * De otra forma el programa devuelve un array con el bit mas comun 
     * en cada posición 
     */
    function arraysToMoreCommonBit(...$args){
        if (count($args) > 2 && count($args) % 2 != 0){
            $arraySum = array_fill(0, 10, 0);
            $moreCommonBit = [];
            
            foreach($args as $array){
                $i=0;
                foreach($array as $bit){
                    $arraySum [$i] += $bit; 
                    $i++;
                }
            }
            foreach($arraySum as $bit){
                if($bit> (count($args)/2)){
                    $moreCommonBit[] = 1;
                }
                else{
                    $moreCommonBit[] = 0;
                }
            }
            return $moreCommonBit;
        }
        else{
            echo "Se deben proporcionar un número impar de arrays mayor que dos.";
        }
    }

    function printArrays(...$args){
        $loop = 1;
        $result = "Resultado";
        $tail = "";
        for ($i =0 ; $i < strlen($result); $i ++){
            $tail  .= "&nbsp;";
        }
      
        $header = "A" ;
        
        foreach($args as $array){
            echo $header . $tail .": " . implode(", ", $array) . "</br>";
            $header ++;
            $loop ++;
            if($loop == (count($args))){
                break;
            }
        }
        echo $result . ": " . implode(", ", end($args)) . "</br>";//end(array), devuelve el último elemento de un array
        
    }

    
    $arrayA = createRandomArray(10, 0 ,1);
    $arrayB = createRandomArray(10, 0 ,1);
    $arrayC = createRandomArray(10, 0 ,1);
    $result = arraysToMoreCommonBit($arrayA, $arrayB, $arrayC);

    printArrays($arrayA, $arrayB, $arrayC, $result);

?>