<?php
    session_start();
    require_once "functions.php";
    if(isset($_POST["insertar"])){
        $nombre = $_POST["nombre"];
        $pass = $_POST["pass"];
        $mail = $_POST["correo"];
        $tlf = $_POST["telefono"];
        $unico = checkEmailUnique($mail);

        if($unico){
            createEmployee($nombre, $pass, $mail, $tlf, 2);
        }
    }
    if(isset($_POST["activar"])){
        activateEmployee($_POST["id"]);
    }elseif(isset($_POST["desactivar"])){
        deactivateEmployee($_POST["id"]);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="../scripts/empleados.js" defer></script>
    <script src="../scripts/jquery-3.2.1.min.js" defer></script>
    <link rel="stylesheet" href="../estilos.css">
    <title>Document</title>
</head>
<body id="empleados">
    <section class="insertar-empleado">
        <ion-icon id="close-form-empleado" name="close-outline"></ion-icon>
        <form action="#" method="post">
            <div class="form-group">
                <label for="nombre">Nombre del empleado</label>
                <input name="nombre" required type="text" class="form-control" aria-describedby="emailHelp" placeholder="Nombre">
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input required type="tel" pattern="[6-7]{1}[0-9]{8}" name="telefono" class="form-control">
            </div>
            <div class="form-group">
                <label for="correo">Correo electrónico</label>
                <input name="correo" required type="email" class="form-control" placeholder="Correo">
            </div>
            <div class="form-group">
                <label for="pass">Contraseña</label>
                <input name="pass" required type="password" class="form-control" aria-describedby="emailHelp" placeholder="Contraseña">
            </div>
            <input type="submit" class="mt-3 btn btn-primary" value="Crear empleado" name="insertar">
        </form>
    </section>
    <button id="button-insertar-empleado">Insertar empleado</button>
    <section class="tabla-empleados">
        <table>
            <thead>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Modificar estado</th>
            </thead>
            <tbody>
                <?php
                    getEmployees();
                ?>
            </tbody>
        </table>
    </section>
    <?php
            if(isset($unico)){
                if(!$unico){
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Correo electrónico ya registrado</div>";
                }
            }
        ?>
</body>
</html>