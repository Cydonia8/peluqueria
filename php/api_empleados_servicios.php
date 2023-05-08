<?php
    //Cabeceras
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");

    $conexion = new mysqli('localhost', 'root', '', 'peluqueria');
    // sleep(1);

    $id = $_GET["id"];
    // $id = 2;
    $sentencia = $conexion->prepare("select nombre from servicios where id = ?");
    $sentencia->bind_param('i', $id);
    $sentencia->bind_result($nombre);
    $sentencia->execute();
    $datos = [];
    
    while($sentencia->fetch()){
        $datos[] = $nombre;
    }
    $info['datos'] = $datos;
    // select p.nombre nombre, p.id id, f_inicio, f_fin, m_inicio, m_fin, t_inicio, t_fin ,fecha ,hora ,c.servicio servicio ,duracion 
    // from personas p, realiza r, trabaja t, citas c, servicios s where s.id=c.servicio and p.id=trabajador and p.id=t.empleado and p.id = r.empleado and r.servicio=?

    echo json_encode($info);