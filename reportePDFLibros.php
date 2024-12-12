<?php
require_once 'fpdf/fpdf.php';
require_once 'conexion.php';

// Filtros iniciales de mes y año
$mes = isset($_GET['mes']) ? $_GET['mes'] : date('m');
$anio = isset($_GET['anio']) ? $_GET['anio'] : date('Y');

// Nuevos filtros
$titulo = isset($_GET['titulo']) ? $_GET['titulo'] : '';
$materia = isset($_GET['materia']) ? $_GET['materia'] : '';
$codigo_barras = isset($_GET['codigo_barras']) ? $_GET['codigo_barras'] : '';
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
$sql = "SELECT * FROM libros WHERE MONTH(fecha_creacion) = :mes AND YEAR(fecha_creacion) = :anio";
$params = [
    ':mes' => $mes,
    ':anio' => $anio
];

// Agregar filtros adicionales si se seleccionan
if (!empty($titulo)) {
    $sql .= " AND titulo LIKE :titulo";
    $params[':titulo'] = '%' . $titulo . '%';
}
if (!empty($materia)) {
    $sql .= " AND materia LIKE :materia";
    $params[':materia'] = '%' . $materia . '%';
}
if (!empty($codigo_barras)) {
    $sql .= " AND codigo_barras = :codigo_barras";
    $params[':codigo_barras'] = $codigo_barras;
}
if (!empty($estatus)) {
    $sql .= " AND estatus = :estatus";
    $params[':estatus'] = $estatus;
}

// Preparar y ejecutar la consulta
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$libros = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Crear el PDF
class PDF extends FPDF {
    // Encabezado
    function Header() {
        $this->Image('logo.png', 10, 6, 30);
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(80);
        $this->Cell(110, 10, utf8_decode('Reporte Mensual de Libros'), 0, 0, 'C');
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

// Título del reporte
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, convertirTexto('Libros creados en el mes de ' . ucfirst($nombreMes) . ' de ' . $anio), 0, 1, 'C');
$pdf->Ln(10);

// Encabezados de la tabla
$pdf->SetFillColor(200, 220, 255);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, 7, convertirTexto('ID'), 1, 0, 'C', true);
$pdf->Cell(50, 7, convertirTexto('Título'), 1, 0, 'C', true);
$pdf->Cell(40, 7, convertirTexto('Materia'), 1, 0, 'C', true);
$pdf->Cell(35, 7, convertirTexto('Cód. Barras'), 1, 0, 'C', true);
$pdf->Cell(35, 7, convertirTexto('Cód. Color'), 1, 0, 'C', true);
$pdf->Cell(35, 7, convertirTexto('Signatura'), 1, 0, 'C', true);
$pdf->Cell(20, 7, convertirTexto('Cant.'), 1, 0, 'C', true);
$pdf->Cell(20, 7, convertirTexto('Estatus'), 1, 0, 'C', true);
$pdf->Cell(35, 7, convertirTexto('Fecha'), 1, 1, 'C', true);

// Cuerpo de la tabla
$pdf->SetFont('Arial', '', 10);
foreach ($libros as $libro) {
    $pdf->Cell(10, 7, $libro['id_libro'], 1);
    $pdf->Cell(50, 7, convertirTexto($libro['titulo']), 1);
    $pdf->Cell(40, 7, convertirTexto($libro['materia']), 1);
    $pdf->Cell(35, 7, $libro['codigo_barras'], 1);
    $pdf->Cell(35, 7, convertirTexto($libro['codigo_color']), 1);
    $pdf->Cell(35, 7, convertirTexto($libro['signatura']), 1);
    $pdf->Cell(20, 7, $libro['cantidad'], 1);
    $pdf->Cell(20, 7, convertirTexto($libro['estatus'] ? 'Activo' : 'Inactivo'), 1);
    $pdf->Cell(35, 7, convertirTexto($libro['fecha_creacion']), 1, 1);
}

// Salida del PDF
$pdf->Output('I', 'Reporte_Mensual_Libros_' . ucfirst($nombreMes) . '_' . $anio . '.pdf');
?>