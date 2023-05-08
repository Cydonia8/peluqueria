<?php
    //Cabeceras
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    
    $conexion = new mysqli('localhost', 'root', '', 'peluqueria');
    sleep(1);
    $sentencia = $conexion->query("select nombre, id, f_inicio, f_fin, m_inicio, m_fin, t_inicio, t_fin ,fecha ,hora ,servicio ,duracion from personas p, realiza r, trabaja t, citas c, servicios s where s.id=c.servicio and p.id=trabajador and p.id=t.empleado and p.id = r.empleado and r.servicio=?");
    $datos = [];
    
    while($fila = $sentencia->fetch_array(MYSQLI_ASSOC)){
        $datos[] = $fila;
    }
    $info['datos'] = $datos;

    echo json_encode($info);