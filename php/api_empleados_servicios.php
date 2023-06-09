<?php
    //Cabeceras
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");

    $conexion = new mysqli('localhost', 'root', '', 'peluqueria');
    // sleep(1);

    $id = $_GET["id"];
    $datos = [];
    // $id = 2;
    $sentencia = $conexion->prepare("select * from realiza where servicio=?");
    $sentencia->bind_param('i',$id);
    $sentencia->execute();
    $resultado=$sentencia->get_result();
    $realiza=[];

    while($fila=$resultado->fetch_assoc()){
        $realiza[] = $fila;
    }
    $sentencia->close();
    $datos["realiza"]=$realiza;

    $sentencia = $conexion->prepare("select trabaja.* from trabaja,realiza where trabaja.empleado=realiza.empleado and servicio=?");
    $sentencia->bind_param('i',$id);
    $sentencia->execute();
    $resultado=$sentencia->get_result();
    $trabaja=[];

    while($fila=$resultado->fetch_assoc()){
        $trabaja[] = $fila;
    }
    $sentencia->close();
    $datos["trabaja"]=$trabaja;

    $sentencia = $conexion->prepare("select personas.* from personas,realiza where tipo=2 and empleado=id and servicio=?");
    $sentencia->bind_param('i',$id);
    $sentencia->execute();
    $resultado=$sentencia->get_result();
    $empleados=[];

    while($fila=$resultado->fetch_assoc()){
        $empleados[] = $fila;
    }
    $sentencia->close();
    $datos["empleados"]=$empleados;

    $info["datos"] = $datos;
    // select p.nombre nombre, p.id id, f_inicio, f_fin, m_inicio, m_fin, t_inicio, t_fin ,fecha ,hora ,c.servicio servicio ,duracion 
    // from personas p, realiza r, trabaja t, citas c, servicios s where s.id=c.servicio and p.id=trabajador and p.id=t.empleado and p.id = r.empleado and r.servicio=?
    
    echo json_encode($info);