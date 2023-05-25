<?php
    session_start();
    require_once("functions.php");
    comprobarSesionAdmin();
    closeSession();
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
        <script src="../scripts/horario.js" defer></script>
        <title>Bienvenido</title>
    </head>
    <body>
        <?php
            printMenu();
        ?>
        <section class="container-fluid px-sm-3 px-0 mt-4 row mx-auto">
            <form action="" method="post" class='row col-10 mx-auto d-flex justify-content-between'>
                <div class='col-5'>
                    <div class='d-flex flex-column mb-3'>
                        <label for='nombre'>Nombre</label>
                        <input type="text" name="nombre">
                    </div>
                    <div class='d-flex flex-column mb-3'>
                        <label for='correo'>Correo electrónico</label>
                        <input type="text" name="correo">
                    </div>
                    <div class='d-flex flex-column mb-3'>
                        <label for='telefono'>Teléfono</label>
                        <input type="text" name="telefono">
                    </div>
                </div>
                <div class='col-5'>
                    <div class='d-flex flex-column mb-3'>
                        <label for='empresa'>Empresa</label>
                        <input type="text" name="empresa">
                    </div>
                    <div class='d-flex flex-column mb-3'>
                        <label for='direccion'>Dirección</label>
                        <input type="text" name="direccion">
                    </div>
                    <div class='d-flex flex-column mt-3'>
                        <br>
                        <input type="submit" name='enviar' value="Enviar">
                    </div>
                </div>
            </form>
        </section>
    </body>
    </html>