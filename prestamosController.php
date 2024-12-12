<?php
require_once 'conexion.php';

// prestamosController.php
function obtenerPrestamos($pdo) {
    $sql = "SELECT p.*, u.nombre AS usuario_nombre, u.apellido AS usuario_apellido, l.titulo AS libro_titulo 
            FROM prestamos p 
            JOIN usuarios u ON p.id_usuario = u.id_usuario 
            JOIN libros l ON p.id_libro = l.id_libro";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerPrestamoPorId($pdo, $id_prestamo) {
    $sql = "SELECT * FROM prestamos WHERE id_prestamo = :id_prestamo";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_prestamo', $id_prestamo, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function crearPrestamo($pdo, $id_usuario, $id_libro, $fecha_prestamo, $fecha_devolucion, $detalle_prestamo, $estatus) {
    $sql = "INSERT INTO prestamos (id_usuario, id_libro, fecha_prestamo, fecha_devolucion, detalle_prestamo, estatus) VALUES (:id_usuario, :id_libro, :fecha_prestamo, :fecha_devolucion, :detalle_prestamo, :estatus)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->bindParam(':id_libro', $id_libro, PDO::PARAM_INT);
    $stmt->bindParam(':fecha_prestamo', $fecha_prestamo, PDO::PARAM_STR);
    $stmt->bindParam(':fecha_devolucion', $fecha_devolucion, PDO::PARAM_STR);
    $stmt->bindParam(':detalle_prestamo', $detalle_prestamo, PDO::PARAM_STR);
    $stmt->bindParam(':estatus', $estatus, PDO::PARAM_INT);
    return $stmt->execute();
}

function actualizarPrestamo($pdo, $id_prestamo, $id_usuario, $id_libro, $fecha_prestamo, $fecha_devolucion, $detalle_prestamo, $estatus) {
    $sql = "UPDATE prestamos SET id_usuario = :id_usuario, id_libro = :id_libro, fecha_prestamo = :fecha_prestamo, fecha_devolucion = :fecha_devolucion, detalle_prestamo = :detalle_prestamo, estatus = :estatus WHERE id_prestamo = :id_prestamo";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_prestamo', $id_prestamo, PDO::PARAM_INT);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->bindParam(':id_libro', $id_libro, PDO::PARAM_INT);
    $stmt->bindParam(':fecha_prestamo', $fecha_prestamo, PDO::PARAM_STR);
    $stmt->bindParam(':fecha_devolucion', $fecha_devolucion, PDO::PARAM_STR);
    $stmt->bindParam(':detalle_prestamo', $detalle_prestamo, PDO::PARAM_STR);
    $stmt->bindParam(':estatus', $estatus, PDO::PARAM_INT);
    return $stmt->execute();
}

function eliminarPrestamo($pdo, $id_prestamo) {
    $sql = "DELETE FROM prestamos WHERE id_prestamo = :id_prestamo";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_prestamo', $id_prestamo, PDO::PARAM_INT);
    return $stmt->execute();
}

// Manejo de las solicitudes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $id_usuario = $_POST['id_usuario'];
        $id_libro = $_POST['id_libro'];
        $fecha_prestamo = $_POST['fecha_prestamo'];
        $fecha_devolucion = $_POST['fecha_devolucion'];
        $detalle_prestamo = $_POST['detalle_prestamo'];
        $estatus = $_POST['estatus'];
        if (crearPrestamo($pdo, $id_usuario, $id_libro, $fecha_prestamo, $fecha_devolucion, $detalle_prestamo, $estatus)) {
            header('Location: prestamos.php?success=created');
        } else {
            header('Location: prestamos.php?error=1');
        }
    } elseif (isset($_POST['update'])) {
        $id_prestamo = $_POST['id_prestamo'];
        $id_usuario = $_POST['id_usuario'];
        $id_libro = $_POST['id_libro'];
        $fecha_prestamo = $_POST['fecha_prestamo'];
        $fecha_devolucion = $_POST['fecha_devolucion'];
        $detalle_prestamo = $_POST['detalle_prestamo'];
        $estatus = $_POST['estatus'];
        if (actualizarPrestamo($pdo, $id_prestamo, $id_usuario, $id_libro, $fecha_prestamo, $fecha_devolucion, $detalle_prestamo, $estatus)) {
            header('Location: prestamos.php?success=updated');
        } else {
            header('Location: prestamos.php?error=1');
        }
    } elseif (isset($_POST['delete'])) {
        $id_prestamo = $_POST['id_prestamo'];
        if (eliminarPrestamo($pdo, $id_prestamo)) {
            header('Location: prestamos.php?success=deleted');
        } else {
            header('Location: prestamos.php?error=1');
        }
    }
    exit;
}
?>