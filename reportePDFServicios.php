<?php
require_once 'fpdf/fpdf.php';
require_once 'conexion.php';

// Filtros iniciales de mes y año
$mes = isset($_GET['mes']) ? $_GET['mes'] : date('m');
$anio = isset($_GET['anio']) ? $_GET['anio'] : date('Y');

// Nuevos filtros
$id_usuario = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';
$estatus = isset($_GET['estatus']) ? $_GET['estatus'] : '';

// Construcción dinámica de la consulta SQL
$sql = "SELECT s.*, u.nombre AS usuario_nombre, u.apellido AS usuario_apellido 
        FROM servicio s
        JOIN usuarios u ON s.id_usuario = u.id_usuario
        WHERE MONTH(s.fecha_creacion) = :mes AND YEAR(s.fecha_creacion) = :anio";
$params = [
    ':mes' => $mes,
    ':anio' => $anio
];

// Agregar filtros adicionales si se seleccionan
if (!empty($id_usuario)) {
    $sql .= " AND s.id_usuario = :id_usuario";
    $params[':id_usuario'] = $id_usuario;
}
if (!empty($estatus)) {
    $sql .= " AND s.estatus = :estatus";
    $params[':estatus'] = $estatus;
}

// Preparar y ejecutar la consulta
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Crear el PDF
class PDF extends FPDF {
    // Encabezado
    function Header() {
        // Logo
        $this->Image('logo.png', 10, 6, 30); // Asegúrate de tener un archivo de logo en tu proyecto
        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Movernos a la derecha
        $this->Cell(80);
        // Título
        $this->Cell(30, 10, utf8_decode('Reporte Mensual de Servicios'), 0, 0, 'C');
        // Salto de línea
        $this->Ln(20);
    }

    // Pie de página
    function Footer() {
        // Posición a 1.5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Número de página
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF('L'); // 'L' para Landscape (horizontal)
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10); // Cambiar el tamaño de la fuente a 10 puntos

// Información del reporte
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode('Servicios creados en el mes de ' . date('F Y', mktime(0, 0, 0, $mes, 1, $anio))), 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 10); // Cambiar el tamaño de la fuente a 10 puntos
$pdf->Cell(15, 15, utf8_decode('ID'), 1);
$pdf->Cell(60, 15, utf8_decode('Usuario'), 1);
$pdf->Cell(30, 15, utf8_decode('Fecha Servicio'), 1);
$pdf->Cell(30, 15, utf8_decode('Hora Inicio'), 1);
$pdf->Cell(30, 15, utf8_decode('Hora Fin'), 1);
$pdf->Cell(20, 15, utf8_decode('Estatus'), 1);
$pdf->Cell(40, 15, utf8_decode('Fecha Creación'), 1);
$pdf->Ln();

foreach ($servicios as $servicio) {
    $pdf->Cell(15, 15, $servicio['id_servicio'], 1);
    $pdf->Cell(60, 15, utf8_decode($servicio['usuario_nombre'] . ' ' . $servicio['usuario_apellido']), 1);
    $pdf->Cell(30, 15, $servicio['fecha_servicio'], 1);
    $pdf->Cell(30, 15, $servicio['hora_inicio'], 1);
    $pdf->Cell(30, 15, $servicio['hora_fin'], 1);
    $pdf->Cell(20, 15, $servicio['estatus'] ? 'Activo' : 'Inactivo', 1);
    $pdf->Cell(40, 15, $servicio['fecha_creacion'], 1);
    $pdf->Ln();
}

// Salida del PDF
$pdf->Output('I', 'Reporte_Mensual_Servicios_' . date('F_Y', mktime(0, 0, 0, $mes, 1, $anio)) . '.pdf');
?>