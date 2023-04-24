<?php

function createConnection(){
    $con = new mysqli('localhost', 'root', '', 'peluqueria');
    $con->set_charset('utf8');
    return $con;
}

function closeSession(){
    if(isset($_POST["cerrar-sesion"])){
        if(isset($_COOKIE['sesion'])){
            unset($_SESSION['user']);
            setcookie("sesion","", time()-3600, '/');              
            echo "<meta http-equiv='refresh' content='0;url=../index.php'>";
        }else{
            unset($_SESSION['user']);
            header("location:../index.php");
        }
    }
}
function getTypeUser($mail){
    $con = createConnection();
    $consulta = $con->prepare("SELECT t.nombre tipo from personas p, tipos t where t.id = p.tipo and correo = ?");
    $consulta->bind_param('s', $mail);
    $consulta->bind_result($tipo);
    $consulta->execute();
    $consulta->fetch();
    $consulta->close();
    $con->close();
    return $tipo;
}
function printMenu(){
    if(isset($_SESSION["user"])){
        if($_SESSION["user"] == "admin@admin.com"){
            echo "<nav class=\"ps-2 navbar navbar-expand-lg navbar-light bg-light\">
                        <a class=\"navbar-brand\" href=\"#\">Peluquería</a>
                        <button class=\"navbar-toggler\" type=\"button\" data-toggle=\"collapse\" data-target=\"#navbarNav\" aria-controls=\"navbarNav\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">
                            <span class=\"navbar-toggler-icon\"></span>
                        </button>
                        <div class=\"collapse navbar-collapse\" id=\"navbarNav\">
                            <ul class=\"navbar-nav\">
                            <li class=\"nav-item active\">
                                <a class=\"nav-link\" href=\"calendario.php\">Calendario</span></a>
                            </li>
                            <li class=\"nav-item\">
                                <a class=\"nav-link\" href=\"empleados.php\">Empleados</a>
                            </li>
                            <li class=\"nav-item\">
                                <a class=\"nav-link\" href=\"servicios.php\">Servicios</a>
                            </li>
                            <li class=\"nav-item\">
                                <a class=\"nav-link\" href=\"horario.php\">Horarios</a>
                            </li>
                            <li lass=\"nav-item\">
                                <form action=\"#\" method=\"post\"><input class=\"nav-link\" type=\"submit\" name=\"cerrar-sesion\" value=\"Cerrar sesión\"></form>
                            </li>
                            </ul>
                        </div>
                </nav>";
        }else{
            echo "<nav class=\"ps-2 navbar navbar-expand-lg navbar-light bg-light\">
                    <a class=\"navbar-brand\" href=\"#\">Peluquería</a>
                    <button class=\"navbar-toggler\" type=\"button\" data-toggle=\"collapse\" data-target=\"#navbarNav\" aria-controls=\"navbarNav\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">
                        <span class=\"navbar-toggler-icon\"></span>
                    </button>
                    <div class=\"collapse navbar-collapse\" id=\"navbarNav\">
                        <ul class=\"navbar-nav\">
                        <li class=\"nav-item active\">
                            <form action=\"#\" method=\"post\"><input type=\"submit\" name=\"cerrar-sesion\" value=\"Cerrar sesión\"></form>
                        </li>
                        </ul>
                    </div>
                </nav>";
        }
    }else{

    }
    
}

function registerUser($nombre, $correo, $pass, $tlf, $tipo){
    $con = createConnection();
    $insercion = $con->prepare("INSERT INTO personas (nombre, correo, pass, telefono, tipo) values (?, ?, ?, ?, ?)");
    $insercion->bind_param('ssssi', $nombre, $correo, $pass, $tlf, $tipo);
    $insercion->execute();
    $insercion->close();
    $con->close();
}

function getClienteID(){
    $con = createConnection();
    $consulta = $con->query("SELECT id from tipos where nombre = 'Cliente'");
    $fila = $consulta->fetch_array(MYSQLI_ASSOC);
    $id = $fila["id"];
    $con->close();
    return $id;
}

function checkLogin($mail, $pass){
    $correcto = true;
    $con = createConnection();
    $consulta = $con->prepare("SELECT count(*) FROM personas WHERE pass = ? and correo = ?");
    $consulta->bind_param('ss', $pass, $mail);
    $consulta->bind_result($count);
    $consulta->execute();
    $consulta->fetch();
    $con->close();
    if($count == 0){
        $correcto = false;
    }

    return $correcto;
}

function getEmployees(){
    $con = createConnection();
    $consulta = $con->query("SELECT id, nombre, correo, telefono, activo from personas where tipo = 2");
    while($fila = $consulta->fetch_array(MYSQLI_ASSOC)){
        echo "<tr>
                <td>$fila[nombre]</td>
                <td>$fila[correo]</td>
                <td>$fila[telefono]</td>";    
            if($fila["activo"] == 1){
                echo "<td><form action=\"#\" method=\"post\">
                    <input hidden name=\"id\" value=\"$fila[id]\">
                    <input name=\"desactivar\" value=\"Desactivar\" type=\"submit\" class=\"btn btn-danger\">
                </form></td>";
            }else{
                echo "<td><form action=\"#\" method=\"post\">
                <input hidden name=\"id\" value=\"$fila[id]\">
                <input name=\"activar\" value=\"Activar\" type=\"submit\" class=\"btn btn-success\">
            </form></td>";
            }
            echo "</tr>";
    }
    $con->close();
}

function activateEmployee($id){
    $con = createConnection();
    $activacion = $con->prepare("UPDATE personas set activo = 1 where id = ?");
    $activacion->bind_param('i', $id);
    $activacion->execute();
    $activacion->close();
    $con->close();
}
function deactivateEmployee($id){
    $con = createConnection();
    $activacion = $con->prepare("UPDATE personas set activo = 0 where id = ?");
    $activacion->bind_param('i', $id);
    $activacion->execute();
    $activacion->close();
    $con->close();
}

function checkEmailUnique($mail){
    $unico = true;
    $con = createConnection();
    $consulta = $con->prepare("SELECT count(*) from personas where correo = ?");
    $consulta->bind_param('s', $mail);
    $consulta->bind_result($count);
    $consulta->execute();
    $consulta->fetch();
    $consulta->close();
    $con->close();
    if($count == 1){
        $unico = false;
    }
    return $unico;
}

function createEmployee($nombre, $pass, $mail, $telefono, $tipo){
    $con = createConnection();
    $insercion = $con->prepare("INSERT INTO personas (nombre, correo, pass, telefono, tipo) values (?,?,?,?,?)");
    $insercion->bind_param('ssssi', $nombre, $mail, $pass, $telefono, $tipo);
    $insercion->execute();
    $insercion->close();
    $con->close();
}