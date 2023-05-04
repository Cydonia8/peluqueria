<?php
    session_start();
    require_once "functions.php";
    closeSession();
    if(isset($_POST["insertar"])){
        $nombre = $_POST["nombre"];
        $pass = $_POST["pass"];
        $mail = $_POST["correo"];
        $tlf = $_POST["telefono"];
        $unico = checkEmailUnique($mail);

        $inicio_m = $_POST["inicio_m"] != '' ? $_POST["inicio_m"] : NULL;
        $fin_m = isset($_POST["fin_m"]) ? $_POST["fin_m"] : NULL;
        $inicio_t = $_POST["inicio_t"] != '' ? $_POST["inicio_t"] : NULL;
        $fin_t = isset($_POST["fin_t"]) ? $_POST["fin_t"] : NULL;
        // $inicio_m = $_POST["inicio_m"];
        // $fin_m = $_POST["fin_m"];
        // $inicio_t = $_POST["inicio_t"];
        // $fin_t = $_POST["fin_t"];

        if($unico){
            createEmployee($nombre, $pass, $mail, $tlf, 2);           
            $id = getLastID();
            employeeShift($id, $inicio_m, $fin_m, $inicio_t, $fin_t);
        }
    }else if(isset($_POST['editar'])){
        $conexion=createConnection();
        if($_POST['pass']==''){
            $preparada=$conexion->prepare("update personas set nombre=?, correo=?, telefono=? where id=?");
            $preparada->bind_param("sssi",$_POST['nombre'],$_POST['correo'],$_POST['telefono'],$_POST['id']);
        }else{
            $preparada=$conexion->prepare("update personas set nombre=?, correo=?, telefono=?, pass=? where id=?");
            $preparada->bind_param("ssssi",$_POST['nombre'],$_POST['correo'],$_POST['telefono'],$_POST['pass'],$_POST['id']);
        }
        $preparada->execute();
        $preparada->close();
        
        $inicio_m = $_POST["inicio_m"] != '' ? $_POST["inicio_m"] : NULL;
        $fin_m = isset($_POST["fin_m"]) ? $_POST["fin_m"] : NULL;
        $inicio_t = $_POST["inicio_t"] != '' ? $_POST["inicio_t"] : NULL;
        $fin_t = isset($_POST["fin_t"]) ? $_POST["fin_t"] : NULL;
        $preparada=$conexion->prepare("update trabaja set m_inicio=?, m_fin=?, t_inicio=?, t_fin=? where empleado=?");
        $preparada->bind_param("ssssi",$inicio_m,$fin_m,$inicio_t,$fin_t,$_POST['id']);
        $preparada->execute();
        $preparada->close();
        header("Refresh:0");
    }
    if(isset($_POST["activar"])){
        activateEmployee($_POST["id"]);
    }elseif(isset($_POST["desactivar"])){
        deactivateEmployee($_POST["id"]);
    }

    if(isset($_POST["programar"])){
        $inicio = $_POST["inicio_desact"];
        $fin = $_POST["fin_desact"] != '' ? $_POST["fin_desact"] : NULL;
        setDeactivation($_POST["id"], $inicio, $fin);
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
    <?php
        printMenu();
    ?>
    <section class="tabla-empleados container-fluid px-sm-3 px-0 mt-4 row">
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="Añadir nuevo">Añadir nuevo</button>
        </div>
        <table>
            <thead>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Horario</th>
                <th>Editar</th>
                <th>Activar/Desactivar</th>
            </thead>
            <tbody>
                <?php
                    getEmployees();
                    $horario = getSchedule();
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
                            <label for="nombre">Nombre del empleado</label>
                            <input name="nombre" required type="text" class="form-control" aria-describedby="emailHelp" placeholder="Nombre">
                        </div>
                        <div class="mb-3">
                            <label for="correo">Correo electrónico</label>
                            <input name="correo" required type="email" class="form-control" placeholder="Correo">
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input required type="tel" pattern="[6-7]{1}[0-9]{8}" name="telefono" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="pass">Contraseña</label>
                            <input name="pass" required type="password" class="form-control" aria-describedby="emailHelp" placeholder="Contraseña">
                        </div>
                        <div class="mb-3 d-flex align-items-center">
                                <label for="inicio_m">Inicio del turno de mañana</label>
                                <input id="inicio_m" name="inicio_m"  type="time" class="form-control" <?php echo "min='$horario[apertura_m]'"; ?>>
                                <label for="fin_m">Fin del turno de mañana</label>
                                <input disabled <?php echo "max='$horario[cierre_m]'"; ?> id="fin_m" name="fin_m"  type="time" class="form-control">
                        </div>
                        <!-- <div class="mb-3">
                                
                        </div> -->
                        <div class="mb-3 d-flex align-items-center">
                                <label for="inicio_t">Inicio del turno de tarde</label>
                                <input id="inicio_t" name="inicio_t"  type="time" class="form-control" <?php echo "min='$horario[apertura_t]'"; ?>>
                                <label for="fin_t">Fin del turno de tarde</label>
                                <input disabled <?php echo "max='$horario[cierre_t]'"; ?> id="fin_t" name="fin_t"  type="time" class="form-control">
                        </div>
                        <input type='hidden' name='id' value=''>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" value="Crear empleado" name="insertar">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalDesactivar" tabindex="-1" aria-labelledby="modalDesactivar" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalDesactivarLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="post">
                    <input hidden name="id" class="id-js">
                    <div class="modal-body">
                        <div class="mb-3">
                                <label for="inicio_desact">Inicio de la desactivación</label>
                                <input id="inicio_desact" name="inicio_desact" required type="date" class="form-control">
                        </div>
                        <div class="mb-3">
                                <label for="fin_desact">Fin de la desactivación</label>
                                <input id="fin_desact" name="fin_desact" type="date" class="form-control">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" value="Programar" name="programar">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
        if(isset($unico)){
            if(!$unico){
                echo "<div class=\"alert alert-danger\" role=\"alert\">Correo electrónico ya registrado</div>";
            }
        }
    ?>
    
</body>
</html>