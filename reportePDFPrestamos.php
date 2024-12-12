<?php
require_once 'fpdf/fpdf.php';
require_once 'conexion.php';

// Filtros iniciales de mes y año
$mes = isset($_GET['mes']) ? $_GET['mes'] : date('m');
$anio = isset($_GET['anio']) ? $_GET['anio'] : date('Y');

// Nuevos filtros
$id_usuario = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';
$id_libro = isset($_GET['id_libro']) ? $_GET['id_libro'] : '';
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
$sql = "SELECT * FROM prestamos WHERE MONTH(fecha_creacion) = :mes AND YEAR(fecha_creacion) = :anio";
$params = [
    ':mes' => $mes,
    ':anio' => $anio
];

// Agregar filtros adicionales si se seleccionan
if (!empty($id_usuario)) {
    $sql .= " AND id_usuario = :id_usuario";
    $params[':id_usuario'] = $id_usuario;
}
if (!empty($id_libro)) {
    $sql .= " AND id_libro = :id_libro";
    $params[':id_libro'] = $id_libro;
}
if (!empty($estatus)) {
    $sql .= " AND estatus = :estatus";
    $params[':estatus'] = $estatus;
}

// Preparar y ejecutar la consulta
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$prestamos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Crear el PDF
class PDF extends FPDF {
    // Encabezado
    function Header() {
        $this->Image('logo.png', 10, 6, 30); // Asegúrate de tener un archivo de logo en tu proyecto
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(80);
        $this->Cell(110, 10, utf8_decode('Reporte Mensual de Préstamos'), 0, 0, 'C');
        $this->Ln(20);
    }

    // Pie de página
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Obtener el mes en español
$nombreMes = obtenerMesEnEspanol($mes);

$pdf = new PDF('L'); // 'L' para Landscape (horizontal)
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10); // Cambiar el tamaño de la fuente a 10 puntos

// Información del reporte
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode('Préstamos creados en el mes de ' . ucfirst($nombreMes) . ' de ' . $anio), 0, 1, 'C');
$pdf->Ln(10);

// Encabezados de la tabla
$pdf->SetFillColor(200, 220, 255);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(15, 15, utf8_decode('ID'), 1, 0, 'C', true);
$pdf->Cell(30, 15, utf8_decode('ID Usuario'), 1, 0, 'C', true);
$pdf->Cell(30, 15, utf8_decode('Fecha Préstamo'), 1, 0, 'C', true);
$pdf->Cell(30, 15, utf8_decode('Fecha Devolución'), 1, 0, 'C', true);
$pdf->Cell(70, 15, utf8_decode('Detalle Préstamo'), 1, 0, 'C', true);
$pdf->Cell(20, 15, utf8_decode('Estatus'), 1, 0, 'C', true);
$pdf->Cell(30, 15, utf8_decode('ID Libro'), 1, 0, 'C', true);
$pdf->Cell(35, 15, utf8_decode('Fecha Creación'), 1, 1, 'C', true);

// Cuerpo de la tabla
$pdf->SetFont('Arial', '', 10);
foreach ($prestamos as $prestamo) {
    $pdf->Cell(15, 15, $prestamo['id_prestamo'], 1);
    $pdf->Cell(30, 15, $prestamo['id_usuario'], 1);
    $pdf->Cell(30, 15, $prestamo['fecha_prestamo'], 1);
    $pdf->Cell(30, 15, $prestamo['fecha_devolucion'], 1);
    $pdf->Cell(70, 15, utf8_decode($prestamo['detalle_prestamo']), 1);
    $pdf->Cell(20, 15, $prestamo['estatus'] ? 'Activo' : 'Inactivo', 1);
    $pdf->Cell(30, 15, $prestamo['id_libro'], 1);
    $pdf->Cell(35, 15, $prestamo['fecha_creacion'], 1);
    $pdf->Ln();
}

// Salida del PDF
$pdf->Output('I', 'Reporte_Mensual_Prestamos_' . ucfirst($nombreMes) . '_' . $anio . '.pdf');
?>
