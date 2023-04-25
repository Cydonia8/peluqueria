<?php
    session_start();
    require_once("functions.php");
    $conexion=createConnection();

    if(isset($_POST['insertar'])){
        $preparada=$conexion->prepare("insert into citas (cliente,trabajador,fecha,hora,servicio) values (?,?,?,?,?)");
        $preparada->bind_param("iissi",$_SESSION['user'],$_POST['trabajador'],$_POST['fecha'],$_POST['hora'],$_POST['servicio']);
        $preparada->execute();
        $preparada->close();
        header("Refresh:0");
    }else if(isset($_POST['editar'])){
        $preparada=$conexion->prepare("update citas set trabajador=?, fecha=?, hora=? where id=?");
        $preparada->bind_param("ssdi",$_POST['nombre'],$_POST['duracion'],$_POST['precio'],$_POST['id']);
        $preparada->execute();
        $preparada->close();
        header("Refresh:0");
    }else if(isset($_POST['borrar'])){
        $preparada=$conexion->prepare("delete from citas where ...");
        $preparada->bind_param("ii",$_POST['valor'],$_POST['id']);
        $preparada->execute();
        $preparada->close();
        header("Refresh:0");
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../estilos.css">
    <script src="../scripts/servicios.js" defer></script>
    <title>Bienvenido</title>
</head>
<body>
    <?php
        printMenu();
    ?>
    <div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="Pedir cita">Pedir cita</button>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Empleado:</label>
                            <select name="empleado" class="form-select" aria-label="Default select example">
                                <option selected hidden disabled>Elige con quien quieres la cita</option>
                                <?php
                                    $consulta=$conexion->query("select personas.id,personas.nombre from personas,tipos where tipo=tipos.id and tipos.nombre='Trabajador'");
                                    while($lista=$consulta->fetch_array(MYSQLI_ASSOC)){
                                        echo "<option value=$lista[id]>$lista[nombre]</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Servicio:</label>
                            <select name="servicio" class="form-select" aria-label="Default select example">
                                <option selected hidden disabled>Elige un servicio</option>
                                <?php
                                    $consulta=$conexion->query("select * from servicios");
                                    while($lista=$consulta->fetch_array(MYSQLI_ASSOC)){
                                        $duracion=explode(":",$lista['duracion']);
                                        echo "<option value=$lista[id]>$lista[nombre] - $duracion[0]:$duracion[1]</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Fecha:</label>
                            <input type="date" name="fecha" class="form-control" id="recipient-name" required>
                        </div>
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Hora:</label>
                            <input type="time" name="hora" class="form-control" id="recipient-name" required>
                        </div>
                        <input type='hidden' name='id' value=''>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="submit" class="recargar btn btn-primary" name="" value="Enviar">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <section class="container-fluid px-sm-3 px-0 mt-4 row">
    <?php
        if(!isset($_SESSION['user'])){
            header('Refresh: 0; URL=../index.php');
        }else{
            if($_SESSION['user']!=="admin@admin.com"){
                $usuario=$_SESSION['user'];
                $conexion=createConnection();
                $consulta=$conexion->query("select id from personas where correo='$usuario'");
                $num=$consulta->fetch_array(MYSQLI_ASSOC);
                $id=$num['id'];
            }

            setlocale(LC_ALL,"es-ES.UTF-8");
            $conexion=createConnection();
            
            $mes_actual=date('m',time());
            $anio_actual=date('Y');

            if(isset($_GET['mes'])){
                $mes=$_GET['mes'];
                $anio=$_GET['año'];
            }else{
                $mes=$mes_actual;
                $anio=$anio_actual;
            }

            $fecha=mktime(0,0,0,$mes,1,$anio);
            $inicio_mes=date('N',$fecha);
            $fin_mes=date('t',$fecha);
            $nombre_mes=ucfirst(strftime('%B',$fecha));
            $dia=0;

            if($_SESSION['user']!="admin@admin.com"){
                $consulta=$conexion->query("select day(fecha) dia from citas where (cliente=$id or trabajador=$id) and year(fecha)=$anio and month(fecha)=$mes order by fecha asc");
            }else{
                $consulta=$conexion->query("select day(fecha) dia from citas where year(fecha)=$anio and month(fecha)=$mes order by fecha asc");
            }
            $num_filas=$consulta->num_rows;
            if($num_filas>0){
                $fila=$consulta->fetch_array(MYSQLI_ASSOC);
                $dia=$fila['dia'];
            }

            $mes_siguiente = $mes + 1;
            $mes_anterior = $mes - 1;
            $año_siguiente = $anio;
            $año_anterior = $anio;

            if($mes_siguiente > 12)
            {
                $mes_siguiente = 1;
                $mes=$mes_siguiente;
                $año_siguiente++;
                $anio=$año_anterior;
            }
            if($mes_anterior<1)
            {
                $mes_anterior=12;
                $mes=$mes_anterior;
                $año_anterior--;
                $anio=$año_anterior;
            }

            echo "<table id=\"calendario\">
                <caption>
                    <a href='calendario.php?mes=$mes_anterior&año=$año_anterior'><-</a>
                    $nombre_mes
                    <a href='calendario.php?mes=$mes_siguiente&año=$año_siguiente'>-></a>
                </caption>
                <thead>
                    <tr>
                        <th>Lunes</th>
                        <th>Martes</th>
                        <th>Miércoles</th>
                        <th>Jueves</th>
                        <th>Viernes</th>
                        <th>Sábado</th>
                        <th>Domingo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>";

            $anio_consulta=$anio;
            if($mes==12){
                $mes_consulta=1;
                $anio_consulta++;
            }else if($mes==1){
                $mes_consulta=12;
            }else{
                $mes_consulta=$mes;
            }

            for($i=1;$i<$inicio_mes;$i++){
                echo "<td></td>";
            }

            $contador=$inicio_mes;
            for($i=1;$i<=$fin_mes;$i++){
                if($i==$dia){
                    echo "<td class='cita'><a href='?mes=$mes_consulta&año=$anio_consulta&dia=$i'>$i</a></td>";
                    $fila=$consulta->fetch_array(MYSQLI_ASSOC);
                    if($fila!=false){
                        $dia=$fila['dia'];
                    }
                }else{
                    echo "<td>$i</td>";
                }
                if($contador==7){
                    echo "</tr><tr>";
                    $contador=0;
                }
                $contador++;
            }
            for($i=$contador;$i<=7;$i++){
                echo "<td></td>";
            }

            echo "</tr>
                </tbody>
            </table>";

            $conexion->close();

            if($_SESSION['user']==="admin@admin.com"){
            // echo "<div class='funcionalidades'>";
            //     echo "<a href='insertar_citas.php'>Añadir cita</a>";
            //     echo "<form  action='#' method='post' enctype='multipart/form-data'>
            //     <input id='buscar' type='text' name='buscar' maxlength='50' placeholder='Nombre, fecha o servicio'>
            //     <input type='submit' name='enviar' value='Buscar'>
            //     </form>
            //     </div>";
            }

            $conexion=createConnection();
            
            echo "<table id='tabla_citas' class='con_mod'>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Socio</th>
                        <th>Teléfono</th>
                        <th>Servicio</th>
                    </tr>
                </thead>
                <tbody>";

            if(isset($_GET['dia'])){
                $busqueda="$_GET[año]-$_GET[mes]-$_GET[dia]";
                $preparada=$conexion->prepare("select fecha,hora,cliente,trabajador,nombre from citas,servicios where citas.cliente!=0 and servicios.id=servicio and fecha=? order by fecha desc");

                $preparada->bind_param("s",$busqueda);
                $preparada->bind_result($fecha,$hora,$cliente,$trabajador,$servicio);
                $preparada->execute();
                $preparada->store_result();
            }else{
                $dia_actual=date('d');

                if($_SESSION['user']!=="admin@admin.com"){
                    $preparada=$conexion->prepare("select fecha,hora,cliente,trabajador,nombre from citas,servicios where citas.cliente!=0 and servicios.id=servicio and month(fecha)=? and year(fecha)=? and day(fecha)=? order by fecha asc");
                }else{
                    $preparada=$conexion->prepare("select fecha,hora,cliente,trabajador,nombre from citas,servicios where citas.cliente!=0 and servicios.id=servicio and month(fecha)=? and year(fecha)=? and day(fecha)=? order by fecha asc");
                }
                $preparada->bind_param("iii",$mes_consulta,$anio_consulta,$dia_actual);
                $preparada->bind_result($fecha,$hora,$cliente,$trabajador,$servicio);
                $preparada->execute();
                $preparada->store_result();
            }
                if($preparada->num_rows>0){
                    while($preparada->fetch()){
                        $fecha2=date('d-m-Y',strtotime($fecha));

                        $consulta=$conexion->query("select nombre from personas where id=$cliente");
                        $cliente=$consulta->fetch_array(MYSQLI_NUM);
                        $consulta->close();

                        $consulta=$conexion->query("select nombre from personas where id=$trabajador");
                        $trabajador=$consulta->fetch_array(MYSQLI_NUM);
                        $consulta->close();

                        echo "<tr>
                            <td>$fecha2</td>
                            <td>$hora</td>
                            <td>$cliente[0]</td>
                            <td>$trabajador[0]</td>
                            <td>$servicio</td>";
    
                            $hoy=getdate()['year']."-".getdate()['mon']."-".getdate()['mday'];
                            if($fecha>$hoy){
                                if($_SESSION['user']!=="admin@admin.com"){
                                    echo "<td></td>";
                                }else{
                                    echo "<td>
                                        <button'>Borrar</button>
                                    </td>";
                                }
                            }else{
                                echo "<td></td>";
                            }
                        echo "</tr>";
                    }
                }else{
                    echo "<tr>
                        <td colspan=5 class='tabla_vacia'>No hay citas para hoy</td>
                    </tr>";
                }

            echo "</tbody>
            </table>";

            $conexion->close();
        }
    ?>
    </section>
</body>
</html>