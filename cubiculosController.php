<?php
require_once 'conexion.php';

function obtenerCubiculos($pdo) {
    $sql = "SELECT * FROM cubiculos";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerCubiculoPorId($pdo, $id_cubiculo) {
    $sql = "SELECT * FROM cubiculos WHERE id_cubiculo = :id_cubiculo";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_cubiculo', $id_cubiculo, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function crearCubiculo($pdo, $tipo, $estatus) {
    $sql = "INSERT INTO cubiculos (tipo, estatus) VALUES (:tipo, :estatus)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
    $stmt->bindParam(':estatus', $estatus, PDO::PARAM_INT);
    return $stmt->execute();
}

function actualizarCubiculo($pdo, $id_cubiculo, $tipo, $estatus) {
    $sql = "UPDATE cubiculos SET tipo = :tipo, estatus = :estatus WHERE id_cubiculo = :id_cubiculo";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_cubiculo', $id_cubiculo, PDO::PARAM_INT);
    $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
    $stmt->bindParam(':estatus', $estatus, PDO::PARAM_INT);
    return $stmt->execute();
}

function eliminarCubiculo($pdo, $id_cubiculo) {
    $sql = "DELETE FROM cubiculos WHERE id_cubiculo = :id_cubiculo";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_cubiculo', $id_cubiculo, PDO::PARAM_INT);
    return $stmt->execute();
}

// Manejo de las solicitudes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $tipo = $_POST['tipo'];
        $estatus = $_POST['estatus'];
        if (crearCubiculo($pdo, $tipo, $estatus)) {
            header('Location: cubiculos.php?success=created');
        } else {
            header('Location: cubiculos.php?error=1');
        }
    } elseif (isset($_POST['update'])) {
        $id_cubiculo = $_POST['id_cubiculo'];
        $tipo = $_POST['tipo'];
        $estatus = $_POST['estatus'];
        if (actualizarCubiculo($pdo, $id_cubiculo, $tipo, $estatus)) {
            header('Location: cubiculos.php?success=updated');
        } else {
            header('Location: cubiculos.php?error=1');
        }
    } elseif (isset($_POST['delete'])) {
        $id_cubiculo = $_POST['id_cubiculo'];
        if (eliminarCubiculo($pdo, $id_cubiculo)) {
            header('Location: cubiculos.php?success=deleted');
        } else {
            header('Location: cubiculos.php?error=1');
        }
    }
    exit;
}
?>