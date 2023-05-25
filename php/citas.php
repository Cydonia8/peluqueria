<?php
    session_start();
    require_once("functions.php");
    comprobarSesionBasica();
    closeSession();
    if(isset($_POST["cancelar-cita"])){
        $split = explode("/", $_POST["cancelar-cita"]);
        $cliente = $split[0];
        $trabajador = $split[1];
        $fecha = $split[2];
        $servicio = $split[3];
        $hora = $split[4];
        $con = createConnection();
        $delete = $con->prepare("DELETE FROM citas where cliente = ? and trabajador = ? and fecha = ? and servicio = ? and hora = ?");
        $delete->bind_param('iisis', $cliente, $trabajador, $fecha, $servicio, $hora);
        $delete->execute();
        $delete->close();
        $eliminada = true;
        $con->close();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../estilos.css">
    <script src="../scripts/jquery-3.2.1.min.js" defer></script>
    <script src="../scripts/citas.js" defer></script>
    <title>Document</title>
</head>
<body>
    <?php
        printMenu();
    ?>
    <div class="mt-4 d-flex flex-column align-items-center">
        <h2 class="text-center">Fecha de la/s cita/s</h2>
        <form action="#" method="post" class="d-flex flex-column align-items-center gap-3">
            <input type="date" name="fecha">
            <button type="submit" class="btn btn-primary">Ver citas</button>
        </form>
    </div>
    <div class='contenedor_tabla col-12 p-0'>
        <table>
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Trabajador</th>
                    <th>Servicio</th>
                    <th>Hora</th>
                    <th>Fecha</th>
                    <th>Cancelar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if(isset($_POST["fecha"])){
                        citasDia($_POST["fecha"]);
                    }
                ?>
            </tbody>
        </table>
    </div>
    <?php
        if(isset($eliminada) and $eliminada){
            echo "<div class=\"alert alert-success text-center\" role=\"alert\">
            Cita eliminada correctamente
          </div>";
        }
    ?>
</body>
</html>
