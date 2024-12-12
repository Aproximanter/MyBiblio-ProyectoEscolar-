<?php
require_once 'conexion.php';

// reservasCubiculosController.php
function obtenerReservasCubiculos($pdo) {
    $sql = "SELECT r.*, u.nombre AS usuario_nombre, u.apellido AS usuario_apellido, c.tipo AS cubiculo_tipo 
            FROM reservascubiculos r 
            JOIN usuarios u ON r.id_usuario = u.id_usuario 
            JOIN cubiculos c ON r.id_cubiculo = c.id_cubiculo";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function obtenerReservaCubiculoPorId($pdo, $id_reserva) {
    $sql = "SELECT * FROM reservascubiculos WHERE id_reserva = :id_reserva";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_reserva', $id_reserva, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function crearReservaCubiculo($pdo, $id_usuario, $id_cubiculo, $fecha_reserva, $hora_inicio, $hora_fin, $estatus) {
    $sql = "INSERT INTO reservascubiculos (id_usuario, id_cubiculo, fecha_reserva, hora_inicio, hora_fin, estatus) VALUES (:id_usuario, :id_cubiculo, :fecha_reserva, :hora_inicio, :hora_fin, :estatus)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->bindParam(':id_cubiculo', $id_cubiculo, PDO::PARAM_INT);
    $stmt->bindParam(':fecha_reserva', $fecha_reserva, PDO::PARAM_STR);
    $stmt->bindParam(':hora_inicio', $hora_inicio, PDO::PARAM_STR);
    $stmt->bindParam(':hora_fin', $hora_fin, PDO::PARAM_STR);
    $stmt->bindParam(':estatus', $estatus, PDO::PARAM_INT);
    return $stmt->execute();
}

function actualizarReservaCubiculo($pdo, $id_reserva, $id_usuario, $id_cubiculo, $fecha_reserva, $hora_inicio, $hora_fin, $estatus) {
    $sql = "UPDATE reservascubiculos SET id_usuario = :id_usuario, id_cubiculo = :id_cubiculo, fecha_reserva = :fecha_reserva, hora_inicio = :hora_inicio, hora_fin = :hora_fin, estatus = :estatus WHERE id_reserva = :id_reserva";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_reserva', $id_reserva, PDO::PARAM_INT);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->bindParam(':id_cubiculo', $id_cubiculo, PDO::PARAM_INT);
    $stmt->bindParam(':fecha_reserva', $fecha_reserva, PDO::PARAM_STR);
    $stmt->bindParam(':hora_inicio', $hora_inicio, PDO::PARAM_STR);
    $stmt->bindParam(':hora_fin', $hora_fin, PDO::PARAM_STR);
    $stmt->bindParam(':estatus', $estatus, PDO::PARAM_INT);
    return $stmt->execute();
}

function eliminarReservaCubiculo($pdo, $id_reserva) {
    $sql = "DELETE FROM reservascubiculos WHERE id_reserva = :id_reserva";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_reserva', $id_reserva, PDO::PARAM_INT);
    return $stmt->execute();
}

// Manejo de las solicitudes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $id_usuario = $_POST['id_usuario'];
        $id_cubiculo = $_POST['id_cubiculo'];
        $fecha_reserva = $_POST['fecha_reserva'];
        $hora_inicio = $_POST['hora_inicio'];
        $hora_fin = $_POST['hora_fin'];
        $estatus = $_POST['estatus'];
        if (crearReservaCubiculo($pdo, $id_usuario, $id_cubiculo, $fecha_reserva, $hora_inicio, $hora_fin, $estatus)) {
            header('Location: reservasCubiculos.php?success=created');
        } else {
            header('Location: reservasCubiculos.php?error=1');
        }
    } elseif (isset($_POST['update'])) {
        $id_reserva = $_POST['id_reserva'];
        $id_usuario = $_POST['id_usuario'];
        $id_cubiculo = $_POST['id_cubiculo'];
        $fecha_reserva = $_POST['fecha_reserva'];
        $hora_inicio = $_POST['hora_inicio'];
        $hora_fin = $_POST['hora_fin'];
        $estatus = $_POST['estatus'];
        if (actualizarReservaCubiculo($pdo, $id_reserva, $id_usuario, $id_cubiculo, $fecha_reserva, $hora_inicio, $hora_fin, $estatus)) {
            header('Location: reservasCubiculos.php?success=updated');
        } else {
            header('Location: reservasCubiculos.php?error=1');
        }
    } elseif (isset($_POST['delete'])) {
        $id_reserva = $_POST['id_reserva'];
        if (eliminarReservaCubiculo($pdo, $id_reserva)) {
            header('Location: reservasCubiculos.php?success=deleted');
        } else {
            header('Location: reservasCubiculos.php?error=1');
        }
    }
    exit;
}
?>