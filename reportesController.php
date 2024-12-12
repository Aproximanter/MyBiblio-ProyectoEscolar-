<?php
require_once 'conexion.php';

function generarReporte($pdo, $tabla, $mes, $anio) {
    $sql = "SELECT *, COUNT(*) as cantidad FROM $tabla WHERE MONTH(fecha_servicio) = :mes AND YEAR(fecha_servicio) = :anio GROUP BY fecha_servicio";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':mes', $mes, PDO::PARAM_INT);
    $stmt->bindParam(':anio', $anio, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>