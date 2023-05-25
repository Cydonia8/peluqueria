<?php
    session_start();
    require_once("functions.php");
    comprobarSesionAdmin();
    closeSession();

    $conexion=createConnection();
    if(isset($_POST['enviar'])){
        if($_POST['dias']!=0){
            $preparada=$conexion->prepare("update horario set m_apertura=?,m_cierre=?,t_apertura=?,t_cierre=? where id=?");
            $preparada->bind_param("ssssi",$_POST['m_apertura'],$_POST['m_cierre'],$_POST['t_apertura'],$_POST['t_cierre'],$_POST['dias']);
        }else{
            $preparada=$conexion->prepare("update horario set m_apertura=?,m_cierre=?,t_apertura=?,t_cierre=? where id<=7");
            $preparada->bind_param("ssss",$_POST['m_apertura'],$_POST['m_cierre'],$_POST['t_apertura'],$_POST['t_cierre']);
        }
        $preparada->execute();
        $preparada->close();
        header("Refresh:0");
    }
    if(isset($_POST['enviar2'])){
        if(isset($_POST['descanso'])){
            foreach($_POST["descanso"] as $valor){
                $preparada=$conexion->prepare("select count(*) from descanso where dia=?");
                $preparada->bind_param("i",$valor);
                $preparada->bind_result($cant);
                $preparada->execute();
                $preparada->fetch();
                $preparada->close();
                if($cant==0){
                    $insert = $conexion->prepare("INSERT INTO descanso values (?)");
                    $insert->bind_param('i',$valor);
                    $insert->execute();
                    $insert->close();
                }
            }
            $preparada=$conexion->query("select dia from descanso");
            $insertados=[];
            while($fila=$preparada->fetch_array(MYSQLI_ASSOC)){
                $insertados[]=$fila['dia'];
            }
            $preparada->close();
            foreach($insertados as $ser){
                if(!in_array($ser,$_POST['descanso'])){
                    $delete=$conexion->prepare("delete from descanso where dia=?");
                    $delete->bind_param("i",$ser);
                    $delete->execute();
                    $delete->close();
                }
            }
        }else{
            $delete=$conexion->query("delete from descanso");
            $delete->close();
        }
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
        }
    }
    if(isset($_POST['borrar'])){
        $preparada=$conexion->prepare("delete from horario where id=?");
        $preparada->bind_param("i",$_POST['id']);
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
    <div class='row w-100 mx-auto'>
        <section class="container-fluid px-sm-3 px-0 mt-4 row col-12 col-md-7 mx-auto">
            <form action="" method="post" class="col-12 mx-auto row">
                <div>
                    <select name="dias" id="select_dias">
                        <option selected value="0">Todos</option>
                        <option value="1">Lunes</option>
                        <option value="2">Martes</option>
                        <option value="3">Miércoles</option>
                        <option value="4">Jueves</option>
                        <option value="5">Viernes</option>
                        <option value="6">Sábado</option>
                        <option value="7">Domingo</option>
                    </select>
                </div>
                <div class="col d-flex flex-column align-items-center p-1">
                    <h3>Mañana</h3>
                    <div class=''>
                        <span class='d-md-block d-sm-inline d-xl-inline d-block text-center'>Horario:<br class='d-xl-none d-md-block d-sm-none'></span>
                        <input type='time' name='m_apertura' disabled value=''>
                        <span>-</span>
                        <input type='time' name='m_cierre' disabled value=''>
                    </div>
                </div>
                <div class="col d-flex flex-column align-items-center p-1">
                    <h3>Tarde</h3>
                    <div>
                        <span class='d-md-block d-sm-inline d-xl-inline d-block text-center'>Horario:<br class='d-xl-none d-md-block d-sm-none'></span>
                        <input type='time' name='t_apertura' disabled value=''>
                        <span>-</span>
                        <input type='time' name='t_cierre' disabled value=''>
                    </div>
                </div>
                <div class='w-100'>
                    <button type="button" class="mod d-block btn btn-primary mx-auto mt-3">Modificar</button>
                    <input type="submit" class="btn btn-primary mx-auto mt-3 d-none" name="enviar" value="Enviar">
                </div>
            </form>
        </section>
        <section class="container-fluid px-sm-3 px-0 mt-4 row col-12 col-md-5 mx-auto">
            <form action="" method="post" class="col-12 mx-auto row p-0">
                <h3 class='text-center'>Dias que cierra</h3>
                <div class="col d-flex align-items-center justify-content-md-between justify-content-evenly p-0" id='dias_semana'>
                    <?php
                            $consulta=$conexion->query("select dia from descanso");
                            $check=[];
                            while($fila=$consulta->fetch_array(MYSQLI_ASSOC)){
                                $check[]=$fila['dia'];
                            }
                            
                            echo" <div class='form-check d-flex flex-column align-items-center p-0'>
                                    <input class='form-check-input mx-auto' disabled ".(in_array(1,$check) ? "checked":"")." type='checkbox' name='descanso[]' value='1' id='lunes'>
                                    <label class='form-check-label' for='lunes'>Lunes</label>
                                </div>
                                <div class='form-check d-flex flex-column align-items-center p-0'>
                                    <input class='form-check-input mx-auto' disabled ".(in_array(2,$check) ? "checked":"")." type='checkbox' name='descanso[]' value='2' id='martes'>
                                    <label class='form-check-label' for='martes'>Martes</label>
                                </div>
                                <div class='form-check d-flex flex-column align-items-center p-0'>
                                    <input class='form-check-input mx-auto' disabled ".(in_array(3,$check) ? "checked":"")." type='checkbox' name='descanso[]' value='3' id='miercoles'>
                                    <label class='form-check-label' for='miercoles'>Miércoles</label>
                                </div>
                                <div class='form-check d-flex flex-column align-items-center p-0'>
                                    <input class='form-check-input mx-auto' disabled ".(in_array(4,$check) ? "checked":"")." type='checkbox' name='descanso[]' value='4' id='jueves'>
                                    <label class='form-check-label' for='jueves'>Jueves</label>
                                </div>
                                <div class='form-check d-flex flex-column align-items-center p-0'>
                                    <input class='form-check-input mx-auto' disabled ".(in_array(5,$check) ? "checked":"")." type='checkbox' name='descanso[]' value='5' id='viernes'>
                                    <label class='form-check-label' for='viernes'>Viernes</label>
                                </div>
                                <div class='form-check d-flex flex-column align-items-center p-0'>
                                    <input class='form-check-input mx-auto' disabled ".(in_array(6,$check) ? "checked":"")." type='checkbox' name='descanso[]' value='6' id='sabado'>
                                    <label class='form-check-label' for='sabado'>Sábado</label>
                                </div>
                                <div class='form-check d-flex flex-column align-items-center p-0'>
                                    <input class='form-check-input mx-auto' disabled ".(in_array(0,$check) ? "checked":"")." type='checkbox' name='descanso[]' value='0' id='domingo'>
                                    <label class='form-check-label' for='domingo'>Domingo</label>
                                </div>";

                        $consulta->close();
                    ?>
                </div>
                <div class='w-100'>
                    <button type="button" class="mod d-block btn btn-primary mx-auto mt-3">Modificar</button>
                    <input type="submit" class="btn btn-primary mx-auto mt-3 d-none" name="enviar2" value="Enviar">
                </div>
            </form>
        </section>
    </div>

    <section class="container-fluid px-sm-3 px-0 mt-4 row mx-auto">
        <div class='mx-auto'>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="Añadir nuevo">Añadir nuevo</button>
        </div>

        <div class='contenedor_tabla'>
            <table id='tabla_horario'>
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
                        $i=0;
                        $consulta=$conexion->query("select * from horario where id>7");
                        while($lista=$consulta->fetch_array(MYSQLI_ASSOC)){
                            if($lista['dia']>=date("Y-m-d")){
                                $i++;
                                echo "
                                <tr>
                                    <td>".formatoFecha($lista['dia'])."</td>
                                    <td>".cortarSeg($lista['m_apertura'])."-".cortarSeg($lista['m_cierre'])."</td>
                                    <td>".cortarSeg($lista['t_apertura'])."-".cortarSeg($lista['t_cierre'])."</td>
                                    <td>
                                        <button data-id='$lista[id]' type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#exampleModal' data-bs-whatever='Editar'>Editar</button>
                                    </td>
                                    <td>
                                        <form action='#' method='post'>
                                            <input type='hidden' name='id' value='$lista[id]'>
                                            <input class='recargar btn btn-primary' type='submit' name='borrar' value='Borrar'></input>
                                        </form>
                                    </td>
                                </tr>
                                ";
                            }
                        }
                        if($i==0){
                            echo "
                            <tr>
                                <td colspan='5'>No se ha modificado el horario para ningún día</td>
                            </tr>
                            ";
                        }
                        $consulta->close();
                        $conexion->close();
                    ?>
                </tbody>
            </table>
        </div>
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