<?php
require_once 'conexion.php';

// cartasNoAdeudoController.php
function obtenerCartasNoAdeudo($pdo) {
    $sql = "SELECT c.*, u.nombre, u.apellido FROM cartasnoadeudo c 
            JOIN usuarios u ON c.id_usuario = u.id_usuario";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerCartaNoAdeudoPorId($pdo, $id_carta) {
    $sql = "SELECT * FROM cartasnoadeudo WHERE id_carta = :id_carta";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_carta', $id_carta, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function crearCartaNoAdeudo($pdo, $id_usuario, $fecha_emision, $estatus) {
    $sql = "INSERT INTO cartasnoadeudo (id_usuario, fecha_emision, estatus) VALUES (:id_usuario, :fecha_emision, :estatus)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->bindParam(':fecha_emision', $fecha_emision, PDO::PARAM_STR);
    $stmt->bindParam(':estatus', $estatus, PDO::PARAM_INT);
    return $stmt->execute();
}

function actualizarCartaNoAdeudo($pdo, $id_carta, $id_usuario, $fecha_emision, $estatus) {
    $sql = "UPDATE cartasnoadeudo SET id_usuario = :id_usuario, fecha_emision = :fecha_emision, estatus = :estatus WHERE id_carta = :id_carta";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_carta', $id_carta, PDO::PARAM_INT);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->bindParam(':fecha_emision', $fecha_emision, PDO::PARAM_STR);
    $stmt->bindParam(':estatus', $estatus, PDO::PARAM_INT);
    return $stmt->execute();
}

function eliminarCartaNoAdeudo($pdo, $id_carta) {
    $sql = "DELETE FROM cartasnoadeudo WHERE id_carta = :id_carta";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_carta', $id_carta, PDO::PARAM_INT);
    return $stmt->execute();
}

// Manejo de las solicitudes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $id_usuario = $_POST['id_usuario'];
        $fecha_emision = $_POST['fecha_emision'];
        $estatus = $_POST['estatus'];
        if (crearCartaNoAdeudo($pdo, $id_usuario, $fecha_emision, $estatus)) {
            header('Location: cartasNoAdeudo.php?success=created');
        } else {
            header('Location: cartasNoAdeudo.php?error=1');
        }
    } elseif (isset($_POST['update'])) {
        $id_carta = $_POST['id_carta'];
        $id_usuario = $_POST['id_usuario'];
        $fecha_emision = $_POST['fecha_emision'];
        $estatus = $_POST['estatus'];
        if (actualizarCartaNoAdeudo($pdo, $id_carta, $id_usuario, $fecha_emision, $estatus)) {
            header('Location: cartasNoAdeudo.php?success=updated');
        } else {
            header('Location: cartasNoAdeudo.php?error=1');
        }
    } elseif (isset($_POST['delete'])) {
        $id_carta = $_POST['id_carta'];
        if (eliminarCartaNoAdeudo($pdo, $id_carta)) {
            header('Location: cartasNoAdeudo.php?success=deleted');
        } else {
            header('Location: cartasNoAdeudo.php?error=1');
        }
    }
    exit;
}
?>