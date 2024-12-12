<?php
require_once 'fpdf/fpdf.php';
require_once 'conexion.php';

// Filtros iniciales de mes y año
$mes = isset($_GET['mes']) ? $_GET['mes'] : date('m');
$anio = isset($_GET['anio']) ? $_GET['anio'] : date('Y');

// Nuevos filtros
$id_usuario = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';
$monto = isset($_GET['monto']) ? $_GET['monto'] : '';
$estatus = isset($_GET['estatus']) ? $_GET['estatus'] : '';

// Función para obtener el mes en español
function obtenerMesEnEspanol($mes) {
    $meses = [
        1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
        5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
        9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
    ];
    return $meses[intval($mes)] ?? '';
}

// Construcción dinámica de la consulta SQL
$sql = "SELECT * FROM multas WHERE MONTH(fecha_creacion) = :mes AND YEAR(fecha_creacion) = :anio";
$params = [
    ':mes' => $mes,
    ':anio' => $anio
];

// Agregar filtros adicionales si se seleccionan
if (!empty($id_usuario)) {
    $sql .= " AND id_usuario = :id_usuario";
    $params[':id_usuario'] = $id_usuario;
}
if (!empty($monto)) {
    $sql .= " AND monto = :monto";
    $params[':monto'] = $monto;
}
if (!empty($estatus)) {
    $sql .= " AND estatus = :estatus";
    $params[':estatus'] = $estatus;
}

// Preparar y ejecutar la consulta
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$multas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Crear el PDF
class PDF extends FPDF {
    // Encabezado
    function Header() {
        $this->Image('logo.png', 10, 6, 30); // Asegúrate de tener un archivo de logo en tu proyecto
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(80);
        $this->Cell(110, 10, utf8_decode('Reporte Mensual de Multas'), 0, 0, 'C');
        $this->Ln(20);
    }

    // Pie de página
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

function convertirTexto($texto) {
    return mb_convert_encoding($texto, 'ISO-8859-1', 'UTF-8');
}

// Obtener el mes en español
$nombreMes = obtenerMesEnEspanol($mes);

$pdf = new PDF('L'); // 'L' para Landscape (horizontal)
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

// Información del reporte
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, convertirTexto('Multas creadas en el mes de ' . ucfirst($nombreMes) . ' de ' . $anio), 0, 1, 'C');
$pdf->Ln(10);

// Encabezados de la tabla
$pdf->SetFillColor(200, 220, 255);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(15, 7, convertirTexto('ID'), 1, 0, 'C', true);
$pdf->Cell(30, 7, convertirTexto('ID Usuario'), 1, 0, 'C', true);
$pdf->Cell(30, 7, convertirTexto('Monto'), 1, 0, 'C', true);
$pdf->Cell(70, 7, convertirTexto('Descripción'), 1, 0, 'C', true);
$pdf->Cell(20, 7, convertirTexto('Estatus'), 1, 0, 'C', true);
$pdf->Cell(40, 7, convertirTexto('Fecha Creación'), 1, 1, 'C', true);

// Cuerpo de la tabla
$pdf->SetFont('Arial', '', 10);
foreach ($multas as $multa) {
    $pdf->Cell(15, 7, $multa['id_multa'], 1);
    $pdf->Cell(30, 7, $multa['id_usuario'], 1);
    $pdf->Cell(30, 7, $multa['monto'], 1);
    $pdf->Cell(70, 7, convertirTexto($multa['descripcion']), 1);
    $pdf->Cell(20, 7, $multa['estatus'] ? 'Activo' : 'Inactivo', 1);
    $pdf->Cell(40, 7, convertirTexto($multa['fecha_creacion']), 1, 1);
}

// Salida del PDF
$pdf->Output('I', 'Reporte_Mensual_Multas_' . ucfirst($nombreMes) . '_' . $anio . '.pdf');
?>
