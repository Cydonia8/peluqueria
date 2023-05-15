<?php
    //Cabeceras
	header('Content-Type: application/json');
	header("Access-Control-Allow-Origin: *");
    $fecha_actual = date('Y-m-d');
    $conexion = new mysqli('localhost', 'root', '', 'peluqueria');
    $sentencia = $conexion->query("select fecha, s.nombre servicio, p.nombre trab, hora, duracion, p.id id_trab from citas c, servicios s, personas p where c.servicio = s.id and c.trabajador = p.id and fecha >= $fecha_actual");
    $datos = [];
    
    while($fila = $sentencia->fetch_array(MYSQLI_ASSOC)){
        $datos[] = $fila;
    }
    $info['datos'] = $datos;

    echo json_encode($info);
?>