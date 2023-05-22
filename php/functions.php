<?php

function createConnection(){
    $con = new mysqli('localhost', 'root', '', 'peluqueria');
    $con->set_charset('utf8');
    return $con;
}

function comprobarSesionBasica(){
    if(!isset($_SESSION["user"])){
        header("location:../index.php");
    }
}

function comprobarSesionAdmin(){
    if(!isset($_SESSION["user"])){
        header("location:../index.php");
    }else if($_SESSION["tipo"]!='Administrador'){
        header("location:calendario.php");
    }
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
function formatoFecha($fecha){
    $fecha_buena=date_format(date_create($fecha),"d-m-Y");
    return $fecha_buena;
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
            echo "<nav class=\"ps-2 navbar navbar-expand-lg\">
                    <div class='container-fluid'>
                        <a class=\"navbar-brand\" href=\"calendario.php\"><img src='../assets/logo-blanco.png' class='img-fluid'></a>
                        <button class=\"navbar-toggler bg-light\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#navbarNav\" aria-controls=\"navbarNav\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">
                            <span class=\"navbar-toggler-icon\"></span>
                        </button>
                        <div class=\"collapse navbar-collapse justify-content-center\" id=\"navbarNav\">
                            <ul class=\"navbar-nav gap-3\">
                            <li class=\"nav-item active\">
                                <a class=\"nav-link\" href=\"calendario.php\">Calendario</span></a>
                            </li>
                            <li class=\"nav-item\">
                                <a class=\"nav-link\" href=\"empleados.php\">Empleados</a>
                            </li>
                            <li class=\"nav-item\">
                                <a class=\"nav-link\" href=\"pedir_cita.php\">Pedir cita</a>
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
                    </div>
                </nav>";
        }else{
            echo "<nav class=\"ps-2 navbar navbar-expand-lg navbar-light \">
                    <a class=\"navbar-brand\" href=\"calendario.php\"><img src='../assets/logo-blanco.png' class='img-fluid'></a>
                    <button class=\"navbar-toggler bg-light\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#navbarNav\" aria-controls=\"navbarNav\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">
                        <span class=\"navbar-toggler-icon\"></span>
                    </button>
                    <div class=\"collapse navbar-collapse justify-content-center\" id=\"navbarNav\">
                        <ul class=\"navbar-nav gap-3\">
                        
                        <li class=\"nav-item\">
                            <a class=\"nav-link\" href=\"calendario.php\">Mis citas</a>
                        </li>
                        <li class=\"nav-item\">
                            <a class=\"nav-link\" href=\"pedir_cita.php\">Pedir cita</a>
                        </li>
                        <li class=\"nav-item active\">
                            <form action=\"#\" method=\"post\"><input class=\"nav-link\" type=\"submit\" name=\"cerrar-sesion\" value=\"Cerrar sesión\"></form>
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
    if($count != 1){
        $correcto = false;
    }

    return $correcto;
}

function getEmployees(){
    $con = createConnection();
    $consulta = $con->query("SELECT id, nombre, correo, telefono, activo, m_inicio, m_fin, t_inicio, t_fin from personas,trabaja where id=empleado and tipo = 2");
    while($fila = $consulta->fetch_array(MYSQLI_ASSOC)){
        if(is_null($fila['m_inicio'])){
            $rango='tarde';
            $horario=cortarSeg($fila['t_inicio'])."-".cortarSeg($fila['t_fin']);
        }else if(is_null($fila['t_inicio'])){
            $rango='mañana';
            $horario=cortarSeg($fila['m_inicio'])."-".cortarSeg($fila['m_fin']);
        }else{
            $rango='dia';
            $horario=cortarSeg($fila['m_inicio'])."-".cortarSeg($fila['m_fin'])."/".cortarSeg($fila['t_inicio'])."-".cortarSeg($fila['t_fin']);
        }
        echo "<tr>
                <td>$fila[nombre]</td>
                <td>$fila[correo]</td>
                <td>$fila[telefono]</td>    
                <td data-rango='$rango'>$horario</td>
                <td>
                    <button class='btn btn-primary' type='button' data-bs-toggle='collapse' data-bs-target='#servicios$fila[id]' aria-expanded='false' aria-controls='collapseExample'>Mostrar</button>
                </td>
                <td>
                    <button data-id='$fila[id]' type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#exampleModal' data-bs-whatever='Editar'>Editar</button>
                </td>";    
            if($fila["activo"] == 1){
                echo "<td class='d-flex flex-column gap-3 align-items-center'><form action=\"#\" method=\"post\">
                    
                    <button data-id='$fila[id]' data-bs-toggle=\"modal\" data-bs-target=\"#modalDesactivar\" data-bs-whatever=\"Programar desactivacion\" class=\"btn programar-des btn-info\">Programar desactivación</button>
                    </td>";
            }else{
                echo "<td><form action=\"#\" method=\"post\">
                <input hidden name=\"id\" value=\"$fila[id]\">
                <input name=\"activar\" value=\"Activar\" type=\"submit\" class=\"btn btn-success\">
            </form></td>";
            }
            $preparada = $con->prepare("select servicio,nombre from realiza,servicios where servicio=id and empleado=?");
            $preparada->bind_param('i', $fila['id']);
            $preparada->bind_result($servicio,$nombre);
            $preparada->execute();
            echo "</tr>
            <tr class='border border-top-0 border-end-0 border-start-0 border-dark border-2'>
                <td class='p-0' colspan=7>
                    <div class='collapse' id='servicios$fila[id]'>
                        <p class='m-0 p-3 lista_servicios'>- ";
            while($preparada->fetch()){
                echo "<span data-ser='$servicio'>$nombre</span> - ";
            }
            echo "      </p>
                    </div>
                </td>
            </tr>";
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

function getIDCliente($mail){
    $con = createConnection();
    $consulta = $con->prepare("SELECT id from personas where correo = ?");
    $consulta->bind_param('s', $mail);
    $consulta->bind_result($id);
    $consulta->execute();
    $consulta->fetch();
    $consulta->close();
    $con->close();
    return $id;
}

function getLastID(){
    $con = createConnection();
    $consulta_id = $con->query("SELECT id from personas order by id desc limit 1");
    $fila = $consulta_id->fetch_array(MYSQLI_ASSOC);
    $id = $fila["id"];
    $con->close();    
    return $id;
}

function employeeShift($id, $m_inicio, $m_fin, $t_inicio, $t_fin){
    $con = createConnection();
    $insert = $con->prepare("INSERT into trabaja (empleado, m_inicio, m_fin, t_inicio, t_fin) values (?,?,?,?,?)");
    $insert->bind_param('issss', $id, $m_inicio, $m_fin, $t_inicio, $t_fin);
    $insert->execute();
    $insert->close();
    $con->close();
}

function getSchedule(){
    $con = createConnection();
    $consulta = $con->query("SELECT m_apertura, m_cierre, t_apertura, t_cierre from horario");
    $fila = $consulta->fetch_array(MYSQLI_ASSOC);
    $horario["apertura_m"] = $fila["m_apertura"];
    $horario["cierre_m"] = $fila["m_cierre"];
    $horario["apertura_t"] = $fila["t_apertura"];
    $horario["cierre_t"] = $fila["t_cierre"];
    $con->close();
    return $horario;
}

function setDeactivation($id, $inicio, $fin){
    $con = createConnection();
    $insertar = $con->prepare("UPDATE personas set f_inicio = ?, f_fin = ? where id = ?");
    $insertar->bind_param('ssi', $inicio, $fin, $id);
    $insertar->execute();
    $insertar->close();
    $con->close();
}
function cortarSeg($tiempo){
    $corte = implode(':', explode(':', $tiempo, -1));
    return $corte;
}

// function getServicesCheckboxs(){
//     $contador = 1;
//     $con = createConnection();
//     $consulta = $con->query("SELECT id, nombre from servicios where activo = 1");
//     while($fila = $consulta->fetch_array(MYSQLI_ASSOC)){
//         $servicio = "servicio".$contador;
//         echo "<input type='checkbox' value='$fila[id]' name='servicios[]'>$fila[nombre]";
//     }
//     $con->close();
// }

function linkServiceToEmployee($empleado, $servicio){
    $con = createConnection();
    $insert = $con->prepare("INSERT INTO realiza (empleado, servicio) values (?,?)");
    $insert->bind_param('ii', $empleado, $servicio);
    $insert->execute();
    $insert->close();
    $con->close();
}

function formatDate($dia){
    return $dia < 10 && strlen($dia) < 2 ? '0'.$dia : $dia;
}

function getServiciosSelect(){
    $con = createConnection();
    $consulta = $con->query("SELECT nombre, id from servicios where activo = 1");
    while($fila = $consulta->fetch_array(MYSQLI_ASSOC)){
        echo "<option value='$fila[id]'>$fila[nombre]</option>";
    }
    $con->close();
}