<?php
    session_start();
    require_once("functions.php");
if($_SESSION['user']!=="admin@admin.com"){
    $usuario=$_SESSION['user'];
    $conexion=createConnection();
    $consulta=$conexion->query("select id from personas where correo='$usuario'");
    $num=$consulta->fetch_array(MYSQLI_ASSOC);
    $id=$num['id'];
    $conexion->close();
}


require('fpdf185/fpdf.php');

class PDF extends FPDF
{
// Cargar los datos
function LoadData($id)
{
    $data = array();
    $currentDate = date('Y-m-d');
    
    $conexion=createConnection();
    $preparada=$conexion->prepare("select fecha,hora,cliente,trabajador,nombre,servicio from citas,servicios where (citas.cliente=$id or citas.trabajador=$id) and servicios.id=servicio and fecha=? order by fecha asc");
    $preparada->bind_param("s",$currentDate);
    $preparada->bind_result($fecha,$hora,$cliente,$trabajador,$servicio_nom,$servicio);
    $preparada->execute();
    $preparada->store_result();
    if($preparada->num_rows>0){
        while($preparada->fetch()){
            $formato=explode(":",$hora);
            $hora=$formato[0].":".$formato[1];

            $consulta=$conexion->query("select nombre from personas where id=$cliente");
            $cliente_nom=$consulta->fetch_array(MYSQLI_NUM);
            $consulta->close();

            $consulta=$conexion->query("select nombre from personas where id=$trabajador");
            $trabajador_nom=$consulta->fetch_array(MYSQLI_NUM);
            $consulta->close();
            
            $lista[]=$fecha;
            $lista[]=$hora;
            $lista[]=$cliente_nom[0];
            $lista[]=$trabajador_nom[0];
            $lista[]=$servicio_nom;
            $data[]=$lista;
        }
    }else{
        $data[]="No hay citas para hoy";
    }
    $conexion->close();

    return $data;
}

// Tabla simple
function BasicTable($header, $data)
{
    // Cabecera
    foreach($header as $col)
        $this->Cell(38,7,$col,1);
    $this->Ln();
    // Datos
    foreach($data as $row)
    {
        foreach($row as $col)
            $this->Cell(38,6,$col,1);
        $this->Ln();
    }
}

// Una tabla más completa
function ImprovedTable($header, $data)
{
    // Anchuras de las columnas
    $w = array(38, 38, 38, 38, 38);
    // Cabeceras
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C');
    $this->Ln();
    // Datos
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row[0],'LR');
        $this->Cell($w[1],6,$row[1],'LR');
        // $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
        // $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
        $this->Ln();
    }
    // Línea de cierre
    $this->Cell(array_sum($w),0,'','T');
}

// Tabla coloreada
function FancyTable($header, $data)
{
    // Colores, ancho de línea y fuente en negrita
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(255);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    // Cabecera
    $w = array(38, 38, 38, 38, 38);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    // Restauración de colores y fuentes
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('');
    // Datos
    $fill = false;
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
        $this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
        // $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R',$fill);
        // $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R',$fill);
        $this->Ln();
        $fill = !$fill;
    }
    // Línea de cierre
    $this->Cell(array_sum($w),0,'','T');
}
}

$pdf = new PDF();
// Títulos de las columnas
$header = array('Fecha', 'Hora', 'Cliente', 'Trabajador', 'Servicio');
// Carga de datos
$data = $pdf->LoadData($id);
$pdf->SetFont('Arial','',14);
$pdf->AddPage();
$pdf->BasicTable($header,$data);
$pdf->AddPage();
$pdf->ImprovedTable($header,$data);
$pdf->AddPage();
$pdf->FancyTable($header,$data);
$pdf->Output();
?>