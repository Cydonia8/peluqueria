<?php
    //Cabeceras
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    
    $conexion = new mysqli('localhost', 'root', '', 'peluqueria');
    sleep(1);
    $sentencia = $conexion->query("select nombre, id from personas p, realiza r where p.id = r.empleado and r.servicio ");
    $datos = [];
    
    while($fila = $sentencia->fetch_array(MYSQLI_ASSOC)){
        $datos[] = $fila;
    }
    $info['datos'] = $datos;

    echo json_encode($info);