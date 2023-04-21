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
    <title>Bienvenido</title>
</head>
<body>
    <section class="container-fluid px-sm-3 px-0 mt-4 row">
        <form action="" method="post" class="col-6 mx-auto row">
            <div class="col d-flex flex-column align-items-center">
                <h3>Mañana</h3>
                <div>
                    <?php
                        require_once("functions.php");
                        $conexion=createConnection();

                        $consulta=$conexion->query("select * from horario");
                        $lista=$consulta->fetch_array(MYSQLI_NUM);

                        echo"
                            <input type='time' name='m_apertura' disabled value='$lista[0]'>
                            <input type='time' name='m_cierre' disabled value='$lista[1]'>
                        ";
                    ?>
                </div>
            </div>
            <div class="col d-flex flex-column align-items-center">
                <h3>Tarde</h3>
                <div>
                    <?php
                        echo"
                            <input type='time' name='t_apertura' disabled value='$lista[2]'>
                            <input type='time' name='t_cierre' disabled value='$lista[3]'>
                        ";
                    ?>
                </div>
            </div>
            <div class='w-100'>
                <input class="w-25 mx-auto d-block mt-3" name="enviar" type="submit" value="Modificar">
            </div>
        </form>
    </section>
</body>
</html>