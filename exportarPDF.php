<?php
require 'fpdf/fpdf.php';
require 'conexion.php';

// Obtener filtros
$mes = isset($_GET['mes']) ? $_GET['mes'] : date('m');
$anio = isset($_GET['anio']) ? $_GET['anio'] : date('Y');

// Consulta filtrada
$sql = "SELECT * FROM usuarios WHERE MONTH(fecha_creacion) = :mes AND YEAR(fecha_creacion) = :anio";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':mes', $mes, PDO::PARAM_INT);
$stmt->bindParam(':anio', $anio, PDO::PARAM_INT);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Crear el PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(190, 10, "Reporte de Usuarios - $mes/$anio", 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, 10, 'ID', 1);
$pdf->Cell(40, 10, 'Nombre', 1);
$pdf->Cell(40, 10, 'Apellido', 1);
$pdf->Cell(50, 10, 'Correo', 1);
$pdf->Cell(50, 10, 'Fecha Creacion', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 10);
foreach ($usuarios as $usuario) {
    $pdf->Cell(10, 10, $usuario['id_usuario'], 1);
    $pdf->Cell(40, 10, $usuario['nombre'], 1);
    $pdf->Cell(40, 10, $usuario['apellido'], 1);
    $pdf->Cell(50, 10, $usuario['correo'], 1);
    $pdf->Cell(50, 10, $usuario['fecha_creacion'], 1);
    $pdf->Ln();
}

$pdf->Output();
