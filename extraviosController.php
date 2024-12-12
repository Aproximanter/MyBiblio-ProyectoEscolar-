<?php
require_once 'conexion.php';

// extraviosController.php
function obtenerExtravios($pdo) {
    $sql = "SELECT e.*, u.nombre AS usuario_nombre, u.apellido AS usuario_apellido, l.titulo AS libro_titulo 
            FROM extravios e 
            JOIN usuarios u ON e.id_usuario = u.id_usuario 
            JOIN libros l ON e.id_libro = l.id_libro";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerExtravioPorId($pdo, $id_extravio) {
    $sql = "SELECT * FROM extravios WHERE id_extravio = :id_extravio";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_extravio', $id_extravio, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function crearExtravio($pdo, $id_usuario, $id_libro, $fecha_extravio, $estatus) {
    $sql = "INSERT INTO extravios (id_usuario, id_libro, fecha_extravio, estatus) VALUES (:id_usuario, :id_libro, :fecha_extravio, :estatus)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->bindParam(':id_libro', $id_libro, PDO::PARAM_INT);
    $stmt->bindParam(':fecha_extravio', $fecha_extravio, PDO::PARAM_STR);
    $stmt->bindParam(':estatus', $estatus, PDO::PARAM_INT);
    return $stmt->execute();
}

function actualizarExtravio($pdo, $id_extravio, $id_usuario, $id_libro, $fecha_extravio, $estatus) {
    $sql = "UPDATE extravios SET id_usuario = :id_usuario, id_libro = :id_libro, fecha_extravio = :fecha_extravio, estatus = :estatus WHERE id_extravio = :id_extravio";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_extravio', $id_extravio, PDO::PARAM_INT);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->bindParam(':id_libro', $id_libro, PDO::PARAM_INT);
    $stmt->bindParam(':fecha_extravio', $fecha_extravio, PDO::PARAM_STR);
    $stmt->bindParam(':estatus', $estatus, PDO::PARAM_INT);
    return $stmt->execute();
}

function eliminarExtravio($pdo, $id_extravio) {
    $sql = "DELETE FROM extravios WHERE id_extravio = :id_extravio";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_extravio', $id_extravio, PDO::PARAM_INT);
    return $stmt->execute();
}

// Manejo de las solicitudes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $id_usuario = $_POST['id_usuario'];
        $id_libro = $_POST['id_libro'];
        $fecha_extravio = $_POST['fecha_extravio'];
        $estatus = $_POST['estatus'];
        if (crearExtravio($pdo, $id_usuario, $id_libro, $fecha_extravio, $estatus)) {
            header('Location: extravios.php?success=created');
        } else {
            header('Location: extravios.php?error=1');
        }
    } elseif (isset($_POST['update'])) {
        $id_extravio = $_POST['id_extravio'];
        $id_usuario = $_POST['id_usuario'];
        $id_libro = $_POST['id_libro'];
        $fecha_extravio = $_POST['fecha_extravio'];
        $estatus = $_POST['estatus'];
        if (actualizarExtravio($pdo, $id_extravio, $id_usuario, $id_libro, $fecha_extravio, $estatus)) {
            header('Location: extravios.php?success=updated');
        } else {
            header('Location: extravios.php?error=1');
        }
    } elseif (isset($_POST['delete'])) {
        $id_extravio = $_POST['id_extravio'];
        if (eliminarExtravio($pdo, $id_extravio)) {
            header('Location: extravios.php?success=deleted');
        } else {
            header('Location: extravios.php?error=1');
        }
    }
    exit;
}
?>