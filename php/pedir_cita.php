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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../estilos.css">
    <link rel="stylesheet" href="../vanilla-calendar-main/package/build/vanilla-calendar.min.css" />
    <!-- <link rel="stylesheet" href="../vanilla-calendar-main/package/src/styles/vanilla-calendar.css" />
    <link rel="stylesheet" href="../vanilla-calendar-main/package/src/styles/themes/light.css" /> -->
    <!-- <script src="../scripts/calendario.js" defer></script> -->
    <script src="../vanilla-calendar-main/package/build/vanilla-calendar.min.js" type='text/javascript' defer></script>
    <script src="../scripts/pedir_cita_api.js" defer></script>
    <script src="../scripts/jquery-3.2.1.min.js" defer></script>

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
        <div class="vanilla-calendar">
            
        </div>
    </section>
</body>
</html>