<?php
    session_start();
    require_once("functions.php");
    closeSession();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="../estilos.css">
    <!-- <script src="../scripts/calendario.js" defer></script> -->
    <script src="../scripts/pedir_cita_api.js" defer></script>
    <script src="../scripts/jquery-3.2.1.min.js" defer></script>
    <link href="../vanilla-calendar-main/vanilla-calendar-main/package/build/vanilla-calendar.min.css" rel="stylesheet">
    <link href="../vanilla-calendar-main/vanilla-calendar-main/package/build/themes/dark.min.css" rel="stylesheet">
    <link href="../vanilla-calendar-main/vanilla-calendar-main/package/build/themes/light.min.css" rel="stylesheet">
    <!-- Plugin JS -->
    <script src="../vanilla-calendar-main/vanilla-calendar-main/package/build/vanilla-calendar.min.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <title>Bienvenido</title>
</head>
<body>
    <?php
        printMenu();
    ?>
    <section>
        <select name="servicio" id="select-servicio">
            <?php
                getServiciosSelect();
            ?>
        </select>
        <select name="empleado" id="select-empleado"></select>
    </section>
    <section class="container-fluid px-sm-3 px-0 mt-4 row">
    <div class="col-12 col-md-6" id="calendar"></div>
    
    <?php
        // if(!isset($_SESSION['user'])){
        //     header('Refresh: 0; URL=../index.php');
        // }else{
        //     if($_SESSION['user']!=="admin@admin.com"){
        //         $usuario=$_SESSION['user'];
        //         $conexion=createConnection();
        //         $consulta=$conexion->query("select id from personas where correo='$usuario'");
        //         $num=$consulta->fetch_array(MYSQLI_ASSOC);
        //         $id=$num['id'];
        //     }

        //     setlocale(LC_ALL,"es-ES.UTF-8");
        //     $conexion=createConnection();
            
        //     $mes_actual=date('m',time());
        //     $anio_actual=date('Y');

        //     if(isset($_GET['mes'])){
        //         $mes=$_GET['mes'];
        //         $anio=$_GET['año'];
        //     }else{
        //         $mes=$mes_actual;
        //         $anio=$anio_actual;
        //     }

        //     $fecha=mktime(0,0,0,$mes,1,$anio);
        //     $inicio_mes=date('N',$fecha);
        //     $fin_mes=date('t',$fecha);
        //     $nombre_mes=ucfirst(strftime('%B',$fecha));
        //     $dia=0;

        //     if($_SESSION['user']!="admin@admin.com"){
        //         $consulta=$conexion->query("select day(fecha) dia from citas where (cliente=$id or trabajador=$id) and year(fecha)=$anio and month(fecha)=$mes order by fecha asc");
        //     }else{
        //         $consulta=$conexion->query("select day(fecha) dia from citas where year(fecha)=$anio and month(fecha)=$mes order by fecha asc");
        //     }
        //     $num_filas=$consulta->num_rows;
        //     if($num_filas>0){
        //         $fila=$consulta->fetch_array(MYSQLI_ASSOC);
        //         $dia=$fila['dia'];
        //     }

        //     $mes_siguiente = $mes + 1;
        //     $mes_anterior = $mes - 1;
        //     $año_siguiente = $anio;
        //     $año_anterior = $anio;

        //     if($mes_siguiente > 12)
        //     {
        //         $mes_siguiente = 1;
        //         $mes=$mes_siguiente;
        //         $año_siguiente++;
        //         $anio=$año_anterior;
        //     }
        //     if($mes_anterior<1)
        //     {
        //         $mes_anterior=12;
        //         $mes=$mes_anterior;
        //         $año_anterior--;
        //         $anio=$año_anterior;
        //     }
        //     // echo "<table class='w-100 text-center'>
        //     echo "<table id=\"calendario\" class='col-12 col-md-6'>
        //         <caption class=\"text-center\">
        //             <a href='calendario.php?mes=$mes_anterior&año=$año_anterior'><-</a>
        //             $nombre_mes
        //             <a href='calendario.php?mes=$mes_siguiente&año=$año_siguiente'>-></a>
        //         </caption>
        //         <thead>
        //             <tr>
        //                 <th>Lunes</th>
        //                 <th>Martes</th>
        //                 <th>Miércoles</th>
        //                 <th>Jueves</th>
        //                 <th>Viernes</th>
        //                 <th>Sábado</th>
        //                 <th>Domingo</th>
        //             </tr>
        //         </thead>
        //         <tbody>
        //             <tr>";

        //     $anio_consulta=$anio;
        //     if($mes==12){
        //         $mes_consulta=1;
        //         $anio_consulta++;
        //     }else if($mes==1){
        //         $mes_consulta=12;
        //     }else{
        //         $mes_consulta=$mes;
        //     }

        //     for($i=1;$i<$inicio_mes;$i++){
        //         echo "<td></td>";
        //     }

        //     $contador=$inicio_mes;
        //     for($i=1;$i<=$fin_mes;$i++){
        //         if($i==$dia){
        //             echo "<td class='cita'><a href='?mes=$mes_consulta&año=$anio_consulta&dia=$i'>$i</a></td>";
        //             $fila=$consulta->fetch_array(MYSQLI_ASSOC);
        //             if($fila!=false){
        //                 $dia=$fila['dia'];
        //             }
        //         }else{
        //             $dia = formatDate($i);
        //             $mes_f = formatDate($mes_consulta);
        //             $fecha = "$anio_consulta-$mes_f-$dia";
        //             echo "<td data-date='$fecha'>$i</td>";
        //         }
        //         if($contador==7 and $i<$fin_mes){
        //             echo "</tr><tr>";
        //             $contador=0;
        //         }
        //         $contador++;
        //     }
        //     if($contador>1){
        //         for($i=$contador;$i<=7;$i++){
        //             echo "<td></td>";
        //         }
        //     }
            
        //     $consulta->close();
           
            // echo "</tr>
            //     </tbody>
            // </table>
            
            // <table>
            //     <thead>
            //         <tr>
            //             <th>Fecha</th>
            //             <th>Hora</th>
            //             <th>Cliente</th>
            //             <th>Trabajador</th>
            //             <th>Servicio</th>
            //         </tr>
            //     </thead>
            //     <tbody>";

            // if(isset($_GET['dia'])){
            //     $busqueda="$_GET[año]-$_GET[mes]-$_GET[dia]";
            // }else{
            //     $dia_actual=date('d');
            //     $busqueda="$anio_consulta-$mes_consulta-$dia_actual";

            // }
            
            // if($_SESSION['user']=="admin@admin.com"){
            //     $preparada=$conexion->prepare("select fecha,hora,cliente,trabajador,nombre,servicio from citas,servicios where servicios.id=servicio and fecha=? order by fecha asc");
            // }else{
            //     $preparada=$conexion->prepare("select fecha,hora,cliente,trabajador,nombre,servicio from citas,servicios where (citas.cliente=$id or citas.trabajador=$id) and servicios.id=servicio and fecha=? order by fecha asc");
            // }
            // $preparada->bind_param("s",$busqueda);
            // $preparada->bind_result($fecha,$hora,$cliente,$trabajador,$servicio_nom,$servicio);
            // $preparada->execute();
            // $preparada->store_result();
            // if($preparada->num_rows>0){
            //     while($preparada->fetch()){
            //         $fecha2=date('d-m-Y',strtotime($fecha));
            //         $formato=explode(":",$hora);
            //         $hora=$formato[0].":".$formato[1];

            //         $consulta=$conexion->query("select nombre from personas where id=$cliente");
            //         $cliente_nom=$consulta->fetch_array(MYSQLI_NUM);
            //         $consulta->close();

            //         $consulta=$conexion->query("select nombre from personas where id=$trabajador");
            //         $trabajador_nom=$consulta->fetch_array(MYSQLI_NUM);
            //         $consulta->close();

            //         echo "<tr>
            //             <td data-fecha='$fecha'>$fecha2</td>
            //             <td>$hora</td>
            //             <td data-id='$cliente'>$cliente_nom[0]</td>
            //             <td data-id='$trabajador'>$trabajador_nom[0]</td>
            //             <td data-id='$servicio'>$servicio_nom</td>";

            //             $month=getdate()['mon'];
            //             if($month<10){
            //                 $month="0".$month;
            //             }

            //             $hoy=getdate()['year']."-".$month."-".getdate()['mday'];
            //             if($fecha>$hoy){
            //                 if($_SESSION['tipo']!=="Cliente"){
            //                     echo "<td>
            //                         <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#exampleModal' data-bs-whatever='Editar'>Editar</button>
            //                     </td>
            //                     <td>
            //                         <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#exampleModal' data-bs-whatever='Cancelar'>Cancelar</button>
            //                     </td>";
            //                 }
            //             }
            //         echo "</tr>";
            //     }
            // }else{
            //     echo "<tr>
            //         <td colspan=5 class='tabla_vacia'>No hay citas para hoy</td>
            //     </tr>";
            // }

        //     echo "</tbody>
        //     </table>";
            echo "<div class='col-12 col-md-6'>
                        <h2 class='text-center'>Horas</h2>  
                </div>";

        //     $conexion->close();
        // }
    ?>
    </section>
</body>
</html>
<script>
    //   document.addEventListener('DOMContentLoaded', () => {
    //     const calendar = new VanillaCalendar('#calendar',{
    //         selected: {
    //             holidays: ['2022-12-24', '2022-12-25'],
    //         },
    //     });
    //     calendar.settings.lang = 'es';
    //     calendar.init();
    //   });
    </script>
