<?php
    // Recoger los datos del formulario y sanearlos
    if(isset($_POST['ciudad']) && isset($_POST['empresa']) && isset($_POST['representante']) &&
    isset($_POST['domicilio_empresa']) && isset($_POST['empleado']) && isset($_POST['domicilio_empleado'])){
        $ciudad = trim(htmlspecialchars($_POST['ciudad']));
        $empresa = trim(htmlspecialchars($_POST['empresa']));
        $representante = trim(htmlspecialchars($_POST['representante']));
        $domicilio_empresa = trim(htmlspecialchars($_POST['domicilio_empresa']));
        $empleado = trim(htmlspecialchars($_POST['empleado']));
        $domicilio_empleado = trim(htmlspecialchars($_POST['domicilio_empleado']));
    }
        echo "<h3>Contrato Generado</h3>";
        echo "<p>En la ciudad de " . $ciudad . 
        "</strong>, se acuerda entre la Empresa " . $empresa . 
        "</strong> representada por el Sr. " . $representante . 
        "</strong> en su carácter de Apoderado, con domicilio en la calle " . $domicilio_empresa . 
        "</strong> y el Sr. " . $empleado . ", futuro empleado con domicilio en " . 
        $domicilio_empleado . ", celebrar el presente contrato a Plazo Fijo, de acuerdo a 
        la normativa vigente de los artículos 90, 92, 93, 94, 95 y concordantes de la Ley de Contrato de 
        Trabajo No. 20744.</p>";
    
?>