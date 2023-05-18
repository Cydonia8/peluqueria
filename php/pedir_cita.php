<?php
    session_start();
    require_once("functions.php");
    closeSession();
    if(isset($_POST['insertar'])){
        $conexion=createConnection();
        $preparada=$conexion->prepare("insert into citas (cliente,trabajador,fecha,hora,servicio) values (?,?,?,?,?)");
        $id_cliente = getIDCliente($_SESSION["user"]);
        if(!isset($_POST["trabajador"]) or $_POST["fecha"] == '' or !isset($_POST['hora']) or !isset($_POST["servicio"])){
            echo "<div class='alert alert-danger' role='alert'>
           Faltan datos por rellenar. Cita no insertada.
          </div>";
        }else{
            $preparada->bind_param("iissi",$id_cliente,$_POST['trabajador'],$_POST['fecha'],$_POST['hora'],$_POST['servicio']);
            $preparada->execute();
            header("Refresh:0");
        }
        $preparada->close();
    }
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
    <script src="../vanilla-calendar-main/vanilla-calendar-main/package/build/vanilla-calendar.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <title>Bienvenido</title>
</head>
<body>
    <?php
        printMenu();
    ?>
    <form action="" method="post">
        <section class='container-fluid display-flex justify-content-evenly mt-4 row mx-auto'>
            <div class='col-12 col-sm-6 p-0 p-sm-1'>
                <select name="servicio" id="select-servicio" class="form-select form-select-lg mb-3 p-2" aria-label=".form-select-lg example">
                    <option checked hidden>Elige un servicio</option>
                    <?php
                        getServiciosSelect();
                    ?>
                </select>
            </div>
            <div class='col-12 col-sm-6 p-0 p-sm-1'>
                <select disabled name="trabajador" id="select-empleado" class="form-select form-select-lg mb-3 p-2" aria-label=".form-select-lg example">
                    <option checked hidden>Elige un trabajador</option>
                </select>
            </div>
            <div>
                
        </section>
        <section class="container-fluid px-sm-3 px-0 mt-4 row mx-auto gx-3">
            <div class='col-12 col-md-6 px-sm-1'>
                <div class='w-100' id="calendar"></div>
            </div>
            <input type="hidden" name="fecha" value=''>
            <div class='col-12 col-md-6 px-sm-1 mt-md-0 mt-3 '>
                <div class='border border-2 rounded-4 h-100 position-relative'>
                <h2 class='text-center'>Horas</h2>
                    <div id='horas' class='p-1'>
                        <h3 class='position-absolute top-50 start-50 w-100 text-center translate-middle mt-sm-2 mt-md-0'>Selecciona un día</h3>
                    </div>  
                </div>
            </div>
        </section>
        <input type="submit" value="Pedir cita" name='insertar' class='btn btn-primary d-block mx-auto mt-3'>
    </form>
</body>
</html>
