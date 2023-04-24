<?php
    session_start();
    require_once("functions.php");
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
    <script src="../scripts/servicios.js" defer></script>
    <title>Bienvenido</title>
</head>
<body>
    <?php
        printMenu();
    ?>
    <section class="container-fluid px-sm-3 px-0 mt-4 row">
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="Añadir nuevo">Añadir nuevo</button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Duración</th>
                    <th>Precio</th>
                    <th>Editar</th>
                    <th>Activar/Desactivar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    
                    $conexion=createConnection();

                    $consulta=$conexion->query("select * from servicios");
                    while($lista=$consulta->fetch_array(MYSQLI_ASSOC)){
                        if($lista["activo"]==1){
                            $act="Desactivar";
                            $valor="0";
                        }else{
                            $act="Activar";
                            $valor="1";
                        }
                        echo "
                        <tr>
                            <td>$lista[nombre]</td>
                            <td>$lista[duracion]</td>
                            <td>$lista[precio]</td>
                            <td>
                                <button data-id='$lista[id]' type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#exampleModal' data-bs-whatever='Editar'>Editar</button>
                            </td>
                            <td>
                                <form action='#' method='post'>
                                    <input type='hidden' name='id' value='$lista[id]'>
                                    <input type='hidden' name='valor' value='$valor'>
                                    <input class='btn btn-primary' type='submit' name='estado' value='$act'></input>
                                </form>
                            </td>
                        </tr>
                        ";
                    }
                    $consulta->close();
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
                            <label for="recipient-name" class="col-form-label">Nombre:</label>
                            <input type="text" name="nombre" class="form-control" id="recipient-name" required>
                        </div>
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Duración:</label>
                            <input type="time" name="duracion" class="form-control" id="recipient-name" required>
                        </div>
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Precio:</label>
                            <input type="precio" name="precio" class="form-control" id="recipient-name" required>
                        </div>
                        <input type='hidden' name='id' value=''>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" name="" value="Enviar">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
        if(isset($_POST['insertar'])){
            $preparada=$conexion->prepare("insert into servicios (nombre,duracion,precio) values (?,?,?)");
            $preparada->bind_param("ssd",$_POST['nombre'],$_POST['duracion'],$_POST['precio']);
            $preparada->execute();
            $preparada->close();
            $conexion->close();
            header("Refresh:0");
        }else if(isset($_POST['editar'])){
            $preparada=$conexion->prepare("update servicios set nombre=?, duracion=?, precio=? where id=?");
            $preparada->bind_param("ssdi",$_POST['nombre'],$_POST['duracion'],$_POST['precio'],$_POST['id']);
            $preparada->execute();
            $preparada->close();
            $conexion->close();
            // header("Refresh:0");
        }else if(isset($_POST['estado'])){
            $preparada=$conexion->prepare("update servicios set activo=? where id=?");
            $preparada->bind_param("ii",$_POST['valor'],$_POST['id']);
            $preparada->execute();
            $preparada->close();
            $conexion->close();
            // header("Refresh:0");
        }
    ?>
</body>
</html>