<?php
require_once 'conexion.php';
require_once 'fpdf/fpdf.php';

// Función para obtener el mes en español
function obtenerMesEnEspanol($mes) {
    $meses = [
        1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
        5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
        9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
    ];
    return $meses[intval($mes)] ?? '';
}

// Clase para generar el PDF
class PDF extends FPDF {
    function Header() {
        $this->Image('logo.png', 10, 6, 30);
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(80);
        $this->Cell(110, 10, utf8_decode('Reporte Mensual de Usuarios'), 0, 0, 'C');
        $this->Ln(20);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

function convertirTexto($texto) {
    return mb_convert_encoding($texto, 'ISO-8859-1', 'UTF-8');
}

// Parámetros de filtro
$mes = isset($_GET['mes']) ? $_GET['mes'] : date('m');
$anio = isset($_GET['anio']) ? $_GET['anio'] : date('Y');
$nombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';
$apellido = isset($_GET['apellido']) ? $_GET['apellido'] : '';
$correo = isset($_GET['correo']) ? $_GET['correo'] : '';
$estatus = isset($_GET['estatus']) ? $_GET['estatus'] : '';
$numero_control = isset($_GET['numero_control']) ? $_GET['numero_control'] : '';

// Construcción de la consulta
$sql = "SELECT * FROM usuarios WHERE MONTH(fecha_creacion) = :mes AND YEAR(fecha_creacion) = :anio";
$params = [':mes' => $mes, ':anio' => $anio];
if ($nombre) {
    $sql .= " AND nombre LIKE :nombre";
    $params[':nombre'] = "%$nombre%";
}
if ($apellido) {
    $sql .= " AND apellido LIKE :apellido";
    $params[':apellido'] = "%$apellido%";
}
if ($correo) {
    $sql .= " AND correo LIKE :correo";
    $params[':correo'] = "%$correo%";
}
if ($estatus !== '') {
    $sql .= " AND estatus = :estatus";
    $params[':estatus'] = $estatus;
}
if ($numero_control) {
    $sql .= " AND no_de_control LIKE :numero_control";
    $params[':numero_control'] = "%$numero_control%";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Generación del PDF
$nombreMes = obtenerMesEnEspanol($mes);
$pdf = new PDF('L'); // Horizontal
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

// Título del reporte
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, convertirTexto('Usuarios creados en el mes de ' . ucfirst($nombreMes) . ' de ' . $anio), 0, 1, 'C');
$pdf->Ln(10);

// Encabezados de la tabla
$pdf->SetFillColor(200, 220, 255);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, 7, convertirTexto('ID'), 1, 0, 'C', true);
$pdf->Cell(40, 7, convertirTexto('Nombre'), 1, 0, 'C', true);
$pdf->Cell(40, 7, convertirTexto('Apellido'), 1, 0, 'C', true);
$pdf->Cell(50, 7, convertirTexto('Correo'), 1, 0, 'C', true);
$pdf->Cell(30, 7, convertirTexto('Teléfono'), 1, 0, 'C', true);
$pdf->Cell(30, 7, convertirTexto('Estatus'), 1, 0, 'C', true);
$pdf->Cell(35, 7, convertirTexto('Fecha'), 1, 1, 'C', true);

// Cuerpo de la tabla
$pdf->SetFont('Arial', '', 10);
foreach ($usuarios as $usuario) {
    $pdf->Cell(10, 7, $usuario['id_usuario'], 1);
    $pdf->Cell(40, 7, convertirTexto($usuario['nombre']), 1);
    $pdf->Cell(40, 7, convertirTexto($usuario['apellido']), 1);
    $pdf->Cell(50, 7, convertirTexto($usuario['correo']), 1);
    $pdf->Cell(30, 7, $usuario['telefono'], 1);
    $pdf->Cell(30, 7, convertirTexto($usuario['estatus'] ? 'Activo' : 'Inactivo'), 1);
    $pdf->Cell(35, 7, convertirTexto($usuario['fecha_creacion']), 1, 1);
}

// Salida del PDF
$pdf->Output('I', 'Reporte_Mensual_Usuarios_' . ucfirst($nombreMes) . '_' . $anio . '.pdf');
?>
