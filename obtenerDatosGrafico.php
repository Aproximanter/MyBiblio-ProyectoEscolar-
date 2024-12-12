<?php
require_once 'conexion.php';

$tabla = 'reservascubiculos'; // Puedes cambiar esto según la tabla que quieras graficar
$datos = obtenerDatos($pdo, $tabla);

$labels = [];
$values = [];

foreach ($datos as $dato) {
    $labels[] = $dato['fecha_reserva']; // Cambia esto según la columna que quieras usar como etiqueta
    $values[] = $dato['id_reserva']; // Cambia esto según la columna que quieras usar como valor
}

echo json_encode(['labels' => $labels, 'values' => $values]);
?>