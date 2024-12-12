<?php
require_once 'fpdf/fpdf.php';
require_once 'conexion.php';
require_once 'librosController.php';
require_once 'autoresController.php';
require_once 'editorialesController.php';

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
        $this->Cell(30, 10, 'Informacion del Libro', 0, 0, 'C');
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

if (isset($_GET['id'])) {
    $id_libro = $_GET['id'];
    $libro = obtenerLibroPorId($pdo, $id_libro);

    if ($libro) {
        $autor = obtenerAutorPorId($pdo, $libro['id_autor']);
        $editorial = obtenerEditorialPorId($pdo, $libro['id_editorial']);

        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);

        // Información del Libro
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Detalles del Libro', 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 10, 'ID:', 1);
        $pdf->Cell(0, 10, $libro['id_libro'], 1, 1);
        $pdf->Cell(50, 10, 'Título:', 1);
        $pdf->Cell(0, 10, $libro['titulo'], 1, 1);
        $pdf->Cell(50, 10, 'Materia:', 1);
        $pdf->Cell(0, 10, $libro['materia'], 1, 1);
        $pdf->Cell(50, 10, 'Código de Barras:', 1);
        $pdf->Cell(0, 10, $libro['codigo_barras'], 1, 1);
        $pdf->Cell(50, 10, 'Código de Color:', 1);
        $pdf->Cell(0, 10, $libro['codigo_color'], 1, 1);
        $pdf->Cell(50, 10, 'Signatura:', 1);
        $pdf->Cell(0, 10, $libro['signatura'], 1, 1);
        $pdf->Cell(50, 10, 'Cantidad:', 1);
        $pdf->Cell(0, 10, $libro['cantidad'], 1, 1);
        $pdf->Cell(50, 10, 'Autor:', 1);
        $pdf->Cell(0, 10, $autor['nombre'] . ' ' . $autor['apellido'], 1, 1);
        $pdf->Cell(50, 10, 'Editorial:', 1);
        $pdf->Cell(0, 10, $editorial['nombre'], 1, 1);
        $pdf->Cell(50, 10, 'Estatus:', 1);
        $pdf->Cell(0, 10, ($libro['estatus'] == 1) ? 'Activo' : 'Inactivo', 1, 1);

        // Generar el código QR
        $libroInfo = json_encode($libro);
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($libroInfo) . '&size=100x100';

        // Insertar el código QR en el PDF
        $pdf->Ln(10);
        $pdf->Cell(0, 10, 'Codigo QR:', 0, 1, 'C');
        $pdf->Image($qrCodeUrl, ($pdf->GetPageWidth() - 50) / 2, $pdf->GetY(), 50, 50, 'PNG');

        // Salida del PDF
        $pdf->Output('I', 'Libro_' . $libro['id_libro'] . '.pdf');
    } else {
        echo "Libro no encontrado.";
    }
} else {
    echo "ID de libro no especificado.";
}
?>