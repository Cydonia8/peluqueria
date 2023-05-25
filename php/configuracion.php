<?php
    session_start();
    require_once("functions.php");
    comprobarSesionAdmin();
    closeSession();

    $conexion=createConnection();
    if(isset($_POST['enviar'])){
        if(isset($_POST['cancelar'])){
            $cancelar=1;
        }else{
            $cancelar=0;
        }
        $preparada=$conexion->prepare("update configuracion set nombre=?,correo=?,telefono=?,empresa=?,direccion=?,cancelar=?");
        $preparada->bind_param("sssssi",$_POST['nombre'],$_POST['correo'],$_POST['telefono'],$_POST['empresa'],$_POST['direccion'],$cancelar);
        $preparada->execute();
        $preparada->close();
        header("Refresh:0");
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
    <script src="../scripts/horario.js" defer></script>
    <title>Bienvenido</title>
</head>
<body>
    <?php
        printMenu();
    ?>
    <section class="container-fluid px-sm-3 px-0 mt-4 row mx-auto">
        <form action="" method="post" class='row col-10 mx-auto d-flex justify-content-between'>
            <?php
                $consulta=$conexion->query("select * from configuracion");
                $fila=$consulta->fetch_array(MYSQLI_ASSOC);
                if($fila['cancelar']==1){
                    $valor='checked';
                }else{
                    $valor='';
                }
                echo '
                <div class="col-5">
                    <div class="d-flex flex-column mb-3">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" value="'.$fila["nombre"].'">
                    </div>
                    <div class="d-flex flex-column mb-3">
                        <label for="correo">Correo electrónico</label>
                        <input type="text" name="correo" value="'.$fila["correo"].'">
                    </div>
                    <div class="d-flex flex-column mb-3">
                        <label for="telefono">Teléfono</label>
                        <input type="text" name="telefono" value="'.$fila["telefono"].'">
                    </div>
                </div>
                <div class="col-5">
                    <div class="d-flex flex-column mb-3">
                        <label for="empresa">Nombre de la empresa</label>
                        <input type="text" name="empresa" value="'.$fila["empresa"].'">
                    </div>
                    <div class="d-flex flex-column mb-3">
                        <label for="direccion">Dirección de la empresa</label>
                        <input type="text" name="direccion" value="'.$fila["direccion"].'">
                    </div>
                    <div class="d-flex flex-column mb-3">
                        <span >Permitir cancelación de las citas</span>
                        <label for="cancelar" class="text-secondary"><input type="checkbox" name="cancelar" '.$valor.'> Al marcarse permite a los clientes cancelar sus citas con al menos un día de antelación</label>
                    </div>
                </div>
                ';
            ?>
            <div class='row col-12'>
                <input class='btn btn-primary col-1' type="submit" name='enviar' value="Enviar">
            </div>
        </form>
    </section>
</body>
</html>