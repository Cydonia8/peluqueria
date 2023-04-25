<?php
    session_start();
    require_once("functions.php");
    closeSession();

    $conexion=createConnection();
    if(isset($_POST['enviar'])){
        $preparada=$conexion->prepare("update horario set m_apertura=?,m_cierre=?,t_apertura=?,t_cierre=?,m_plantilla=?,t_plantilla=?");
        $preparada->bind_param("ssssii",$_POST['m_apertura'],$_POST['m_cierre'],$_POST['t_apertura'],$_POST['t_cierre'],$_POST['m_plantilla'],$_POST['t_plantilla']);
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
    <link rel="stylesheet" href="estilos.css">
    <script src="../scripts/horario.js" defer></script>
    <title>Bienvenido</title>
</head>
<body>
    <?php
        printMenu();
    ?>
    <section class="container-fluid px-sm-3 px-0 mt-4 row">
        <form action="" method="post" class="col-6 mx-auto row">
            <div class="col d-flex flex-column align-items-center">
                <h3>Mañana</h3>
                <div>
                    <?php
                        $consulta=$conexion->query("select * from horario");
                        $lista=$consulta->fetch_array(MYSQLI_NUM);

                        echo"
                            <span>Horario:</span>
                            <input type='time' name='m_apertura' disabled value='$lista[0]'>
                            <span>-</span>
                            <input type='time' name='m_cierre' disabled value='$lista[1]'>
                            <br><span>Plantilla:</span>
                            <input type='number' name='m_plantilla' disabled value='$lista[4]'>
                        ";
                    ?>
                </div>
            </div>
            <div class="col d-flex flex-column align-items-center">
                <h3>Tarde</h3>
                <div>
                    <?php
                        echo"
                            <span>Horario:</span>
                            <input type='time' name='t_apertura' disabled value='$lista[2]'>
                            <span>-</span>
                            <input type='time' name='t_cierre' disabled value='$lista[3]'>
                            <br><span>Plantilla:</span>
                            <input type='number' name='t_plantilla' disabled value='$lista[5]'>
                        ";
                        $consulta->close();
                        $conexion->close();
                    ?>
                </div>
            </div>
            <div class='w-100'>
                <button id="mod" type="button" class="d-block btn btn-primary mx-auto mt-3">Modificar</button>
                <input type="submit" class="btn btn-primary mx-auto mt-3 d-none" name="enviar" value="Enviar">
            </div>
        </form>
    </section>
</body>
</html>