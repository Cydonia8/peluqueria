<?php
    session_start();
    require_once "php/functions.php";
    if(isset($_POST["login"])){
        $mail = $_POST["correo"];
        $pass = $_POST["pass"];
        $accede = checkLogin($mail,$pass);

        if($accede){
            echo "<meta http-equiv='refresh' content='0;url=php/calendario.php'>";
        }
    }elseif(isset($_POST["register"])){
        $nombre = $_POST["nombre"];
        $mail = $_POST["correo"];
        $pass = $_POST["pass"];
        $tlf = $_POST["telefono"];
        $id = getClienteID();
        registerUser($nombre, $mail, $pass, $tlf, $id);
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
    <link rel="stylesheet" href="estilos.css">
    <script src="scripts/index.js" defer></script>
    <script src="scripts/jquery-3.2.1.min.js" defer></script>
    <title>Bienvenido</title>
</head>
<body id = "index">
    <section class="formularios hidden">
        <button class="rounded-pill p-2" id="atras">Atrás</button>
        <form data-form="login" action="#" method="post">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Correo</label>
                <input type="email" class="form-control" name="correo" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Contraseña</label>
                <input type="password" name="pass" class="form-control">
            </div>
            <input type="submit" class="btn btn-primary" name="login" value="Iniciar sesión">
        </form>
        <form data-form="register" action="#" method="post">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Nombre</label>
                <input required type="text" class="form-control" name="nombre" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Correo</label>
                <input required type="email" name="correo" class="form-control">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Contraseña</label>
                <input required type="password" name="pass" class="form-control">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Teléfono</label>
                <input required type="tel" pattern="[6-7]{1}[0-9]{8}" name="telefono" class="form-control">
            </div>
            <input type="submit" name="register" class="btn btn-primary" value="Registrarme">
        </form>
    </section>
    <section class="buttons">
        <button class="login-register-buttons" data-button="login">Iniciar sesión</button>
        <button class="login-register-buttons" data-button="register">Registrarse</button>
        <?php
            if(isset($_POST['login'])){
                if(!$accede){
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Credenciales incorrectas</div>";
                }
            }elseif(isset($_POST["register"])){
                echo "<div class=\"alert alert-success\" role=\"alert\">Usuario creado correctamente</div>";
            }
        ?>
    </section>
    
</body>
</html>