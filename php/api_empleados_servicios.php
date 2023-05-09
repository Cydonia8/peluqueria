<?php
    //Cabeceras
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");

    $conexion = new mysqli('localhost', 'root', '', 'peluqueria');
    // sleep(1);

    // $id = $_GET["id"];
    $datos = [];
    // $id = 2;
    $sentencia = $conexion->prepare("select * from realiza");
    $sentencia->execute();
    $resultado=$sentencia->get_result();
    $realiza=[];

    while($fila=$resultado->fetch_assoc()){
        $realiza[] = $fila;
    }
    $sentencia->close();
    $datos["realiza"]=$realiza;

    $sentencia = $conexion->prepare("select * from servicios");
    $sentencia->execute();
    $resultado=$sentencia->get_result();
    $servicios=[];

    while($fila=$resultado->fetch_assoc()){
        $servicios[] = $fila;
    }
    $sentencia->close();
    $datos["servicios"]=$servicios;

    $sentencia = $conexion->prepare("select * from citas");
    $sentencia->execute();
    $resultado=$sentencia->get_result();
    $citas=[];    

    while($fila=$resultado->fetch_assoc()){
        $citas[] = $fila;
    }
    $sentencia->close();
    $datos["citas"]=$citas;

    $sentencia = $conexion->prepare("select * from trabaja");
    $sentencia->execute();
    $resultado=$sentencia->get_result();
    $trabaja=[];

    while($fila=$resultado->fetch_assoc()){
        $trabaja[] = $fila;
    }
    $sentencia->close();
    $datos["trabaja"]=$trabaja;

    $sentencia = $conexion->prepare("select * from personas where tipo=2");
    $sentencia->execute();
    $resultado=$sentencia->get_result();
    $empleados=[];

    while($fila=$resultado->fetch_assoc()){
        $empleados[] = $fila;
    }
    $sentencia->close();
    $datos["empleados"]=$empleados;

    $sentencia = $conexion->prepare("select * from descanso");
    $sentencia->execute();
    $resultado=$sentencia->get_result();
    $descanso=[];

    while($fila=$resultado->fetch_assoc()){
        $descanso[] = $fila;
    }
    $sentencia->close();
    $datos["descanso"]=$descanso;

    $sentencia = $conexion->prepare("select * from horario");
    $sentencia->execute();
    $resultado=$sentencia->get_result();
    $horario=[];

    while($fila=$resultado->fetch_assoc()){
        $horario[] = $fila;
    }
    $sentencia->close();
    $datos["horario"]=$horario;






    $info["datos"] = $datos;
    // select p.nombre nombre, p.id id, f_inicio, f_fin, m_inicio, m_fin, t_inicio, t_fin ,fecha ,hora ,c.servicio servicio ,duracion 
    // from personas p, realiza r, trabaja t, citas c, servicios s where s.id=c.servicio and p.id=trabajador and p.id=t.empleado and p.id = r.empleado and r.servicio=?
    
    echo json_encode($info);