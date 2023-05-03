<?php
    session_start();
    require_once("functions.php");
    closeSession();

    $conexion=createConnection();
    if(isset($_POST['enviar'])){
        $preparada=$conexion->prepare("update horario set m_apertura=?,m_cierre=?,t_apertura=?,t_cierre=? where id=1");
        $preparada->bind_param("ssss",$_POST['m_apertura'],$_POST['m_cierre'],$_POST['t_apertura'],$_POST['t_cierre']);
        $preparada->execute();
        $preparada->close();
        header("Refresh:0");
    }
    if(isset($_POST['insertar']) or isset($_POST['editar'])){
        $inicio_m = $_POST["inicio_m"] != '' ? $_POST["inicio_m"] : NULL;
        $fin_m = isset($_POST["fin_m"]) ? $_POST["fin_m"] : NULL;
        $inicio_t = $_POST["inicio_t"] != '' ? $_POST["inicio_t"] : NULL;
        $fin_t = isset($_POST["fin_t"]) ? $_POST["fin_t"] : NULL;
        if(isset($_POST['insertar'])){
            $preparada=$conexion->prepare("insert into horario (dia,m_apertura,m_cierre,t_apertura,t_cierre) values (?,?,?,?,?)");
            $preparada->bind_param("sssss",$_POST['dia'],$inicio_m,$fin_m,$inicio_t,$fin_t);
            $preparada->execute();
            $preparada->close();
            header("Refresh:0");
        }else if(isset($_POST['editar'])){
            $preparada=$conexion->prepare("update horario set dia=?,m_apertura=?,m_cierre=?,t_apertura=?,t_cierre=? where id=?");
            $preparada->bind_param("sssssi",$_POST['dia'],$inicio_m,$fin_m,$inicio_t,$fin_t,$_POST['id']);
            $preparada->execute();
            $preparada->close();
            header("Refresh:0");
        }else if(isset($_POST['borrar'])){
            $preparada=$conexion->prepare("delete from horario where id=?");
            $preparada->bind_param("i",$_POST['id']);
            $preparada->execute();
            $preparada->close();
            header("Refresh:0");
        }
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
                            <input type='time' name='m_apertura' disabled value='$lista[1]'>
                            <span>-</span>
                            <input type='time' name='m_cierre' disabled value='$lista[2]'>
                            ";
                            // <br><span>Plantilla:</span>
                            // <input type='number' name='m_plantilla' disabled value='$lista[4]'>
                    ?>
                </div>
            </div>
            <div class="col d-flex flex-column align-items-center">
                <h3>Tarde</h3>
                <div>
                    <?php
                        echo"
                            <span>Horario:</span>
                            <input type='time' name='t_apertura' disabled value='$lista[3]'>
                            <span>-</span>
                            <input type='time' name='t_cierre' disabled value='$lista[4]'>
                        ";
                            // <br><span>Plantilla:</span>
                            // <input type='number' name='t_plantilla' disabled value='$lista[5]'>
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

    <section class="container-fluid px-sm-3 px-0 mt-4 row mx-auto">
        <div class='mx-auto'>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="Añadir nuevo">Añadir nuevo</button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Dia</th>
                    <th>Mañana</th>
                    <th>Tarde</th>
                    <th>Editar</th>
                    <th>Borrar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $conexion=createConnection();

                    $consulta=$conexion->query("select * from horario where id!=1");
                    while($lista=$consulta->fetch_array(MYSQLI_ASSOC)){
                        if($lista['dia']<date("Y-m-d")){
                            $preparada=$conexion->prepare("delete from horario where id=?");
                            $preparada->bind_param("i",$lista['id']);
                            $preparada->execute();
                            $preparada->close();
                            // header("Refresh:0");
                        }else{
                            echo "
                            <tr>
                                <td>$lista[dia]</td>
                                <td>".cortarSeg($lista['m_apertura'])."-".cortarSeg($lista['m_cierre'])."</td>
                                <td>".cortarSeg($lista['t_apertura'])."-".cortarSeg($lista['t_cierre'])."</td>
                                <td>
                                    <button data-id='$lista[id]' type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#exampleModal' data-bs-whatever='Editar'>Editar</button>
                                </td>
                                <td>
                                    <form action='#' method='post'>
                                        <input type='hidden' name='id' value='$lista[id]'>
                                        <input class='recargar btn btn-primary' type='submit' name='estado' value='Borrar'></input>
                                    </form>
                                </td>
                            </tr>
                            ";
                        }
                    }
                    $consulta->close();
                    $conexion->close();
                ?>
            </tbody>
        </table>
    </section>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="dia">Fecha</label>
                            <input name="dia" type="date" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="inicio_m">Inicio del turno de mañana</label>
                            <input id="inicio_m" name="inicio_m" type="time" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="fin_m">Fin del turno de mañana</label>
                                <input disabled id="fin_m" name="fin_m" type="time" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="inicio_t">Inicio del turno de tarde</label>
                            <input id="inicio_t" name="inicio_t" type="time" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="fin_t">Fin del turno de tarde</label>
                            <input disabled id="fin_t" name="fin_t" type="time" class="form-control">
                        </div>
                        <input type='hidden' name='id' value=''>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="submit" class="recargar btn btn-primary" name="" value="Enviar">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>