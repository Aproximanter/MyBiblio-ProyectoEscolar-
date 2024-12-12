<?php
require_once 'fpdf/fpdf.php';
require_once 'conexion.php';

class PDF extends FPDF {
    function Header() {
        $this->Image('logo.png', 10, 6, 30); 
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(80);
        $this->Cell(30, 10, 'Reporte Mensual de Usuarios', 0, 0, 'C');
        $this->Ln(20);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$mes = isset($_GET['mes']) ? $_GET['mes'] : date('m');
$anio = isset($_GET['anio']) ? $_GET['anio'] : date('Y');

// Consulta filtrada por mes y año
$sql = "SELECT * FROM usuarios WHERE MONTH(fecha_creacion) = :mes AND YEAR(fecha_creacion) = :anio";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':mes', $mes, PDO::PARAM_INT);
$stmt->bindParam(':anio', $anio, PDO::PARAM_INT);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Crear PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Agregar datos de los usuarios al PDF
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Usuarios Registrados en ' . date('F', mktime(0, 0, 0, $mes, 1)) . ' ' . $anio, 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 12);
foreach ($usuarios as $usuario) {
    $pdf->Cell(50, 10, 'ID:', 1);
    $pdf->Cell(0, 10, $usuario['id_usuario'], 1, 1);
    $pdf->Cell(50, 10, 'Nombre:', 1);
    $pdf->Cell(0, 10, $usuario['nombre'], 1, 1);
    $pdf->Cell(50, 10, 'Apellido:', 1);
    $pdf->Cell(0, 10, $usuario['apellido'], 1, 1);
    $pdf->Cell(50, 10, 'Correo:', 1);
    $pdf->Cell(0, 10, $usuario['correo'], 1, 1);
    $pdf->Cell(50, 10, 'Teléfono:', 1);
    $pdf->Cell(0, 10, $usuario['telefono'], 1, 1);
    $pdf->Cell(50, 10, 'Dirección:', 1);
    $pdf->Cell(0, 10, $usuario['direccion'], 1, 1);
    $pdf->Cell(50, 10, 'No. de Control:', 1);
    $pdf->Cell(0, 10, $usuario['no_de_control'], 1, 1);
    $pdf->Cell(50, 10, 'Estatus:', 1);
    $pdf->Cell(0, 10, $usuario['estatus'] ? 'Activo' : 'Inactivo', 1, 1);
    $pdf->Ln(5);
}

$pdf->Output();
?>
